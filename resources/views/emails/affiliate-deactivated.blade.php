<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Affiliate Dinonaktifkan</title>
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
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
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
        .alert-icon {
            text-align: center;
            font-size: 64px;
            margin: 20px 0;
        }
        .info-box {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .reason-box {
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
        .contact-box {
            background-color: #d1ecf1;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ Pemberitahuan Penting</h1>
        </div>
        
        <div class="content">
            <div class="alert-icon">🔒</div>
            
            <p>Halo <strong>{{ $name }}</strong>,</p>
            
            <p>Kami informasikan bahwa akun affiliate Anda telah <strong>dinonaktifkan</strong> oleh admin Gentle Living.</p>
            
            <div class="info-box">
                <strong>📋 Status Akun:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li><strong>Status:</strong> Nonaktif</li>
                    <li><strong>Akses Login:</strong> Ditangguhkan sementara</li>
                    <li><strong>Tanggal:</strong> {{ date('d F Y, H:i') }} WIB</li>
                </ul>
            </div>
            
            @if($reason)
            <div class="reason-box">
                <strong>📝 Alasan:</strong>
                <p style="margin: 10px 0;">{{ $reason }}</p>
            </div>
            @endif
            
            <p>Dengan status nonaktif, Anda sementara tidak dapat:</p>
            <ul style="margin: 10px 0; padding-left: 20px; color: #666;">
                <li>Login ke sistem</li>
                <li>Mengakses dashboard affiliate</li>
                <li>Melakukan aktivitas affiliate</li>
            </ul>
            
            <div class="contact-box">
                <strong>💬 Perlu Klarifikasi?</strong>
                <p style="margin: 10px 0;">
                    Jika Anda merasa ini adalah kesalahan atau ingin mengajukan pengaktifan kembali, silakan hubungi tim admin kami.
                </p>
                <p style="margin: 10px 0; font-size: 14px;">
                    <strong>Email:</strong> support@gentleliving.id<br>
                    <strong>WhatsApp:</strong> +62 812-3456-7890
                </p>
            </div>
            
            <p style="color: #666; font-size: 14px; margin-top: 30px;">
                Terima kasih atas pengertian Anda. Kami berharap dapat bekerja sama kembali di masa mendatang.
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Gentle Living. All rights reserved.</p>
            <p>Email ini dikirim otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
