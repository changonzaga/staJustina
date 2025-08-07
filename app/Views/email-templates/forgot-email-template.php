<p>Dear <?= $mail_data['user']->name ?></p>
<p>
    We received a request to reset your password for EduConnect account associated with<i> <?= $mail_data['user']->email ?></i>. 
    You can reset your password by clicking the link below.
    <br><br>
    <a href="<?= $mail_data['actionLink'] ?>" style="color:#fff;border-color:#22bc66;border-style:solid;border-width:5px 10px;background-color:#22bc66;display:inline-block;text-decoration:none;border-radius:5px;box-shadow:0 2px 3px rgba(0,0,0,0.16);
    -webkit-text-size-adjust:none;box-sizing:border-box; " target="_blank">Reset Password</a>
    <br><br>
    <b>NB:</b> This Link will expire in 24 hours. If you have not yet requested a password reset, you can ignore this email.
    <br><br>
    If you have any questions, please contact us at Sta. Justina National High School. <a href="https://www.facebook.com/share/1EAV21afco/" target="_blank">Facebook Page</a>
    <br><br>
    <p>Thank you.</p>
</p>