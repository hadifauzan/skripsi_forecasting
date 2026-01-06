<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Affiliate Aktif</title>
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
            background: linear-gradient(135deg, #28a745 0%, #20833a 100%);
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
        .success-icon {
            text-align: center;
            font-size: 64px;
            margin: 20px 0;
        }
        .info-box {
            background-color: #d1ecf1;
            border-left: 4px solid #0c5460;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .warning-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .button {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #785576 0%, #694966 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .steps {
            background-color: #f8f5f7;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .steps ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        .steps li {
            margin: 10px 0;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Selamat! Akun Anda Telah Aktif</h1>
        </div>
        
        <div class="content">
            <div class="success-icon">✅</div>
            
            <p>Halo <strong>{{ $name }}</strong>,</p>
            
            <p>Kabar baik! Akun affiliate Anda telah <strong>diaktifkan</strong> oleh admin Gentle Living.</p>
            
            <div class="info-box">
                <strong>📧 Informasi Akun:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li><strong>Email:</strong> {{ $email }}</li>
                    <li><strong>Password Default:</strong> password</li>
                    <li><strong>Status:</strong> Aktif</li>
                </ul>
            </div>
            
            <div class="warning-box">
                <strong>🔒 PENTING - Ganti Password Anda!</strong>
                <p style="margin: 10px 0;">
                    Untuk keamanan akun Anda, <strong>segera ganti password default</strong> saat pertama kali login. Password default "password" hanya untuk login pertama kali saja.
                </p>
            </div>
            
            <div class="steps">
                <strong>Langkah Selanjutnya:</strong>
                <ol>
                    <li>Login ke akun Anda menggunakan email dan password default (<code>password</code>)</li>
                    <li>Sistem akan meminta Anda untuk mengubah password</li>
                    <li>Buat password baru yang kuat dan mudah diingat</li>
                    <li>Mulai menggunakan akun affiliate Anda!</li>
                </ol>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="button">Login Sekarang</a>
            </div>
            
            <p style="color: #666; font-size: 14px; margin-top: 30px;">
                Selamat bergabung dengan Gentle Living! Jika ada pertanyaan, jangan ragu untuk menghubungi tim support kami.
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Gentle Living. All rights reserved.</p>
            <p>Email ini dikirim otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
