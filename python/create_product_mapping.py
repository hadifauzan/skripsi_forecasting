"""
Script untuk membuat Product Mapping JSON
Menghubungkan kode produk Excel dengan nama item di Database
"""

import pymysql
import os
import json
import pandas as pd
from dotenv import load_dotenv

# Load environment variables
load_dotenv()

# Database config
DB_HOST = os.getenv('DB_HOST', 'localhost')
DB_USER = os.getenv('DB_USERNAME', 'root')
DB_PASSWORD = os.getenv('DB_PASSWORD', '')
DB_NAME = os.getenv('DB_DATABASE', 'skripsi_forecasting')
DB_PORT = int(os.getenv('DB_PORT', 3306))

EXCEL_PATH = os.getenv('EXCEL_PATH', 'Dataset_Forecasting_ARIMA_Lengkap1.xlsx')
MAPPING_FILE = 'product_mapping.json'

print("=" * 100)
print("PRODUCT MAPPING GENERATOR")
print("=" * 100)

# Get Excel products
try:
    excel = pd.read_excel(EXCEL_PATH)
    excel_products = [col for col in excel.columns if col not in ['Date', 'Total_Sales']]
except Exception as e:
    print(f"❌ Error reading Excel: {str(e)}")
    excel_products = []

# Get Database items
try:
    conn = pymysql.connect(
        host=DB_HOST,
        user=DB_USER,
        password=DB_PASSWORD,
        database=DB_NAME,
        port=DB_PORT,
        charset='utf8mb4'
    )
    cursor = conn.cursor(pymysql.cursors.DictCursor)
    cursor.execute("SELECT item_id, name_item FROM master_items WHERE deleted_at IS NULL ORDER BY name_item")
    items = cursor.fetchall()
    cursor.close()
    conn.close()
    db_items = [(item['item_id'], item['name_item']) for item in items]
except Exception as e:
    print(f"❌ Error connecting to database: {str(e)}")
    db_items = []

# Create mapping template
mapping = {
    'generated_from': 'product_mapping_generator.py',
    'excel_file': EXCEL_PATH,
    'instructions': 'Update the "mapped_to" value untuk setiap produk dengan nama_item yang benar dari database',
    'products': {}
}

print(f"\n📊 Found {len(excel_products)} products in Excel")
print(f"📊 Found {len(db_items)} items in Database\n")

# Create template for each Excel product
for excel_prod in excel_products:
    mapping['products'][excel_prod] = {
        'mapped_to': None,  # User harus isi ini dengan nama_item dari database
        'item_id': None,
        'notes': 'Cari nama yang cocok dari database dan isi field ini'
    }

# Save mapping template
try:
    with open(MAPPING_FILE, 'w', encoding='utf-8') as f:
        json.dump(mapping, f, indent=2, ensure_ascii=False)
    print(f"✅ Mapping template created: {MAPPING_FILE}\n")
except Exception as e:
    print(f"❌ Error saving mapping file: {str(e)}\n")

# Display instructions
print("=" * 100)
print("📋 LANGKAH-LANGKAH PENGGUNAAN")
print("=" * 100)

print(f"""
1. File {MAPPING_FILE} sudah dibuat dengan template mapping

2. Edit file {MAPPING_FILE} dan cari produk Excel Anda:
   Misalnya cari "GB-LDR-250" di file

3. Untuk setiap produk, update field "mapped_to" dengan nama item dari database:
   
   CONTOH: Untuk kode "GB-LDR-250", cari di database names:
   ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   Excel Code: "GB-LDR-250"
   
   Dari database, nama yang cocok adalah: "Gentle Baby LDR Booster 250ml"
   
   Jadi di file mapping, update menjadi:
   
   "GB-LDR-250": {{
       "mapped_to": "Gentle Baby LDR Booster 250ml",
       "item_id": 17,
       "notes": "LDR Booster 250ml"
   }}
   ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

4. Setelah selesai update semua produk, jalankan:
   python apply_product_mapping.py

   Script ini akan update buffer stock menggunakan mapping yang sudah dibuat

5. Jika ada produk yang tidak ada di database, biarkan "mapped_to": null
   Script akan skip produk tersebut

════════════════════════════════════════════════════════════════

📝 DAFTAR LENGKAP DATABASE ITEMS (Untuk referensi mapping):
════════════════════════════════════════════════════════════════
""")

for item_id, name in sorted(db_items):
    print(f"  [{item_id:2}] {name}")

print(f"""

════════════════════════════════════════════════════════════════

💡 TIPS:
- Gunakan Ctrl+F di VS Code untuk search/navigate di file mapping
- Copy-paste nama dari daftar database di atas
- Pastikan spelling dan case cocok persis
- Line break atau leading/trailing spaces akan menyebabkan mismatch
""")
