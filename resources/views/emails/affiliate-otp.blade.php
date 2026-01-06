<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi OTP</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #785576 0%, #694966 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 40px 30px;
        }
        .otp-box {
            background-color: #f8f5f7;
            border: 2px dashed #785576;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-code {
            font-size: 48px;
            font-weight: bold;
            color: #785576;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
        }
        .otp-label {
            font-size: 14px;
            color: #666;
            margin-top: 10px;
        }
        .info-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #785576 0%, #694966 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Verifikasi Email Anda</h1>
        </div>
        
        <div class="content">
            <p>Halo <strong>{{ $name }}</strong>,</p>
            
            <p>Terima kasih telah mendaftar sebagai Affiliate Gentle Living! Untuk melanjutkan proses pendaftaran, silakan verifikasi email Anda dengan memasukkan kode OTP berikut:</p>
            
            <div class="otp-box">
                <div class="otp-code">{{ $otp }}</div>
                <div class="otp-label">Kode Verifikasi OTP</div>
            </div>
            
            <div class="info-box">
                <strong>⚠️ Penting:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Kode ini berlaku selama <strong>{{ $expiresInMinutes }} menit</strong></li>
                    <li>Jangan bagikan kode ini kepada siapapun</li>
                    <li>Jika Anda tidak melakukan pendaftaran, abaikan email ini</li>
                </ul>
            </div>
            
            <div class="info-box" style="background-color: #d1ecf1; border-left: 4px solid #0c5460;">
                <strong>💡 Tips:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li><strong>Halaman verifikasi tertutup?</strong> Jangan khawatir!</li>
                    <li>Kunjungi: <a href="{{ url('/affiliate/request-verification') }}" style="color: #0c5460;">{{ url('/affiliate/request-verification') }}</a></li>
                    <li>Masukkan email Anda untuk mendapatkan akses ke halaman verifikasi</li>
                    <li>Atau klik tombol di bawah ini:</li>
                </ul>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ url('/affiliate/request-verification') }}" class="button">
                    Akses Halaman Verifikasi
                </a>
            </div>
            
            <p style="color: #666; font-size: 14px; margin-top: 30px;">
                Jika Anda mengalami kesulitan, silakan hubungi tim support kami.
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Gentle Living. All rights reserved.</p>
            <p>Email ini dikirim otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
