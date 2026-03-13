import pandas as pd

# 1. Tentukan nama file Excel tunggal
file_path = '2025 Update Stok GB.xlsx'

try:
    # Membaca semua sheet sekaligus. 
    # header=1 berarti baris ke-2 dijadikan header (sesuai format file Anda)
    all_sheets = pd.read_excel(file_path, sheet_name=None, header=1)
    
    all_data = []

    # 2. Loop melalui setiap sheet
    for sheet_name, df in all_sheets.items():
        print(f"Memproses sheet: {sheet_name}")
        
        # Lewati sheet template/kosong jika ada (misal 'File Asli')
        if 'File Asli' in sheet_name:
            continue
            
        # Pastikan kolom cukup (minimal sampai index 8 untuk data Stok Keluar)
        if len(df.columns) >= 9:
            # Ambil kolom index 6, 7, 8 (Tanggal, Varian, Jumlah pada bagian 'Stok Keluar')
            df_subset = df.iloc[:, [6, 7, 8]].copy()
            df_subset.columns = ['Date', 'Variant', 'Quantity']
            
            # Hapus baris yang tanggalnya kosong
            df_subset = df_subset.dropna(subset=['Date'])
            
            # Masukkan ke list penampung
            all_data.append(df_subset)

    # 3. Gabungkan semua data dari berbagai sheet
    full_df = pd.concat(all_data, ignore_index=True)

    # 4. Pembersihan Data (Cleaning)
    # Fungsi parsing tanggal yang fleksibel
    def parse_date(date_val):
        if pd.isna(date_val): return pd.NaT
        date_str = str(date_val).strip()
        try:
            return pd.to_datetime(date_str)
        except:
            pass
        # Coba format DD/MM (asumsi tahun 2025)
        try:
            if "/" in date_str:
                parts = date_str.split("/")
                if len(parts) == 2:
                    return pd.to_datetime(f"2025-{parts[1]}-{parts[0]}")
        except:
            pass
        return pd.NaT

    full_df['Date'] = full_df['Date'].apply(parse_date)
    full_df = full_df.dropna(subset=['Date'])
    
    # Standarisasi nama varian (huruf besar, hapus spasi)
    full_df['Variant'] = full_df['Variant'].astype(str).str.strip().str.upper()
    
    # Pastikan quantity angka
    full_df['Quantity'] = pd.to_numeric(full_df['Quantity'], errors='coerce').fillna(0)

    # 5. Agregasi Harian (Menjumlahkan penjualan per varian per hari)
    daily_sales = full_df.groupby(['Date', 'Variant'])['Quantity'].sum().reset_index()

    # 6. Buat Pivot Table untuk ARIMA
    pivot_df = daily_sales.pivot(index='Date', columns='Variant', values='Quantity').fillna(0)

    # Reindex agar tanggal lengkap setahun (01 Jan - 31 Des)
    full_idx = pd.date_range(start='2025-01-01', end='2025-12-31', freq='D')
    pivot_df = pivot_df.reindex(full_idx, fill_value=0)
    pivot_df.index.name = 'Date'

    # Tambah kolom Total Sales
    pivot_df['Total_Sales'] = pivot_df.sum(axis=1)

    # 7. Simpan Hasil
    output_filename = 'Dataset_Forecasting_ARIMA_Lengkap.xlsx'
    with pd.ExcelWriter(output_filename) as writer:
        pivot_df.to_excel(writer, sheet_name='Pivot_Harian_ARIMA')
        daily_sales.to_excel(writer, sheet_name='Data_Mentah_Gabungan', index=False)

    print(f"Berhasil! File '{output_filename}' telah dibuat.")
    print(pivot_df.head())

except Exception as e:
    print(f"Terjadi kesalahan: {e}")