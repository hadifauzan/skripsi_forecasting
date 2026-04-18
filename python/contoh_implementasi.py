"""
Contoh Implementasi Inventory Calculator untuk Berbagai Kebutuhan
Demonstrasi praktis integrasi dengan berbagai sistem
"""

from inventory_calculator import InventoryCalculator
import pandas as pd
from datetime import datetime


# ==============================================================================
# CONTOH 1: Analisis Dasar dan Export Hasil
# ==============================================================================

def example_basic_analysis():
    """
    Contoh paling sederhana: jalankan kalkulasi dan simpan hasil
    """
    print("\n" + "="*70)
    print("CONTOH 1: ANALISIS DASAR DAN EXPORT")
    print("="*70 + "\n")
    
    # Inisialisasi dan proses
    calculator = InventoryCalculator('master_items_raw_material.xlsx')
    result_df = calculator.process_all()
    
    # Ekspor hasil
    calculator.export_results('hasil_analisis_basic.xlsx')
    
    # Tampilkan informasi ringkas
    print("Ringkasan Analisis:")
    summary = calculator.get_summary_statistics()
    for key, value in summary.items():
        print(f"  {key}: {value}")
    
    return result_df


# ==============================================================================
# CONTOH 2: Analisis Item Kritis dan Rekomendasi Tindakan
# ==============================================================================

def example_critical_items_analysis():
    """
    Analisis detail item yang memerlukan perhatian khusus
    """
    print("\n" + "="*70)
    print("CONTOH 2: ANALISIS ITEM KRITIS")
    print("="*70 + "\n")
    
    calculator = InventoryCalculator('master_items_raw_material.xlsx')
    result_df = calculator.process_all()
    
    # Item dengan buffer stock yang jauh di bawah rekomendasi
    critical_buffer = result_df[result_df['buffer_stock_variance'] < -20]
    print("Item dengan BUFFER STOCK TERLALU TINGGI (>20 unit):")
    print(f"Total: {len(critical_buffer)} item\n")
    print(critical_buffer[['material_name', 'buffer_stock', 'buffer_stock_proposed', 
                          'buffer_stock_variance']].to_string())
    
    # Item dengan reorder point yang jauh di bawah rekomendasi
    critical_reorder = result_df[result_df['reorder_point_variance'] > 100]
    print("\n\nItem dengan REORDER POINT TERLALU RENDAH (>100 unit):")
    print(f"Total: {len(critical_reorder)} item\n")
    print(critical_reorder[['material_name', 'reorder_point', 'reorder_point_proposed',
                           'reorder_point_variance']].to_string())
    
    # Rekomendasi tindakan
    print("\n\nREKOMENDASI TINDAKAN:")
    print("-" * 70)
    print(f"1. INCREASE: {len(critical_reorder)} item memerlukan kenaikan reorder point")
    print(f"2. DECREASE: {len(critical_buffer)} item dapat dikurangi buffer stocknya")
    print("3. PRIORITAS: Fokus pada item dengan variance tertinggi terlebih dahulu")
    
    return result_df


# ==============================================================================
# CONTOH 3: Analisis per Supplier
# ==============================================================================

def example_supplier_analysis():
    """
    Analisis inventory berdasarkan supplier
    """
    print("\n" + "="*70)
    print("CONTOH 3: ANALISIS PER SUPPLIER")
    print("="*70 + "\n")
    
    calculator = InventoryCalculator('master_items_raw_material.xlsx')
    result_df = calculator.process_all()
    
    # Group by supplier
    supplier_stats = result_df.groupby('supplier_name').agg({
        'item_raw_id': 'count',
        'lead_time_days': 'mean',
        'buffer_stock_variance': ['mean', 'sum'],
        'reorder_point_variance': ['mean', 'sum']
    }).round(2)
    
    supplier_stats.columns = ['Jumlah Item', 'Avg Lead Time', 
                              'Avg Buffer Variance', 'Total Buffer Variance',
                              'Avg Reorder Variance', 'Total Reorder Variance']
    
    print("Statistik per Supplier:")
    print(supplier_stats.to_string())
    
    # Supplier dengan lead time terlama
    print("\n\nSupplier dengan Lead Time Terlama:")
    supplier_leadtime = result_df.groupby('supplier_name')['lead_time_days'].mean().sort_values(ascending=False)
    print(supplier_leadtime.to_string())
    
    # Insight
    print("\n\nINSIGHT:")
    for supplier in result_df['supplier_name'].unique():
        supplier_data = result_df[result_df['supplier_name'] == supplier]
        avg_lead = supplier_data['lead_time_days'].mean()
        items_count = len(supplier_data)
        print(f"  {supplier}: {items_count} item, Avg Lead Time: {avg_lead:.1f} hari")
    
    return result_df


# ==============================================================================
# CONTOH 4: Simulasi Perubahan Lead Time
# ==============================================================================

