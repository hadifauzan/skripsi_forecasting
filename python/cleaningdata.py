import pandas as pd

# List file yang akan diproses
files = [
    "2025 Update Stok GB.xlsx - Jan 25.csv",
    "2025 Update Stok GB.xlsx - Feb25.csv",
    "2025 Update Stok GB.xlsx - Mar25.csv",
    "2025 Update Stok GB.xlsx - Apr25.csv",
    "2025 Update Stok GB.xlsx - Mei25.csv",
    "2025 Update Stok GB.xlsx - Juni25.csv",
    "2025 Update Stok GB.xlsx - Juli 25.csv",
    "2025 Update Stok GB.xlsx - Agustus 25.csv",
    "2025 Update Stok GB.xlsx - September 25.csv",
    "2025 Update Stok GB.xlsx - Oktober 25.csv",
    "2025 Update Stok GB.xlsx - November 25.csv",
    "2025 Update Stok GB.xlsx - Desember 25.csv"
]

all_data = []

for file in files:
    try:
        # Header ada di baris ke-2 (index 1)
        df = pd.read_csv(file, header=1)
        
        # Ambil kolom stok keluar (index 6, 7, 8: Tanggal, Varian, Jumlah)
        if len(df.columns) >= 9:
            df_subset = df.iloc[:, [6, 7, 8]].copy()
            df_subset.columns = ['Date', 'Variant', 'Quantity']
            df_subset = df_subset.dropna(subset=['Date']) 
            all_data.append(df_subset)
    except Exception as e:
        print(f"Error reading {file}: {e}")

# Gabungkan semua data
full_df = pd.concat(all_data, ignore_index=True)

# Fungsi pembersih tanggal
def parse_date(date_str):
    if pd.isna(date_str):
        return pd.NaT
    date_str = str(date_str).strip()
    try:
        return pd.to_datetime(date_str)
    except:
        pass
    try:
        if "/" in date_str:
            parts = date_str.split("/")
            if len(parts) == 2:
                return pd.to_datetime(f"2025-{parts[1]}-{parts[0]}")
            elif len(parts) == 3:
                 return pd.to_datetime(date_str, dayfirst=True)
    except:
        pass
    return pd.NaT

# Terapkan pembersihan
full_df['Date'] = full_df['Date'].apply(parse_date)
full_df = full_df.dropna(subset=['Date'])
full_df['Variant'] = full_df['Variant'].astype(str).str.strip().str.upper()
full_df['Quantity'] = pd.to_numeric(full_df['Quantity'], errors='coerce').fillna(0)

# Agregasi Harian (Long Format)
daily_sales = full_df.groupby(['Date', 'Variant'])['Quantity'].sum().reset_index()
daily_sales = daily_sales.sort_values('Date')

# Buat Pivot Table (Wide Format untuk ARIMA)
pivot_df = daily_sales.pivot(index='Date', columns='Variant', values='Quantity').fillna(0)

# Reindex untuk memastikan semua tanggal ada (01 Jan - 31 Des)
full_idx = pd.date_range(start='2025-01-01', end='2025-12-31', freq='D')
pivot_df = pivot_df.reindex(full_idx, fill_value=0)
pivot_df.index.name = 'Date'

# Tambahkan kolom Total
pivot_df['Total_Sales'] = pivot_df.sum(axis=1)

# Simpan ke Excel dengan nama yang diminta user
output_filename = 'Dataset_Forecasting_ARIMA_Lengkap.xlsx'
with pd.ExcelWriter(output_filename) as writer:
    pivot_df.to_excel(writer, sheet_name='Pivot_Harian_ARIMA')
    daily_sales.to_excel(writer, sheet_name='Data_Mentah_Gabungan', index=False)

print(f"File {output_filename} berhasil dibuat ulang.")