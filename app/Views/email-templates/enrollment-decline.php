<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Application Status</title>
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
        .decline-notice {
            background: #fff5f5;
            border: 1px solid #fed7d7;
            border-radius: 5px;
            padding: 20px;
            margin: 25px 0;
            border-left: 4px solid #e53e3e;
        }
        .decline-notice h3 {
            color: #e53e3e;
            margin-top: 0;
            font-size: 20px;
            margin-bottom: 15px;
        }
        .enrollment-details {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin: 25px 0;
        }
        .enrollment-details h3 {
            color: #550000;
            margin-top: 0;
            font-size: 18px;
            margin-bottom: 15px;
        }
        .detail-item {
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-item:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: bold;
            color: #550000;
            display: inline-block;
            width: 150px;
        }
        .reason-box {
            background: #fef5e7;
            border: 1px solid #f6ad55;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #ed8936;
        }
        .reason-box h4 {
            color: #c05621;
            margin-top: 0;
            margin-bottom: 10px;
        }
        .reason-text {
            color: #744210;
            font-style: italic;
            line-height: 1.5;
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
        .footer {
            background: #f8f8f8;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #ddd;
            font-size: 14px;
            color: #666;
        }
        .contact-info {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
        .contact-info p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1><?= $schoolName ?></h1>
            <p>Enrollment Application Status</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                Dear <?= htmlspecialchars($studentName) ?>,
            </div>
            
            <div class="decline-notice">
                <h3>Application Status: Declined</h3>
                <p>We regret to inform you that your enrollment application has been declined after careful review.</p>
            </div>
            
            <div class="enrollment-details">
                <h3>Application Details</h3>
                <div class="detail-item">
                    <span class="detail-label">Student Name:</span>
                    <?= htmlspecialchars($studentName) ?>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Enrollment Number:</span>
                    <?= htmlspecialchars($enrollmentNumber) ?>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Application Date:</span>
                    <?= date('F j, Y') ?>
                </div>
            </div>
            
            <div class="reason-box">
                <h4>Reason for Decline:</h4>
                <div class="reason-text">
                    <?= nl2br(htmlspecialchars($reason)) ?>
                </div>
            </div>
            
            <div class="next-steps">
                <h3>What's Next?</h3>
                <p>If you have any questions about this decision or would like to discuss the possibility of reapplying, please don't hesitate to contact our admissions office.</p>
                <p>We appreciate your interest in <?= $schoolName ?> and wish you the best in your educational journey.</p>
            </div>
            
            <p>Thank you for your interest in our school.</p>
            
            <p>Sincerely,<br>
            <strong>Admissions Office</strong><br>
            <?= $schoolName ?></p>
        </div>
        
        <div class="footer">
            <div class="contact-info">
                <p><strong>Contact Information</strong></p>
                <p>Email: admissions@stajustina.edu.ph</p>
                <p>Phone: (123) 456-7890</p>
                <p>Address: Sta. Justina National High School</p>
            </div>
            
            <p style="margin-top: 20px; font-size: 12px; color: #999;">
                This is an automated message. Please do not reply directly to this email.
            </p>
        </div>
    </div>
</body>
</html>