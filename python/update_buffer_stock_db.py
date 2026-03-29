"""
Update Buffer Stock ke Database
Script untuk menghitung buffer stock dan update ke tabel master_items_stock di database
"""

import pymysql
import os
import logging
from typing import Dict, List, Optional
from buffer_stock_calc import BufferStockCalculator
from dotenv import load_dotenv

# Load environment variables
load_dotenv()

# Setup logging
logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s | %(levelname)s | %(name)s | %(message)s",
)
logger = logging.getLogger(__name__)


class BufferStockDatabaseUpdater:
    """Class untuk update buffer stock ke database"""
    
    def __init__(
        self, 
        excel_path: str,
        db_host: str = None,
        db_user: str = None,
        db_password: str = None,
        db_name: str = None,
        avg_lead_time: float = 5.4,
        max_lead_time: float = 7
    ):
        """
        Initialize updater dengan koneksi database
        
        Args:
            excel_path: Path ke file Excel yang berisi data penjualan
            db_host: Database host (default: dari env atau localhost)
            db_user: Database user (default: dari env atau root)
            db_password: Database password (default: dari env atau '')
            db_name: Database name (default: dari env atau skripsi_forecasting)
            avg_lead_time: Average lead time dalam hari
            max_lead_time: Maximum lead time dalam hari
        """
        self.excel_path = excel_path
        self.avg_lead_time = avg_lead_time
        self.max_lead_time = max_lead_time
        
        # Get database config dari environment atau default
        self.db_host = db_host or os.getenv('DB_HOST', 'localhost')
        self.db_user = db_user or os.getenv('DB_USERNAME', 'root')
        self.db_password = db_password or os.getenv('DB_PASSWORD', '')
        self.db_name = db_name or os.getenv('DB_DATABASE', 'skripsi_forecasting')
        self.db_port = int(os.getenv('DB_PORT', 3306))
        
        # Initialize calculator
        try:
            self.calculator = BufferStockCalculator(
                excel_path=excel_path,
                avg_lead_time=avg_lead_time,
                max_lead_time=max_lead_time
            )
            logger.info("✓ Buffer Stock Calculator initialized successfully")
        except Exception as e:
            logger.error(f"✗ Failed to initialize calculator: {str(e)}")
            raise
    
    def connect_to_database(self) -> pymysql.Connection:
        """Connect ke database"""
        try:
            connection = pymysql.connect(
                host=self.db_host,
                user=self.db_user,
                password=self.db_password,
                database=self.db_name,
                port=self.db_port,
                charset='utf8mb4'
            )
            logger.info(f"✓ Connected to database: {self.db_name}")
            return connection
        except pymysql.Error as e:
            logger.error(f"✗ Database connection error: {str(e)}")
            raise
    
    def get_items_mapping(self, connection: pymysql.Connection) -> Dict[str, int]:
        """
        Get mapping antara product name (dari Excel) dengan item_id (dari database)
        
        Returns:
            Dict dengan format {product_name: item_id}
        """
        try:
            cursor = connection.cursor(pymysql.cursors.DictCursor)
            query = "SELECT item_id, name_item FROM master_items WHERE deleted_at IS NULL"
            cursor.execute(query)
            
            items = cursor.fetchall()
            mapping = {item['name_item']: item['item_id'] for item in items}
            
            logger.info(f"✓ Loaded {len(mapping)} items from database")
            return mapping
            
        except pymysql.Error as e:
            logger.error(f"✗ Error fetching items: {str(e)}")
            raise
        finally:
            cursor.close()
    
    def update_buffer_stocks(self, inventory_id: int = 1) -> Dict[str, any]:
        """
        Update buffer stock di database untuk inventory tertentu
        
        Args:
            inventory_id: ID inventori yang akan di-update (default: 1)
            
        Returns:
            Dict berisi statistics update
        """
        connection = None
        try:
            connection = self.connect_to_database()
            
            # Get items mapping
            items_mapping = self.get_items_mapping(connection)
            
            # Get all buffer stocks dari calculator
            all_buffer_stocks = self.calculator.get_all_buffer_stocks()
            
            # Update statistics
            updated_count = 0
            not_found_count = 0
            error_count = 0
            
            cursor = connection.cursor()
            
            for buffer_data in all_buffer_stocks:
                product_name = buffer_data['product_name']
                buffer_stock = int(round(buffer_data['buffer_stock']))
                
                # Cari item_id dari product name
                if product_name not in items_mapping:
                    logger.warning(f"⚠ Product not found in database: {product_name}")
                    not_found_count += 1
                    continue
                
                item_id = items_mapping[product_name]
                
                try:
                    # Update query
                    update_query = """
                        UPDATE master_items_stock 
                        SET buffer_stock = %s, updated_at = NOW()
                        WHERE item_id = %s AND inventory_id = %s
                    """
                    
                    cursor.execute(update_query, (buffer_stock, item_id, inventory_id))
                    
                    if cursor.rowcount > 0:
                        updated_count += 1
                        logger.info(f"✓ Updated {product_name}: buffer_stock = {buffer_stock}")
                    else:
                        # Jika belum ada record, insert baru
                        insert_query = """
                            INSERT INTO master_items_stock 
                            (item_id, inventory_id, stock, buffer_stock, created_at, updated_at)
                            VALUES (%s, %s, %s, %s, NOW(), NOW())
                        """
                        # Default stock = 0 jika belum ada
                        cursor.execute(insert_query, (item_id, inventory_id, 0, buffer_stock))
                        updated_count += 1
                        logger.info(f"✓ Inserted new: {product_name}: buffer_stock = {buffer_stock}")
                    
                except pymysql.Error as e:
                    logger.error(f"✗ Error updating {product_name}: {str(e)}")
                    error_count += 1
                    continue
            
            # Commit changes
            connection.commit()
            logger.info("✓ Changes committed to database")
            
            # Log summary
            total_processed = updated_count + not_found_count + error_count
            logger.info("\n" + "=" * 80)
            logger.info("UPDATE SUMMARY")
            logger.info("=" * 80)
            logger.info(f"Total items processed: {total_processed}")
            logger.info(f"Successfully updated: {updated_count}")
            logger.info(f"Items not found in database: {not_found_count}")
            logger.info(f"Errors occurred: {error_count}")
            logger.info("=" * 80)
            
            return {
                'success': True,
                'updated_count': updated_count,
                'not_found_count': not_found_count,
                'error_count': error_count,
                'total_processed': total_processed
            }
            
        except Exception as e:
            logger.error(f"✗ Update failed: {str(e)}")
            return {
                'success': False,
                'error': str(e)
            }
        finally:
            if connection:
                cursor.close()
                connection.close()
                logger.info("✓ Database connection closed")


