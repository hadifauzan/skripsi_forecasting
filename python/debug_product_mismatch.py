"""
Debug Script - Identifikasi Product Name Mismatch
Script untuk mendiagnosis kenapa products dari Excel tidak ditemukan di database
"""

import pandas as pd
import pymysql
import os
from dotenv import load_dotenv
from difflib import SequenceMatcher
import logging

# Load environment variables
load_dotenv()

# Setup logging
logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s | %(levelname)s | %(message)s"
)
logger = logging.getLogger(__name__)


class ProductMismatchDebugger:
    """Debug tool untuk find mismatch antara Excel dan Database products"""
    
    def __init__(self, excel_path: str):
        self.excel_path = excel_path
        self.excel_products = []
        self.db_items = []
        self.db_connection = None
        
        # Get database config
        self.db_host = os.getenv('DB_HOST', 'localhost')
        self.db_user = os.getenv('DB_USERNAME', 'root')
        self.db_password = os.getenv('DB_PASSWORD', '')
        self.db_name = os.getenv('DB_DATABASE', 'skripsi_forecasting')
        self.db_port = int(os.getenv('DB_PORT', 3306))
    
    def load_excel_products(self):
        """Load product names dari Excel"""
        try:
            df = pd.read_excel(self.excel_path)
            
            # Semua kolom kecuali 'Date' dan 'Total_Sales' adalah product names
            self.excel_products = [
                col for col in df.columns 
                if col.lower() not in ['date', 'total_sales']
            ]
            
            logger.info(f"✓ Loaded {len(self.excel_products)} products dari Excel")
            return self.excel_products
            
        except Exception as e:
            logger.error(f"✗ Error loading Excel: {str(e)}")
            return []
    
    def load_database_items(self):
        """Load item names dari database"""
        try:
            connection = pymysql.connect(
                host=self.db_host,
                user=self.db_user,
                password=self.db_password,
                database=self.db_name,
                port=self.db_port
            )
            
            cursor = connection.cursor(pymysql.cursors.DictCursor)
            query = "SELECT item_id, name_item FROM master_items WHERE deleted_at IS NULL ORDER BY name_item"
            cursor.execute(query)
            
            results = cursor.fetchall()
            self.db_items = results
            
            logger.info(f"✓ Loaded {len(self.db_items)} items dari database")
            
            cursor.close()
            connection.close()
            
            return self.db_items
            
        except pymysql.Error as e:
            logger.error(f"✗ Database connection error: {str(e)}")
            return []
    
    def find_matches(self, similarity_threshold: float = 0.8):
        """Find matches dengan similarity calculation"""
        matches = {}
        no_matches = []
        
        db_item_names = [item['name_item'] for item in self.db_items]
        
        for excel_product in self.excel_products:
            best_match = None
            best_score = 0
            
            for db_item_name in db_item_names:
                # Case-insensitive comparison
                score = SequenceMatcher(
                    None, 
                    excel_product.lower(), 
                    db_item_name.lower()
                ).ratio()
                
                if score > best_score:
                    best_score = score
                    best_match = db_item_name
            
            if best_score >= similarity_threshold:
                matches[excel_product] = {
                    'db_name': best_match,
                    'similarity': round(best_score * 100, 2)
                }
            else:
                no_matches.append({
                    'excel_name': excel_product,
                    'closest_match': best_match,
                    'similarity': round(best_score * 100, 2)
                })
        
        return matches, no_matches
    
    def print_report(self):
        """Print debug report"""
        logger.info("\n" + "=" * 100)
        logger.info("PRODUCT NAME MISMATCH REPORT")
        logger.info("=" * 100)
        
        # Load data
        self.load_excel_products()
        self.load_database_items()
        
        if not self.excel_products or not self.db_items:
            logger.error("✗ Failed to load data from Excel or Database")
            return
        
        # Find matches
        matches, no_matches = self.find_matches()
        
        logger.info(f"\n📊 SUMMARY")
        logger.info(f"   Excel Products: {len(self.excel_products)}")
        logger.info(f"   Database Items: {len(self.db_items)}")
        logger.info(f"   Perfect Matches: {len(matches)}")
        logger.info(f"   Mismatches: {len(no_matches)}")
        
        # Print matched products
        if matches:
            logger.info(f"\n✅ MATCHED PRODUCTS ({len(matches)})")
            logger.info("-" * 100)
            for excel_name, match_info in sorted(matches.items()):
                logger.info(f"   Excel: '{excel_name}' → DB: '{match_info['db_name']}' "
                           f"(Similarity: {match_info['similarity']}%)")
        
        # Print unmatched products
        if no_matches:
            logger.info(f"\n❌ UNMATCHED PRODUCTS ({len(no_matches)})")
            logger.info("-" * 100)
            for item in no_matches:
                logger.info(f"   Excel: '{item['excel_name']}'")
                logger.info(f"   Closest match: '{item['closest_match']}' "
                           f"(Similarity: {item['similarity']}%)")
                logger.info("")
        
        # SQL Mapping Template
        logger.info(f"\n📝 SQL MAPPING TEMPLATE")
        logger.info("-" * 100)
        logger.info("# Copy paste ini ke product_mapping.json atau gunakan untuk manual fix\n")
        
        mapping = {}
        for excel_name, match_info in matches.items():
            mapping[excel_name] = match_info['db_name']
        
        import json
        logger.info(json.dumps(mapping, indent=2))
        
        # Suggestions
        logger.info(f"\n💡 SOLUSI YANG DISARANKAN")
        logger.info("-" * 100)
        
        if len(no_matches) == 0:
            logger.info("✅ SEMPURNA! Semua produk Excel ditemukan di database")
        else:
            logger.info(f"Ada {len(no_matches)} produk yang tidak sesuai. Pilih salah satu:")
            logger.info("\n1. OPSI A: Rename di Excel (paling simple)")
            logger.info("   - Ubah nama kolom di Excel sesuai nama di database")
            logger.info("   - Re-run script")
            
            logger.info("\n2. OPSI B: Rename di Database (jika ada database records)")
            logger.info("   - Update master_items.name_item sesuai Excel")
            logger.info("   SQL: UPDATE master_items SET name_item = 'NewName' WHERE item_id = X;")
            
            logger.info("\n3. OPSI C: Pakai Product Mapping File")
            logger.info("   - Buat/update file 'product_mapping.json'")
            logger.info("   - Format: {'ExcelName': 'DatabaseName'}")
            logger.info("   - Modify update_buffer_stock_db.py untuk pakai mapping file")
            
            logger.info("\n4. OPSI D: Manual Match (jika produk hanya beberapa)")
            logger.info("   - Check satu-satu produk Excel vs Database")
            logger.info("   - Lihat closest matches di atas")
        
        logger.info("\n" + "=" * 100)


def main(excel_path: str = None):
    """Main function"""
    # Default Excel path
    if not excel_path:
        current_dir = os.path.dirname(os.path.abspath(__file__))
        excel_path = os.path.join(current_dir, 'Dataset_Forecasting_ARIMA_Lengkap.xlsx')
    
    # Check if file exists
    if not os.path.exists(excel_path):
        logger.error(f"✗ File tidak ditemukan: {excel_path}")
        logger.info("\nGunakan salah satu file Excel yang ada:")
        
        current_dir = os.path.dirname(os.path.abspath(__file__))
        excel_files = [f for f in os.listdir(current_dir) 
                      if f.endswith(('.xlsx', '.xls'))]
        
        for f in excel_files:
            logger.info(f"   - {f}")
        
        return
    
    debugger = ProductMismatchDebugger(excel_path)
    debugger.print_report()


if __name__ == '__main__':
    import sys
    
    excel_file = None
    if len(sys.argv) > 1:
        excel_file = sys.argv[1]
    
    main(excel_file)
