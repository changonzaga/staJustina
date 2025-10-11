<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            background: #550000;
            color: white;
            padding: 24px 20px;
            text-align: center;
        }
        .header .logo {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 6px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 300;
        }
        .content {
            padding: 32px 26px;
        }
        .meta {
            margin: 8px 0 18px 0;
            color: #666;
            font-size: 14px;
        }
        .badge {
            display: inline-block;
            background: #f5f5f5;
            color: #550000;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: bold;
        }
        .announcement-body {
            background: #fafafa;
            border: 1px solid #eee;
            border-radius: 6px;
            padding: 18px;
            color: #333;
        }
        .footer {
            color: #666;
            text-align: center;
            padding: 16px 20px;
            font-size: 12px;
            border-top: 1px solid #eee;
        }
    </style>
    <!--[if mso]>
    <style>
        .announcement-body { border: 1px solid #eeeeee !important; }
    </style>
    <![endif]-->
    </head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">STA. JUSTINA NATIONAL HIGH SCHOOL</div>
            <h1>Announcement</h1>
        </div>
        <div class="content">
            <h2 style="margin:0 0 6px; color:#222; font-weight:600; font-size:20px;"><?= esc($title) ?></h2>
            <div class="meta">
                <span class="badge">Audience: <?= esc($audience) ?></span>
            </div>
            <div class="announcement-body">
                <?= nl2br(esc($content)) ?>
            </div>
            <p style="margin-top:20px; color:#555; font-size:14px;">If you have questions, please contact the school administration.</p>
        </div>
        <div class="footer">
            <p style="margin:0 0 6px;">This is an automated message. Please do not reply to this email.</p>
            <p style="margin:0;">© <?= date('Y') ?> Sta. Justina National High School. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

