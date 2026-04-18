"""
Update Buffer Stock ke Database
Script untuk menghitung buffer stock dan update ke tabel master_items_stock di database
"""

import pymysql
import os
import json
import re
import logging
from typing import Any, Dict, Optional
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
        mapping_file: Optional[str] = None,
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
            mapping_file: Path file JSON mapping produk Excel -> nama produk database
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
        self.mapping_file = mapping_file or self._resolve_default_mapping_path()
        
        # Get database config dari environment atau default
        self.db_host = db_host or os.getenv('DB_HOST', 'localhost')
        self.db_user = db_user or os.getenv('DB_USERNAME', 'root')
        self.db_password = db_password or os.getenv('DB_PASSWORD', '')
        self.db_name = db_name or os.getenv('DB_DATABASE', 'skripsi_forecasting')
        self.db_port = int(os.getenv('DB_PORT', 3306))

        # Load optional product mapping (Excel name -> DB name)
        self.product_mapping = self._load_product_mapping(self.mapping_file)
        
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

    def _resolve_default_mapping_path(self) -> str:
        """Resolve default path ke file product mapping."""
        current_dir = os.path.dirname(os.path.abspath(__file__))
        return os.path.join(current_dir, 'product_mapping.json')

    def _load_product_mapping(self, mapping_path: str) -> Dict[str, str]:
        """
        Load mapping produk dari JSON.

        Support 2 format:
        1) Flat: {"SKU": "Database Name"}
        2) Structured: {"products": {"SKU": {"mapped_to": "Database Name"}}}
        """
        if not mapping_path or not os.path.exists(mapping_path):
            logger.warning(f"⚠ Mapping file not found, direct name matching only: {mapping_path}")
            return {}

        try:
            with open(mapping_path, 'r', encoding='utf-8') as f:
                raw_mapping = json.load(f)

            mapping: Dict[str, str] = {}

            # Structured format with "products"
            if isinstance(raw_mapping, dict) and isinstance(raw_mapping.get('products'), dict):
                for excel_name, value in raw_mapping['products'].items():
                    if isinstance(value, dict):
                        mapped_to = value.get('mapped_to')
                        if mapped_to:
                            mapping[str(excel_name)] = str(mapped_to)
                    elif isinstance(value, str):
                        mapping[str(excel_name)] = value
            # Flat format
            elif isinstance(raw_mapping, dict):
                for excel_name, value in raw_mapping.items():
                    if isinstance(value, str):
                        mapping[str(excel_name)] = value
                    elif isinstance(value, dict):
                        mapped_to = value.get('mapped_to')
                        if mapped_to:
                            mapping[str(excel_name)] = str(mapped_to)

            logger.info(f"✓ Loaded {len(mapping)} product mappings from: {mapping_path}")
            return mapping
        except Exception as e:
            logger.warning(f"⚠ Failed to load mapping file '{mapping_path}': {str(e)}")
            logger.warning("⚠ Continue with direct product name matching")
            return {}

    def _resolve_item_id(self, product_name: str, items_mapping: Dict[str, int]) -> Optional[int]:
        """Resolve item_id via direct match or mapping file (case-insensitive)."""
        # 1) Direct exact match
        if product_name in items_mapping:
            return items_mapping[product_name]

        # 2) Case-insensitive direct match
        lowered_mapping = {name.lower(): item_id for name, item_id in items_mapping.items()}
        direct_ci = lowered_mapping.get(product_name.lower())
        if direct_ci is not None:
            return direct_ci

        # 3) Mapping file resolution
        mapped_name = self._resolve_mapped_name(product_name)
        if mapped_name:
            if mapped_name in items_mapping:
                return items_mapping[mapped_name]

            mapped_ci = lowered_mapping.get(mapped_name.lower())
            if mapped_ci is not None:
                return mapped_ci

        return None

    def _normalize_code(self, value: str) -> str:
        """Normalize code for comparison: uppercase and keep alnum only."""
        return ''.join(ch for ch in value.upper() if ch.isalnum())

    def _guess_mapping_key_from_sku(self, product_name: str) -> Optional[str]:
        """Guess mapping key variant from SKU-like product code."""
        sku = product_name.strip().upper().replace('_', '-')

        # Example: GB-DS-10 -> DS 10, GB-DS-30 -> DS, GB-TP-CC -> CC
        if sku.startswith('GB-'):
            parts = [p for p in sku.split('-') if p]
            if len(parts) >= 3:
                if parts[1] == 'TP':
                    if parts[2] in {'TV', 'TP'}:
                        return 'TP'
                    return parts[2]

                code = parts[1]
                size = parts[2]
                if size in {'30', '30ML'}:
                    return code
                return f"{code} {size}"
            if len(parts) == 2:
                return parts[1]

        # Example: BB10 -> BB 10
        compact_match = re.match(r'^([A-Z]+)(\d+)$', sku)
        if compact_match:
            return f"{compact_match.group(1)} {compact_match.group(2)}"

        return None

    def _resolve_mapped_name(self, product_name: str) -> Optional[str]:
        """Resolve mapped DB product name from mapping file with SKU heuristics."""
        if not self.product_mapping:
            return None

        # 1) Exact key
        direct = self.product_mapping.get(product_name)
        if direct:
            return direct

        # 2) Case-insensitive key
        mapping_lower = {k.lower(): v for k, v in self.product_mapping.items()}
        direct_ci = mapping_lower.get(product_name.lower())
        if direct_ci:
            return direct_ci

        # 3) Guessed key from SKU pattern
        guessed_key = self._guess_mapping_key_from_sku(product_name)
        if guessed_key:
            guessed = self.product_mapping.get(guessed_key)
            if guessed:
                return guessed
            guessed_ci = mapping_lower.get(guessed_key.lower())
            if guessed_ci:
                return guessed_ci

        # 3b) Additional alias for 30ml variants: GB-XX-30 -> "XX 30"
        sku = product_name.strip().upper().replace('_', '-')
        if sku.startswith('GB-'):
            parts = [p for p in sku.split('-') if p]
            if len(parts) >= 3 and parts[2] in {'30', '30ML'}:
                alt_key = f"{parts[1]} 30"
                alt_direct = self.product_mapping.get(alt_key)
                if alt_direct:
                    return alt_direct
                alt_ci = mapping_lower.get(alt_key.lower())
                if alt_ci:
                    return alt_ci

        # 4) Normalized key comparison (remove separators)
        normalized_input = self._normalize_code(product_name)
        mapping_normalized = {self._normalize_code(k): v for k, v in self.product_mapping.items()}

        # Try full normalized first
        norm_direct = mapping_normalized.get(normalized_input)
        if norm_direct:
            return norm_direct

        # Try normalized of guessed key
        if guessed_key:
            norm_guessed = mapping_normalized.get(self._normalize_code(guessed_key))
            if norm_guessed:
                return norm_guessed

        return None

    def _extract_product_and_buffer(self, buffer_data: Dict[str, Any]) -> Optional[tuple[str, int]]:
        """Extract product name and buffer stock from multiple possible key formats."""
        product_name = buffer_data.get('product_name') or buffer_data.get('Produk')
        raw_buffer = buffer_data.get('buffer_stock')
        if raw_buffer is None:
            raw_buffer = buffer_data.get('Buffer_Stock')

        if product_name is None or raw_buffer is None:
            return None

        try:
            return str(product_name), int(round(float(raw_buffer)))
        except (TypeError, ValueError):
            return None
    
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
    
    def update_buffer_stocks(self, inventory_id: int = 1) -> Dict[str, Any]:
        """
        Update buffer stock di database untuk inventory tertentu
        
        Args:
            inventory_id: ID inventori yang akan di-update (default: 1)
            
        Returns:
            Dict berisi statistics update
        """
        connection = None
        cursor = None
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
                extracted = self._extract_product_and_buffer(buffer_data)
                if extracted is None:
                    logger.warning(f"⚠ Invalid buffer data format, skipped: {buffer_data}")
                    error_count += 1
                    continue

                product_name, buffer_stock = extracted
                
                # Resolve item_id (direct match / case-insensitive / mapping file)
                item_id = self._resolve_item_id(product_name, items_mapping)
                if item_id is None:
                    mapped_name = self._resolve_mapped_name(product_name)
                    if mapped_name:
                        logger.warning(
                            f"⚠ Product not found in database after mapping: {product_name} -> {mapped_name}"
                        )
                    else:
                        logger.warning(f"⚠ Product not found in database (and no mapping): {product_name}")
                    not_found_count += 1
                    continue
                
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
            if cursor:
                cursor.close()
            if connection:
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
    mapping_file = os.getenv('PRODUCT_MAPPING_FILE')
    
    try:
        updater = BufferStockDatabaseUpdater(
            excel_path=excel_path,
            mapping_file=mapping_file,
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
