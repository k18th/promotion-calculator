خطوات التثبيت:
1) أنشئ قاعدة MySQL من لوحة Hostinger.
2) استورد schema.sql عبر phpMyAdmin.
3) انسخ config.example.php إلى config.php وأدخل بيانات القاعدة.
4) ولّد كلمة مرور الإدارة بأمر PHP: password_hash('YOUR_PASSWORD', PASSWORD_DEFAULT)
5) ضع الناتج في admin_password_hash.
6) ارفع محتويات المجلد إلى public_html الخاص بالدومين.
7) رابط الإدارة: /admin.php

