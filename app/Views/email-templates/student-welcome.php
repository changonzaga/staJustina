<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Sta. Justina - Student Account</title>
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
            padding: 30px 20px;
            text-align: center;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 300;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #550000;
        }
        .account-details {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin: 25px 0;
        }
        .account-details h3 {
            color: #550000;
            margin-top: 0;
            font-size: 20px;
            margin-bottom: 20px;
        }
        .important {
            background-color: #f5f5f5;
            padding: 8px 12px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            font-weight: bold;
            border: 1px solid #ccc;
            display: inline-block;
            color: #550000;
        }
        .security-notice {
            border-left: 3px solid #550000;
            padding: 15px 20px;
            margin: 20px 0;
            background-color: #fafafa;
        }
        .security-notice h3 {
            color: #550000;
            margin-top: 0;
        }
        .next-steps {
            border-left: 3px solid #550000;
            padding: 15px 20px;
            margin: 20px 0;
            background-color: #fafafa;
        }
        .next-steps h3 {
            color: #550000;
            margin-top: 0;
        }
        .next-steps ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        .next-steps li {
            margin: 8px 0;
        }
        .contact-info {
            border-left: 3px solid #550000;
            padding: 15px 20px;
            margin: 20px 0;
            background-color: #fafafa;
        }
        .contact-info h3 {
            color: #550000;
            margin-top: 0;
        }
        .footer {
            color: #666;
            text-align: center;
            padding: 20px;
            font-size: 12px;
            border-top: 1px solid #eee;
            margin-top: 30px;
        }
        .footer p { margin: 5px 0; }
        .btn {
            background: #550000;
            color: white !important;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin: 10px 0;
            font-size: 14px;
        }
        .btn:hover { background: #660000; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">STA. JUSTINA NATIONAL HIGH SCHOOL</div>
            <h1>Welcome to the Student Portal</h1>
        </div>

        <div class="content">
            <div class="greeting">Dear <?= esc($studentName) ?>,</div>

            <p>Your student account has been created. Please find your login details below:</p>

            <div class="account-details">
                <h3>Account Information</h3>
                <p><strong>Student Account Number:</strong> <?= esc($accountNo) ?></p>
                <p><strong>Temporary Password:</strong> <span class="important"><?= esc($password) ?></span></p>
                <p><strong>Email:</strong> <?= esc($email) ?></p>
            </div>

            <div class="security-notice">
                <h3>🔒 Important Security Notice:</h3>
                <ul>
                    <li>Change your password immediately after your first login.</li>
                    <li>Do not share your login credentials with anyone.</li>
                    <li>Keep this email secure and delete it after changing your password.</li>
                </ul>
            </div>

            <div class="next-steps">
                <h3>📋 Next Steps:</h3>
                <ol>
                    <li>Visit our student portal: <a href="<?= esc($loginUrl) ?>" class="btn">Login to Student Portal</a></li>
                    <li>Log in using your account number and temporary password.</li>
                    <li>Complete your profile and change your password.</li>
                </ol>
            </div>

            <div class="contact-info">
                <h3>📞 Need Help?</h3>
                <p>If you need assistance, contact our office:</p>
                <ul>
                    <li><strong>Email:</strong> admissions@stajustina.edu</li>
                    <li><strong>Phone:</strong> (123) 456-7890</li>
                    <li><strong>Office Hours:</strong> Monday–Friday, 8:00 AM – 5:00 PM</li>
                </ul>
            </div>

            <p>Welcome to Sta. Justina National High School!</p>
            <p>Best regards,<br>
            <strong>Admissions Office</strong><br>
            Sta. Justina National High School</p>
        </div>

        <div class="footer">
            <p>This is an automated message. Please do not reply.</p>
            <p>© <?= date('Y') ?> Sta. Justina National High School. All rights reserved.</p>
        </div>
    </div>
</body>
</html>