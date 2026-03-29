# 📋 PANDUAN MENGISI PRODUCT MAPPING

## Permasalahan 🔴

Nama produk di Excel menggunakan **KODE SINGKAT**:
- `AERIS10`, `BR45`, `GB-LDR-250`, `TEETH10`, dll

Sedangkan di database menggunakan **NAMA LENGKAP**:
- `Healo Aeris`, `Healo Stiffin`, `Gentle Baby LDR Booster 250ml`, dll

Untuk menyelesaikan ini, kita perlu **memetakan** setiap kode Excel ke nama database yang benar.

---

## Langkah-Langkah 📝

### Step 1: Buka File Mapping
Buka file `product_mapping.json` di VS Code atau text editor pilihan Anda.

Struktur file:
```json
{
  "products": {
    "KODE_EXCEL": {
      "mapped_to": null,           // ← ISI DENGAN NAMA DATABASE
      "item_id": null,             // ← ISI DENGAN ID DARI DATABASE
      "notes": "..."
    }
  }
}
```

---

### Step 2: Matching Kode dengan Nama Database

Gunakan daftar berikut untuk menemukan nama yang cocok:

#### **GENTLE BABY SERIES (GB-***)**

```
Kode Excel          →  Nama Database
─────────────────────────────────────────────────
GB-DS-10            →  Gentle Baby Deep Sleep 10ml       (ID: 1)
GB-DS-30            →  Gentle Baby Deep Sleep 30ml       (ID: 2)
GB-DS-100           →  Gentle Baby Deep Sleep 100ml      (ID: 3)
GB-DS-250           →  Gentle Baby Deep Sleep 250ml      (ID: 4)

GB-JOY-10           →  Gentle Baby Joy 10ml              (ID: 5)
GB-JOY-30           →  Gentle Baby Joy 30ml              (ID: 6)
GB-JOY-100          →  Gentle Baby Joy 100ml             (ID: 7)
GB-JOY-250          →  Gentle Baby Joy 250ml             (ID: 8)

GB-CNF-10           →  Gentle Baby Cough n Flu 10ml      (ID: 9)
GB-CNF-30           →  Gentle Baby Cough n Flu 30ml      (ID: 10)
GB-CNF-100          →  Gentle Baby Cough n Flu 100ml     (ID: 11)
GB-CNF-250          →  Gentle Baby Cough n Flu 250ml     (ID: 12)

GB-BB-10            →  Gentle Baby Bye Bugs 10ml         (ID: 13)
GB-BB-30            →  Gentle Baby Bye Bugs 30ml         (ID: 14)

GB-GF-10            →  Gentle Baby Gimme Food 10ml       (ID: 16)
GB-GF-30            →  Gentle Baby Gimme Food 30ml       (ID: 17)
GB-GF-100           →  Gentle Baby Gimme Food 100ml      (ID: 18)
GB-GF-250           →  Gentle Baby Gimme Food 250ml      (ID: 19)

GB-TC-10            →  Gentle Baby Tummy Calmer 10ml     (ID: 20)
GB-TC-30            →  Gentle Baby Tummy Calmer 30ml     (ID: 21)
GB-TC-100           →  Gentle Baby Tummy Calmer 100ml    (ID: 22)
GB-TC-250           →  Gentle Baby Tummy Calmer 250ml    (ID: 23)

GB-LDR-10           →  Gentle Baby LDR Booster 10ml      (ID: 24)
GB-LDR-30           →  Gentle Baby LDR Booster 30ml      (ID: 25)
GB-LDR-100          →  Gentle Baby LDR Booster 100ml     (ID: 26)
GB-LDR-250          →  Gentle Baby LDR Booster 250ml     (ID: 27)

GB-MYB-10           →  Gentle Baby Massage Your Baby 10ml (ID: 28)
GB-MYB-30           →  Gentle Baby Massage Your Baby 30ml (ID: 29)
GB-MYB-100          →  Gentle Baby Massage Your Baby 100ml (ID: 30)
GB-MYB-250          →  Gentle Baby Massage Your Baby 250ml (ID: 31)

GB-IB-10            →  Gentle Baby Immboost 10ml         (ID: 32)
GB-IB-30            →  Gentle Baby Immboost 30ml         (ID: 33)
GB-IB-100           →  Gentle Baby Immboost 100ml        (ID: 34)
GB-IB-250           →  Gentle Baby Immboost 250ml        (ID: 35)

GB-TP-NB            →  Gentle Twin Pack New Born         (ID: 37)
GB-TP-CC            →  Gentle Twin Pack Common Cold      (ID: 36)
GB-TP-TV            →  Gentle Twin Pack Travel Pack      (ID: 38)
```

