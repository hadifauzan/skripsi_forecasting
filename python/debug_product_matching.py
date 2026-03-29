"""
Debug Script untuk mendiagnosis masalah product name matching
Menampilkan produk dari Excel dan dari Database untuk dibandingkan
"""

import pymysql
import os
import pandas as pd
from dotenv import load_dotenv
import difflib

# Load environment variables
load_dotenv()

# Database config
DB_HOST = os.getenv('DB_HOST', 'localhost')
DB_USER = os.getenv('DB_USERNAME', 'root')
DB_PASSWORD = os.getenv('DB_PASSWORD', '')
DB_NAME = os.getenv('DB_DATABASE', 'skripsi_forecasting')
DB_PORT = int(os.getenv('DB_PORT', 3306))

# Excel path
EXCEL_PATH = os.getenv('EXCEL_PATH', 'Dataset_Forecasting_ARIMA_Lengkap.xlsx')

print("=" * 100)
print("PRODUCT MATCHING DIAGNOSTIC TOOL")
print("=" * 100)


def get_excel_products():
    """Get daftar produk dari Excel file"""
    try:
        excel = pd.read_excel(EXCEL_PATH)
        # Skip Date dan Total_Sales columns
        products = [col for col in excel.columns if col not in ['Date', 'Total_Sales']]
        return sorted(products)
    except Exception as e:
        print(f"❌ Error membaca Excel: {str(e)}")
        return []


def get_database_items():
    """Get daftar item dari database"""
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
        
        return [(item['item_id'], item['name_item']) for item in items]
    except Exception as e:
        print(f"❌ Error koneksi database: {str(e)}")
        return []


def find_similar_names(excel_products, db_items):
    """Find produk dengan nama yang mirip"""
    db_names = [item[1] for item in db_items]
    
    matches = []
    for excel_prod in excel_products:
        # Cari kecocokan exact
        exact_match = None
        for item_id, db_name in db_items:
            if excel_prod.lower() == db_name.lower():
                exact_match = (item_id, db_name)
                break
        
        if exact_match:
            matches.append({
                'excel_name': excel_prod,
                'db_name': exact_match[1],
                'item_id': exact_match[0],
                'match_type': 'EXACT'
            })
        else:
            # Cari kecocokan mirip
            close_matches = difflib.get_close_matches(excel_prod, db_names, n=1, cutoff=0.6)
            if close_matches:
                for item_id, db_name in db_items:
                    if db_name == close_matches[0]:
                        matches.append({
                            'excel_name': excel_prod,
                            'db_name': db_name,
                            'item_id': item_id,
                            'match_type': 'SIMILAR (60%+)'
                        })
                        break
            else:
                matches.append({
                    'excel_name': excel_prod,
                    'db_name': None,
                    'item_id': None,
                    'match_type': 'NOT FOUND'
                })
    
    return matches


# Run diagnostic
print("\n📊 Loading data...\n")

excel_products = get_excel_products()
db_items = get_database_items()
matches = find_similar_names(excel_products, db_items)

print(f"✓ Found {len(excel_products)} products in Excel")
print(f"✓ Found {len(db_items)} items in Database\n")

# Tampilkan hasil
exact_matches = [m for m in matches if m['match_type'] == 'EXACT']
similar_matches = [m for m in matches if m['match_type'] == 'SIMILAR (60%)']
not_found = [m for m in matches if m['match_type'] == 'NOT FOUND']

print(f"✅ EXACT MATCHES: {len(exact_matches)}")
print(f"⚠️  SIMILAR MATCHES: {len(similar_matches)}")
print(f"❌ NOT FOUND: {len(not_found)}\n")

if not_found:
    print("=" * 100)
    print("❌ PRODUCTS NOT FOUND IN DATABASE (akan di-skip saat update)")
    print("=" * 100)
    for match in not_found:
        print(f"\n  Excel Product: '{match['excel_name']}'")
        # Cari yang paling mirip
        db_names = [item[1] for item in db_items]
        suggestions = difflib.get_close_matches(match['excel_name'], db_names, n=3, cutoff=0.4)
        if suggestions:
            print(f"  Kemungkinan nama di DB yang mirip:")
            for sugg in suggestions:
                print(f"    - '{sugg}'")
        else:
            print(f"  (Tidak ada nama yang mirip)")

if similar_matches:
    print("\n" + "=" * 100)
    print("⚠️  SIMILAR MATCHES (Perlu verifikasi manual)")
    print("=" * 100)
    for match in similar_matches:
        print(f"\n  Excel: '{match['excel_name']}'")
        print(f"  Database: '{match['db_name']}' (ID: {match['item_id']})")
        print(f"  Status: Akan diupdate jika nama cocok")

if exact_matches:
    print("\n" + "=" * 100)
    print(f"✅ EXACT MATCHES ({len(exact_matches)})")
    print("=" * 100)
    for i, match in enumerate(exact_matches[:10], 1):  # Show first 10
        print(f"  {i}. '{match['excel_name']}' → '{match['db_name']}' (ID: {match['item_id']})")
    if len(exact_matches) > 10:
        print(f"  ... dan {len(exact_matches) - 10} lagi")

# Rekomendasi
print("\n" + "=" * 100)
print("💡 REKOMENDASI PERBAIKAN")
print("=" * 100)

if not_found:
    print(f"\n1. Ada {len(not_found)} produk yang tidak ditemukan:")
    print("   Opsi A - Tambahkan produk ke database:")
    print("     - Buka admin panel > Master Items")
    print("     - Tambahkan item baru dengan nama yang cocok Excel\n")
    
    print("   Opsi B - Update nama di Excel untuk cocok dengan database:")
    print("     - Edit file Excel")
    print("     - Ganti nama kolom produk supaya cocok dengan database")
    print("     - Jalankan script ini lagi untuk verifikasi\n")
    
    print("   Opsi C - Rename produk di database untuk cocok dengan Excel:")
    print("     - Buka phpMyAdmin")
    print("     - Edit tabel master_items")
    print("     - Ubah name_item untuk cocok dengan Excel\n")

print("\n2. Setelah menyelesaikan salah satu opsi diatas:")
print("   Jalankan: python update_buffer_stock_db.py\n")

# Tampilkan semua daftar untuk reference
print("=" * 100)
print("📑 DAFTAR LENGKAP - EXCEL PRODUCTS")
print("=" * 100)
for i, prod in enumerate(excel_products, 1):
    status = "✅" if any(m['excel_name'] == prod and m['match_type'] == 'EXACT' for m in matches) else "❌"
    print(f"{status} {i:3}. {prod}")

print("\n" + "=" * 100)
print("📑 DAFTAR LENGKAP - DATABASE ITEMS")
print("=" * 100)
for i, (item_id, name) in enumerate(db_items, 1):
    status = "✅" if any(m['db_name'] == name and m['match_type'] == 'EXACT' for m in matches) else "⚠️"
    print(f"{status} {i:3}. [{item_id}] {name}")
