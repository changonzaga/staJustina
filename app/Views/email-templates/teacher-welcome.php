<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Sta. Justina National High School System</title>
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
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #550000;
        }
        .credentials {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }
        .credentials h3 {
            color: #550000;
            margin-top: 0;
            font-size: 20px;
            margin-bottom: 20px;
        }
        .credential-item {
            margin: 15px 0;
            font-size: 16px;
        }
        .credential-label {
            font-weight: bold;
            color: #550000;
            display: inline-block;
            width: 140px;
            text-align: left;
        }
        .credential-value {
            background-color: #f5f5f5;
            padding: 8px 12px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            font-weight: normal;
            border: 1px solid #ccc;
            display: inline-block;
            margin-left: 10px;
            color: #333;
            min-width: 180px;
        }
        .login-button {
            background: #550000;
            color: white !important;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin: 20px 0;
            font-size: 14px;
            font-weight: normal;
        }
        .login-button:hover {
            background: #660000;
        }
        .warning {
            border-left: 3px solid #550000;
            padding: 15px 20px;
            margin: 20px 0;
            background-color: #fafafa;
        }
        .warning h4 {
            margin-top: 0;
            color: #550000;
        }
        .getting-started {
            border-left: 3px solid #550000;
            padding: 15px 20px;
            margin: 20px 0;
            background-color: #fafafa;
        }
        .getting-started h4 {
            color: #550000;
            margin-top: 0;
        }
        .getting-started ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .getting-started li {
            margin: 8px 0;
        }
        .footer {
            color: #666;
            text-align: center;
            padding: 20px;
            font-size: 12px;
            border-top: 1px solid #eee;
            margin-top: 30px;
        }
        .footer p {
            margin: 5px 0;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">STA. JUSTINA NATIONAL HIGH SCHOOL</div>
            <h1>Welcome to Our School System!</h1>
            <p>Your Teacher Account Has Been Successfully Created</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                Dear <?= esc($teacherName) ?>,
            </div>
            
            <p>Welcome to the Sta. Justina National High School System! We're excited to have you as part of our educational team. Your teacher account has been successfully created and is ready to use.</p>
            
            <div class="credentials">
                <h3>Your Login Credentials</h3>
                
                <div class="credential-item">
                    <span class="credential-label">Account Number:</span>
                    <span class="credential-value"><?= esc($accountNo) ?></span>
                </div>
                
                <div class="credential-item">
                    <span class="credential-label">Password:</span>
                    <span class="credential-value"><?= esc($password) ?></span>
                </div> 
            </div>
            
            <div style="text-align: center;">
                <a href="<?= $loginUrl ?>" class="login-button">Login to System</a>
            </div>
            
            <div class="warning">
                <h4>Important Security Notice</h4>
                <ul>
                    <li><strong>Change your password</strong> after your first login for security</li>
                    <li><strong>Keep your credentials secure</strong> and do not share them with anyone</li>
                    <li><strong>Contact IT support</strong> if you experience any login issues</li>
                    <li><strong>Use your account number</strong> (not email) to login to the system</li>
                </ul>
            </div>
            
            <div class="getting-started">
                <h4>Getting Started</h4>
                <ul>
                    <li>Complete your profile information in the system</li>
                    <li>Review your assigned classes and subjects</li>
                    <li>Familiarize yourself with the system features</li>
                    <li>Update your contact information if needed</li>
                    <li>Contact the admin for any questions or support</li>
                </ul>
            </div>
            
            <p>If you have any questions or need assistance getting started, please don't hesitate to contact our support team or school administration.</p>
            
            <p>We look forward to working with you!</p>
            
            <p><strong>Best regards,</strong><br>
            <strong>Sta. Justina National High School Administration Team</strong></p>
        </div>
        
        <div class="footer">
            <p>This is an automated message from Sta. Justina National High School System</p>
            <p>Please do not reply to this email.</p>
            <p>© <?= date('Y') ?> Sta. Justina National High School System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>