#### **HEALO SERIES**
```
AERIS10             →  Healo Aeris                       (ID: 39)
BR45                →  Healo Stiffin                     (ID: 40)  [Asumsi BR = Back Relief]
BR75                →  Healo Stiffin                     (ID: 40)  [atau produk lain?]
EC45                →  Healo Fresh Guard                 (ID: 41)  [Asumsi EC = Extract]
EC75                →  Healo Fresh Guard                 (ID: 41)  [atau produk lain?]
TEETH10             →  Healo Teething                    (ID: 42)
```

---

### Step 3: Edit File product_mapping.json

#### **CONTOH 1: Mapping GB-LDR-250**

SEBELUM (template kosong):
```json
"GB-LDR-250": {
  "mapped_to": null,
  "item_id": null,
  "notes": "Cari nama yang cocok dari database dan isi field ini"
}
```

SESUDAH (sudah di-mapping):
```json
"GB-LDR-250": {
  "mapped_to": "Gentle Baby LDR Booster 250ml",
  "item_id": 27,
  "notes": "LDR = Learner Development Resource"
}
```

#### **CONTOH 2: Mapping AERIS10**
```json
"AERIS10": {
  "mapped_to": "Healo Aeris",
  "item_id": 39,
  "notes": "Healo Starter Pack aromaterapi"
}
```

---

### Step 4: Tips Editing

1. **Gunakan Search & Replace** (Ctrl+H):
   - Find: `"mapped_to": null,`
   - Untuk mempercepat manual edit

2. **Gunakan Ctrl+F** untuk cari kode:
   - Tekan Ctrl+F
   - Ketik kode misalnya `GB-LDR`
   - Akan otomatis jump ke lokasi di file

3. **Copy-Paste Nama**:
   - Jangan ketik manual nama database
   - Copy dari daftar di atas atau dari file mapping output
   - Pastikan spacing dan capitalize benar

4. **Validate JSON**:
   - Setelah edit, VS Code akan highlight error jika syntax salah
   - Pastikan tidak ada comma yang hilang
   - Pastikan string dalam quotes

---

### Step 5: Jalankan Update Script

Setelah selesai mengisi mapping, jalankan:

```bash
cd python
.\.venv\Scripts\python.exe apply_product_mapping.py
```

Output yang diharapkan:
```
✓ Updated 'GB-LDR-250' → 'Gentle Baby LDR Booster 250ml': buffer_stock = 125
✓ Updated 'AERIS10' → 'Healo Aeris': buffer_stock = 89
...

✅ Buffer stock update BERHASIL!
   Updated: 37 items
```

---

## ⚠️ Catatan Penting

1. **Produk yang tidak di-mapping**:
   - Biarkan `"mapped_to": null` jika produk tidak ada di database
   - Script akan skip otomatis tanpa error

2. **Case-Sensitive**:
   - `Gentle Baby` ≠ `gentle baby`
   - Pastikan capitalization cocok persis

3. **Whitespace**:
   - Leading/trailing spaces akan menyebabkan mismatch
   - Copy-paste jangan ada extra spaces

4. **Ambiguous Codes**:
   - Untuk `BR45`, `BR75`, `EC45`, `EC75`: Periksa Excel atau database untuk memastikan produk mana
   - Bisa saja ada produk baru yang tidak ada mapping

---

## 📞 Jika Ada Pertanyaan

Jalankan kembali diagnostic:
```bash
python debug_product_matching.py
```

Script akan menampilkan:
- Mappin yang berhasil (✅)
- Mapping yang mirip tapi tidak exact (⚠️)
- Mapping yang gagal (❌)