def example_leadtime_sensitivity():
    """
    Analisis sensitivitas: bagaimana perubahan lead time mempengaruhi inventory
    """
    print("\n" + "="*70)
    print("CONTOH 4: ANALISIS SENSITIVITAS LEAD TIME")
    print("="*70 + "\n")
    
    calculator = InventoryCalculator('master_items_raw_material.xlsx')
    result_df = calculator.process_all()
    
    # Simulasi dengan lead time berbeda
    leadtimes = [0.8, 1.0, 1.2]  # 20% lebih rendah, baseline, 20% lebih tinggi
    
    print("Simulasi Pengaruh Lead Time terhadap Buffer Stock dan Reorder Point")
    print("Contoh: Item 'Botol 10 ml'\n")
    
    sample_item = result_df[result_df['item_raw_id'] == 1].iloc[0]
    base_demand = sample_item['avg_daily_demand']
    
    for lt_factor in leadtimes:
        new_lead = sample_item['lead_time_days'] * lt_factor
        new_buffer = (new_lead * base_demand * 0.25)
        new_reorder = (new_lead * base_demand) + new_buffer
        
        print(f"Lead Time: {new_lead:.1f} hari (factor {lt_factor})")
        print(f"  Buffer Stock: {new_buffer:.0f} unit")
        print(f"  Reorder Point: {new_reorder:.0f} unit")
        print()
    
    # Analisis cost impact
    print("\nIMPLIKASI COST:")
    print("Lead time lebih tinggi -> Buffer stock lebih besar -> Holding cost lebih besar")
    print("Lead time lebih rendah -> Buffer stock lebih kecil -> Risk of stockout lebih besar")
    
    return result_df


# ==============================================================================
# CONTOH 5: Ranking Items berdasarkan Prioritas Inventory
# ==============================================================================

def example_inventory_prioritization():
    """
    Ranking item untuk menentukan prioritas pengelolaan inventory
    """
    print("\n" + "="*70)
    print("CONTOH 5: PRIORITAS PENGELOLAAN INVENTORY")
    print("="*70 + "\n")
    
    calculator = InventoryCalculator('master_items_raw_material.xlsx')
    result_df = calculator.process_all()
    
    # Hitung investment value = current_stock × purchase_price
    result_df['inventory_value'] = result_df['current_stock'] * result_df['purchase_price']
    
    # ABC Analysis
    total_value = result_df['inventory_value'].sum()
    result_df['cumulative_pct'] = (result_df['inventory_value'].sort_values(ascending=False).cumsum() / total_value * 100)
    
    # Assign ABC class
    def assign_abc_class(pct):
        if pct <= 70:
            return 'A'
        elif pct <= 90:
            return 'B'
        else:
            return 'C'
    
    sorted_df = result_df.sort_values('inventory_value', ascending=False)
    sorted_df['abc_class'] = sorted_df['cumulative_pct'].apply(assign_abc_class)
    
    print("Top 10 Item dengan Inventory Value Tertinggi:")
    print(sorted_df[['material_name', 'current_stock', 'purchase_price', 
                     'inventory_value', 'abc_class']].head(10).to_string(index=False))
    
    # Summary ABC
    print("\n\nRingkasan ABC Analysis:")
    abc_summary = sorted_df.groupby('abc_class').agg({
        'item_raw_id': 'count',
        'inventory_value': 'sum'
    }).round(0)
    abc_summary.columns = ['Jumlah Item', 'Total Value']
    print(abc_summary.to_string())
    
    print("\n\nInterpretasi:")
    print("Class A: Item dengan nilai tinggi, perlu monitoring ketat")
    print("Class B: Item dengan nilai medium, monitoring regular")
    print("Class C: Item dengan nilai rendah, monitoring minimal")
    
    return sorted_df


# ==============================================================================
# CONTOH 6: Generate Laporan Rekomendasi Aksi
# ==============================================================================

