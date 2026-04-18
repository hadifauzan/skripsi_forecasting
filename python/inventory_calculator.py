import pandas as pd
import numpy as np
from datetime import datetime

class InventoryCalculator:
    def __init__(self, file_path):
        self.df = pd.read_excel(file_path)
        self.results = None
        
    def calculate_avg_daily_demand(self):
        """
        Menghitung rata-rata permintaan harian
        Rumus: (Current Stock / Lead Time Days) untuk estimasi
        """
        self.df['avg_daily_demand'] = self.df['current_stock'] / self.df['lead_time_days']
        return self.df['avg_daily_demand']
    
    def calculate_buffer_stock_proposed(self):
        """
        Menghitung buffer stock yang direkomendasikan
        Rumus: Lead Time Days × Average Daily Demand × 0.25
        (menggunakan safety factor 25% dari demand rata-rata)
        """
        self.df['buffer_stock_proposed'] = (
            self.df['lead_time_days'] * 
            self.df['avg_daily_demand'] * 
            0.25
        ).round(0).astype(int)
        return self.df['buffer_stock_proposed']
    
    def calculate_reorder_point_proposed(self):
        """
        Menghitung reorder point yang direkomendasikan
        Rumus: (Lead Time Days × Average Daily Demand) + Buffer Stock
        """
        self.df['reorder_point_proposed'] = (
            (self.df['lead_time_days'] * self.df['avg_daily_demand']) + 
            self.df['buffer_stock_proposed']
        ).round(0).astype(int)
        return self.df['reorder_point_proposed']
    
    def calculate_variance(self):
        """
        Menghitung selisih antara nilai yang ada dengan nilai yang direkomendasikan
        """
        self.df['buffer_stock_variance'] = (
            self.df['buffer_stock_proposed'] - self.df['buffer_stock']
        )
        self.df['reorder_point_variance'] = (
            self.df['reorder_point_proposed'] - self.df['reorder_point']
        )
        return self.df[['buffer_stock_variance', 'reorder_point_variance']]
    
    def process_all(self):
        """
        Menjalankan semua perhitungan secara berurutan
        """
        self.calculate_avg_daily_demand()
        self.calculate_buffer_stock_proposed()
        self.calculate_reorder_point_proposed()
        self.calculate_variance()
        return self.df
    
    def export_results(self, output_file):
        """
        Mengekspor hasil perhitungan ke file Excel baru
        """
        output_columns = [
            'item_raw_id',
            'material_name',
            'unit',
            'current_stock',
            'lead_time_days',
            'avg_daily_demand',
            'buffer_stock',
            'buffer_stock_proposed',
            'buffer_stock_variance',
            'reorder_point',
            'reorder_point_proposed',
            'reorder_point_variance',
            'supplier_name'
        ]
        
        output_df = self.df[output_columns].copy()
        output_df.to_excel(output_file, index=False, sheet_name='Inventory Analysis')
        print(f"File berhasil disimpan: {output_file}")
        return output_df
    
    def get_summary_statistics(self):
        """
        Menampilkan statistik ringkasan hasil perhitungan
        """
        summary = {
            'Total Items': len(self.df),
            'Avg Daily Demand (Mean)': self.df['avg_daily_demand'].mean().round(2),
            'Buffer Stock Variance (Mean)': self.df['buffer_stock_variance'].mean().round(2),
            'Reorder Point Variance (Mean)': self.df['reorder_point_variance'].mean().round(2),
            'Items with Buffer Stock Below Recommendation': (
                self.df['buffer_stock_variance'] < 0
            ).sum(),
            'Items with Buffer Stock Above Recommendation': (
                self.df['buffer_stock_variance'] > 0
            ).sum(),
        }
        return summary
    
    def get_critical_items(self, threshold=10):
        """
        Mengidentifikasi item yang memerlukan perhatian khusus
        Item dikatakan kritis jika reorder point variance signifikan
        """
        critical = self.df[
            abs(self.df['reorder_point_variance']) > threshold
        ][['material_name', 'reorder_point', 'reorder_point_proposed', 'reorder_point_variance']].copy()
        
        return critical.sort_values('reorder_point_variance', ascending=False)


if __name__ == "__main__":
    try:
        # Inisialisasi calculator dengan file input
        calculator = InventoryCalculator('master_items_raw_material.xlsx')
        
        # Jalankan semua perhitungan
        print("=" * 70)
        print("INVENTORY BUFFER STOCK & REORDER POINT CALCULATOR")
        print("=" * 70)
        print("\nMemproses data...\n")
        
        result_df = calculator.process_all()
        
        # Tampilkan statistik ringkasan
        summary = calculator.get_summary_statistics()
        print("RINGKASAN STATISTIK:")
        print("-" * 70)
        for key, value in summary.items():
            print(f"{key:<50}: {value}")
        
        # Tampilkan item kritis
        print("\n\nITEM YANG MEMERLUKAN PERHATIAN KHUSUS (Variance > 10):")
        print("-" * 70)
        critical_items = calculator.get_critical_items()
        if len(critical_items) > 0:
            print(critical_items.to_string())
        else:
            print("Semua item berada dalam toleransi yang wajar")
        
        # Tampilkan sample data hasil perhitungan
        print("\n\nSAMPLE HASIL PERHITUNGAN (5 Item Pertama):")
        print("-" * 70)
        sample_columns = [
            'material_name', 'lead_time_days', 'current_stock',
            'avg_daily_demand', 'buffer_stock_proposed', 
            'reorder_point_proposed'
        ]
        print(result_df[sample_columns].head().to_string())
        
        # Ekspor hasil ke file Excel
        output_filename = f"inventory_analysis_{datetime.now().strftime('%Y%m%d_%H%M%S')}.xlsx"
        calculator.export_results(output_filename)
        
        print(f"\n\n✓ Proses selesai! File hasil tersimpan: {output_filename}")
        print("=" * 70)
        
    except FileNotFoundError:
        print("Error: File 'master_items_raw_material.xlsx' tidak ditemukan!")
    except Exception as e:
        print(f"Error: {str(e)}")
