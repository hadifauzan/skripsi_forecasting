"""
Buffer Stock Calculation Module
Menghitung buffer stock berdasarkan data penjualan dengan rumus:
Buffer Stock = (Max Daily Sales × Max Lead Time) – (Avg Daily Sales × Avg Lead Time)
"""

import pandas as pd
import numpy as np
from typing import Dict, List, Optional
import os


class BufferStockCalculator:
    """Kelas untuk menghitung buffer stock per produk"""
    
    def __init__(self, excel_path: str, avg_lead_time: float = 5.4, max_lead_time: float = 7):
        """
        Initialize calculator dengan dataset
        
        Args:
            excel_path: Path ke file Excel yang berisi data penjualan
            avg_lead_time: Average lead time dalam hari (default: 5.4)
            max_lead_time: Maximum lead time dalam hari (default: 7)
        """
        self.excel_path = excel_path
        self.avg_lead_time = avg_lead_time
        self.max_lead_time = max_lead_time
        self.dataset = None
        self.product_stats = None
        self.load_and_process_data()
    
    def load_and_process_data(self):
        """Load dan preprocess data dari Excel"""
        try:
            # Load dataset
            self.dataset = pd.read_excel(self.excel_path)
            
            # Convert Date column ke datetime
            self.dataset['Date'] = pd.to_datetime(self.dataset['Date'], errors='coerce')
            
            # Drop rows dengan date invalid
            invalid_dates = self.dataset['Date'].isna().sum()
            if invalid_dates > 0:
                self.dataset = self.dataset.dropna(subset=['Date'])
            
            # Sort by date
            self.dataset = self.dataset.sort_values('Date')
            self.dataset = self.dataset.reset_index(drop=True)
            
            # Compute statistics
            self._compute_statistics()
            
        except Exception as e:
            raise Exception(f"Error loading data: {str(e)}")
    
    def _compute_statistics(self):
        """Compute statistics untuk setiap produk"""
        # Identify product columns (semua kecuali Date dan Total_Sales)
        product_columns = [col for col in self.dataset.columns 
                          if col not in ['Date', 'Total_Sales']]
        
        product_stats = []
        
        for product in product_columns:
            try:
                stats = {
                    'product_name': product,
                    'max_daily_sales': self.dataset[product].quantile(0.95),  # Persentil 95%
                    'avg_daily_sales': self.dataset[product].mean(),
                    'std_dev': self.dataset[product].std(),
                    'min_daily_sales': self.dataset[product].min(),
                    'max_daily_sales_actual': self.dataset[product].max(),
                    'median_daily_sales': self.dataset[product].median()
                }
                
                # Ensure max >= avg
                stats['max_daily_sales'] = max(stats['max_daily_sales'], stats['avg_daily_sales'])
                
                # Calculate buffer stock
                stats['buffer_stock'] = max(0, 
                    (stats['max_daily_sales'] * self.max_lead_time) - 
                    (stats['avg_daily_sales'] * self.avg_lead_time)
                )
                
                # Calculate safety stock (alternative method)
                z_score = 1.65  # 95% service level
                stats['safety_stock'] = z_score * stats['std_dev'] * np.sqrt(self.avg_lead_time)
                
                product_stats.append(stats)
                
            except Exception as e:
                # Skip product jika ada error
                print(f"Warning: Error calculating stats for {product}: {str(e)}")
                continue
        
        self.product_stats = pd.DataFrame(product_stats)
    
    def get_all_buffer_stocks(self) -> List[Dict]:
        """Get buffer stock untuk semua produk"""
        if self.product_stats is None:
            return []
        
        return self.product_stats.to_dict('records')
    
    def get_buffer_stock_by_product(self, product_name: str) -> Optional[Dict]:
        """Get buffer stock untuk produk spesifik"""
        if self.product_stats is None:
            return None
        
        result = self.product_stats[self.product_stats['product_name'] == product_name]
        
        if result.empty:
            return None
        
        return result.iloc[0].to_dict()
    
    def get_summary(self) -> Dict:
        """Get summary dari buffer stock calculation"""
        if self.product_stats is None:
            return {}
        
        return {
            'total_products': len(self.product_stats),
            'total_buffer_stock': self.product_stats['buffer_stock'].sum(),
            'avg_buffer_stock': self.product_stats['buffer_stock'].mean(),
            'total_safety_stock': self.product_stats['safety_stock'].sum(),
            'max_buffer_stock': self.product_stats['buffer_stock'].max(),
            'min_buffer_stock': self.product_stats['buffer_stock'].min(),
            'avg_lead_time': self.avg_lead_time,
            'max_lead_time': self.max_lead_time,
            'calculation_formula': '(Max Daily Sales × Max Lead Time) – (Avg Daily Sales × Avg Lead Time)'
        }
    
    def get_top_products(self, n: int = 10) -> List[Dict]:
        """Get top N products berdasarkan buffer stock"""
        if self.product_stats is None:
            return []
        
        top = self.product_stats.nlargest(n, 'buffer_stock')
        return top.to_dict('records')
    
    def export_to_csv(self, output_path: str):
        """Export hasil calculation ke CSV"""
        if self.product_stats is None:
            raise Exception("No data to export")
        
        export_df = self.product_stats.copy()
        export_df.columns = [
            'Product Name',
            'Max Daily Sales',
            'Avg Daily Sales',
            'Std Dev',
            'Min Daily Sales',
            'Max Daily Sales (Actual)',
            'Median Daily Sales',
            'Buffer Stock',
            'Safety Stock'
        ]
        
        export_df.to_csv(output_path, index=False)
