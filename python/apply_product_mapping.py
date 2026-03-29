"""
Apply Product Mapping dan Update Buffer Stock ke Database
Menggunakan product_mapping.json untuk matching produk
"""

import pymysql
import os
import json
import logging
from typing import Dict, List
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

class BufferStockDatabaseUpdaterWithMapping:
    """Update buffer stock menggunakan product mapping"""
    
    def __init__(
        self, 
        excel_path: str,
        mapping_file: str = 'product_mapping.json',
        db_host: str = None,
        db_user: str = None,
        db_password: str = None,
        db_name: str = None,
        avg_lead_time: float = 5.4,
        max_lead_time: float = 7
    ):
        self.excel_path = excel_path
        self.mapping_file = mapping_file
        self.avg_lead_time = avg_lead_time
        self.max_lead_time = max_lead_time
        
        # Database config
        self.db_host = db_host or os.getenv('DB_HOST', 'localhost')
        self.db_user = db_user or os.getenv('DB_USERNAME', 'root')
        self.db_password = db_password or os.getenv('DB_PASSWORD', '')
        self.db_name = db_name or os.getenv('DB_DATABASE', 'skripsi_forecasting')
        self.db_port = int(os.getenv('DB_PORT', 3306))
        
        # Load mapping file
        try:
            with open(mapping_file, 'r', encoding='utf-8') as f:
                self.mapping = json.load(f)
            logger.info(f"✓ Loaded mapping from {mapping_file}")
        except FileNotFoundError:
            logger.error(f"✗ Mapping file not found: {mapping_file}")
            raise Exception(f"Mapping file not found: {mapping_file}")
        
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
    
    def get_items_mapping_from_db(self, connection: pymysql.Connection) -> Dict[str, int]:
        """Get mapping dari database: name_item -> item_id"""
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
    
    def apply_mapping_and_update(self, inventory_id: int = 1) -> Dict:
        """Apply mapping dan update buffer stock"""
        connection = None
        try:
            connection = self.connect_to_database()
            
            # Get mapping dari database
            db_items_mapping = self.get_items_mapping_from_db(connection)
            
            # Get all buffer stocks dari calculator
            all_buffer_stocks = self.calculator.get_all_buffer_stocks()
            
            # Statistics
            updated_count = 0
            not_mapped_count = 0
            mapped_but_not_found_count = 0
            error_count = 0
            
            cursor = connection.cursor()
            
            for buffer_data in all_buffer_stocks:
                excel_product = buffer_data['product_name']
                buffer_stock = int(round(buffer_data['buffer_stock']))
                
                # Check if product is in mapping
                if excel_product not in self.mapping['products']:
                    logger.warning(f"⚠ Product not found in mapping file: {excel_product}")
                    not_mapped_count += 1
                    continue
                
                # Get mapped name from mapping file
                mapping_entry = self.mapping['products'][excel_product]
                mapped_name = mapping_entry.get('mapped_to')
                
                if not mapped_name:
                    logger.warning(f"⚠ Product '{excel_product}' not mapped (mapped_to is null)")
                    not_mapped_count += 1
                    continue
                
                # Find item_id from database mapping
                if mapped_name not in db_items_mapping:
                    logger.warning(f"⚠ Mapped name not found in database: '{mapped_name}' (for Excel product '{excel_product}')")
                    mapped_but_not_found_count += 1
                    continue
                
                item_id = db_items_mapping[mapped_name]
                
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
                        logger.info(f"✓ Updated '{excel_product}' → '{mapped_name}': buffer_stock = {buffer_stock}")
                    else:
                        # Insert new jika belum ada
                        insert_query = """
                            INSERT INTO master_items_stock 
                            (item_id, inventory_id, stock, buffer_stock, created_at, updated_at)
                            VALUES (%s, %s, %s, %s, NOW(), NOW())
                        """
                        cursor.execute(insert_query, (item_id, inventory_id, 0, buffer_stock))
                        updated_count += 1
                        logger.info(f"✓ Inserted '{excel_product}' → '{mapped_name}': buffer_stock = {buffer_stock}")
                    
                except pymysql.Error as e:
                    logger.error(f"✗ Error updating '{excel_product}': {str(e)}")
                    error_count += 1
                    continue
            
            # Commit changes
            connection.commit()
            logger.info("✓ Changes committed to database")
            
            # Log summary
            total_processed = updated_count + not_mapped_count + mapped_but_not_found_count + error_count
            logger.info("\n" + "=" * 80)
            logger.info("UPDATE SUMMARY")
            logger.info("=" * 80)
            logger.info(f"Total items processed: {total_processed}")
            logger.info(f"Successfully updated: {updated_count}")
            logger.info(f"Not mapped in mapping file: {not_mapped_count}")
            logger.info(f"Mapped but not found in database: {mapped_but_not_found_count}")
            logger.info(f"Errors occurred: {error_count}")
            logger.info("=" * 80)
            
            return {
                'success': True,
                'updated_count': updated_count,
                'not_mapped_count': not_mapped_count,
                'mapped_but_not_found_count': mapped_but_not_found_count,
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


if __name__ == '__main__':
    print("=" * 80)
    print("BUFFER STOCK UPDATE WITH PRODUCT MAPPING")
    print("=" * 80)
    
    excel_path = os.getenv('EXCEL_PATH', 'Dataset_Forecasting_ARIMA_Lengkap.xlsx')
    mapping_file = 'product_mapping.json'
    
    try:
        updater = BufferStockDatabaseUpdaterWithMapping(
            excel_path=excel_path,
            mapping_file=mapping_file,
            avg_lead_time=5.4,
            max_lead_time=7
        )
        
        result = updater.apply_mapping_and_update(inventory_id=1)
        
        if result['success']:
            print("\n✅ Buffer stock update BERHASIL!")
            print(f"   Updated: {result['updated_count']} items")
            print(f"   Not mapped: {result['not_mapped_count']} items")
            print(f"   Mapped but not found: {result['mapped_but_not_found_count']} items")
            print(f"   Errors: {result['error_count']} items")
        else:
            print(f"\n❌ Buffer stock update GAGAL!")
            print(f"   Error: {result['error']}")
            
    except Exception as e:
        print(f"\n❌ FATAL ERROR: {str(e)}")
        import traceback
        traceback.print_exc()