def example_action_report():
    """
    Membuat laporan rekomendasi aksi untuk management
    """
    print("\n" + "="*70)
    print("CONTOH 6: LAPORAN REKOMENDASI AKSI")
    print("="*70 + "\n")
    
    calculator = InventoryCalculator('master_items_raw_material.xlsx')
    result_df = calculator.process_all()
    
    # Kategorisasi aksi
    increase_reorder = result_df[result_df['reorder_point_variance'] > 50]
    decrease_buffer = result_df[result_df['buffer_stock_variance'] < -10]
    optimize_buffer = result_df[(result_df['buffer_stock_variance'] > 5) & 
                               (result_df['buffer_stock_variance'] <= 20)]
    
    print("RINGKASAN REKOMENDASI AKSI\n")
    print(f"1. INCREASE REORDER POINT: {len(increase_reorder)} item")
    print(f"   Alasan: Reorder point terlalu rendah, risiko stockout tinggi")
    if len(increase_reorder) > 0:
        print("   Item:")
        for idx, row in increase_reorder.head(5).iterrows():
            print(f"     - {row['material_name']}: {row['reorder_point']} -> {row['reorder_point_proposed']}")
        if len(increase_reorder) > 5:
            print(f"     ... dan {len(increase_reorder)-5} item lainnya")
    
    print(f"\n2. OPTIMIZE BUFFER STOCK: {len(optimize_buffer)} item")
    print(f"   Alasan: Buffer stock perlu disesuaikan untuk keseimbangan cost-risk")
    if len(optimize_buffer) > 0:
        print("   Item:")
        for idx, row in optimize_buffer.head(5).iterrows():
            increase_amt = row['buffer_stock_proposed'] - row['buffer_stock']
            print(f"     - {row['material_name']}: +{increase_amt:.0f} unit")
        if len(optimize_buffer) > 5:
            print(f"     ... dan {len(optimize_buffer)-5} item lainnya")
    
    print(f"\n3. REDUCE BUFFER STOCK: {len(decrease_buffer)} item")
    print(f"   Alasan: Inventory berlebih, perlu dikurangi untuk efficiency")
    if len(decrease_buffer) > 0:
        print("   Item:")
        for idx, row in decrease_buffer.head(5).iterrows():
            decrease_amt = row['buffer_stock'] - row['buffer_stock_proposed']
            print(f"     - {row['material_name']}: -{decrease_amt:.0f} unit")
        if len(decrease_buffer) > 5:
            print(f"     ... dan {len(decrease_buffer)-5} item lainnya")
    
    # Estimasi impact
    total_buffer_increase = result_df[result_df['buffer_stock_variance'] > 0]['buffer_stock_variance'].sum()
    total_buffer_decrease = result_df[result_df['buffer_stock_variance'] < 0]['buffer_stock_variance'].sum()
    
    print(f"\n\nESTIMASI IMPACT:")
    print(f"Total kenaikan buffer stock: {total_buffer_increase:.0f} unit")
    print(f"Total penurunan buffer stock: {abs(total_buffer_decrease):.0f} unit")
    print(f"Net change: {(total_buffer_increase + total_buffer_decrease):.0f} unit")
    
    return result_df


# ==============================================================================
# CONTOH 7: Export untuk Integration dengan ERP/Accounting
# ==============================================================================

def example_erp_integration():
    """
    Format export untuk integrasi dengan sistem ERP/Accounting
    """
    print("\n" + "="*70)
    print("CONTOH 7: EXPORT UNTUK ERP/ACCOUNTING INTEGRATION")
    print("="*70 + "\n")
    
    calculator = InventoryCalculator('master_items_raw_material.xlsx')
    result_df = calculator.process_all()
    
    # Format untuk import ke ERP
    erp_data = result_df[['item_raw_id', 'material_name', 'buffer_stock_proposed', 
                          'reorder_point_proposed']].copy()
    erp_data.columns = ['ItemID', 'ItemName', 'SafetyStock', 'ReorderLevel']
    
    # Tambah timestamp
    erp_data['LastUpdated'] = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    
    # Export
    erp_file = f"erp_import_{datetime.now().strftime('%Y%m%d')}.csv"
    erp_data.to_csv(erp_file, index=False)
    
    print(f"File ERP export berhasil dibuat: {erp_file}\n")
    print("Preview data (5 baris pertama):")
    print(erp_data.head().to_string(index=False))
    
    print("\n\nStruktur CSV untuk import:")
    print("  - ItemID: ID item di sistem")
    print("  - ItemName: Nama item")
    print("  - SafetyStock: Buffer stock yang direkomendasikan")
    print("  - ReorderLevel: Reorder point yang direkomendasikan")
    print("  - LastUpdated: Timestamp update")
    
    return erp_data


# ==============================================================================
# MAIN FUNCTION
# ==============================================================================

if __name__ == "__main__":
    print("\n")
    print("╔" + "="*68 + "╗")
    print("║" + " "*68 + "║")
    print("║" + "CONTOH IMPLEMENTASI INVENTORY CALCULATOR".center(68) + "║")
    print("║" + " "*68 + "║")
    print("╚" + "="*68 + "╝")
    
    # Jalankan semua contoh
    try:
        example_basic_analysis()
        example_critical_items_analysis()
        example_supplier_analysis()
        example_leadtime_sensitivity()
        example_inventory_prioritization()
        example_action_report()
        example_erp_integration()
        
        print("\n" + "="*70)
        print("✓ Semua contoh selesai dijalankan!")
        print("="*70 + "\n")
        
    except Exception as e:
        print(f"\nError: {str(e)}")
        print("Pastikan file 'master_items_raw_material.xlsx' ada di folder yang sama")