def resolve_excel_path() -> str:
    """Resolve path ke Excel file"""
    env_path = os.getenv('EXCEL_PATH')
    if env_path:
        return env_path
    
    current_dir = os.path.dirname(os.path.abspath(__file__))
    return os.path.join(current_dir, 'Dataset_Forecasting_ARIMA_Lengkap.xlsx')


if __name__ == '__main__':
    print("=" * 80)
    print("BUFFER STOCK DATABASE UPDATER")
    print("=" * 80)
    
    excel_path = resolve_excel_path()
    
    try:
        updater = BufferStockDatabaseUpdater(
            excel_path=excel_path,
            avg_lead_time=5.4,
            max_lead_time=7
        )
        
        # Update buffer stock untuk inventory_id = 1
        result = updater.update_buffer_stocks(inventory_id=1)
        
        if result['success']:
            print("\n✅ Buffer stock update BERHASIL!")
            print(f"   Updated: {result['updated_count']} items")
            print(f"   Not found: {result['not_found_count']} items")
            print(f"   Errors: {result['error_count']} items")
        else:
            print(f"\n❌ Buffer stock update GAGAL!")
            print(f"   Error: {result['error']}")
            
    except Exception as e:
        print(f"\n❌ FATAL ERROR: {str(e)}")
        import traceback
        traceback.print_exc()
