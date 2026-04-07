<?php
session_start();
include 'config.php';

$msg = "";

// When form is submitted
if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "❌ Invalid email format";
    } elseif ($new_pass != $confirm_pass) {
        $msg = "❌ Passwords do not match";
    } else {
        // Check if email exists
        $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if (mysqli_num_rows($query) == 1) {
            $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);

            mysqli_query($conn, "UPDATE users SET password='$hashed_pass' WHERE email='$email'");
            $msg = "✅ Password reset successfully. <a href='login.php'>Login now</a>";
        } else {
            $msg = "❌ Email not registered";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Password</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
body { margin:0; font-family:"Poppins",sans-serif;
    background: linear-gradient(135deg, #667eea, #764ba2);
    min-height: 100vh; display:flex; justify-content:center; align-items:center; }
.form-box { width: 420px; background: rgba(255,255,255,0.95);
    padding: 35px 30px; border-radius: 16px; box-shadow: 0 20px 45px rgba(0,0,0,0.25); }
.form-box h2 { text-align:center; font-size:26px; color:#333; margin-bottom:8px; }
.subtitle { text-align:center; font-size:13px; color:#777; margin-bottom:22px; }
.form-box input { width:100%; padding:13px; margin-top:12px; border-radius:8px; border:1px solid #ccc; font-size:14px; }
.form-box input:focus { outline:none; border-color:#6a5acd; box-shadow:0 0 8px rgba(106,90,205,0.35); }
.btn { width:100%; background: linear-gradient(135deg,#6a5acd,#836fff); border:none; color:white; padding:13px; margin-top:20px; font-size:16px; border-radius:10px; cursor:pointer; }
.btn:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(106,90,205,0.5); }
.error { background:#fdecea; color:#e74c3c; padding:8px; border-radius:6px; font-size:13px; text-align:center; margin-bottom:12px; }
.form-footer { text-align:center; margin-top:18px; font-size:13px; }
.form-footer a { color:#6a5acd; font-weight:600; text-decoration:none; }
.form-footer a:hover { text-decoration:underline; }
</style>
</head>
<body>

<div class="form-box">
    <h2>Reset Password</h2>
    <p class="subtitle">Enter your email and new password</p>

    <?php if($msg!=""){ ?>
        <div class="error"><?php echo $msg; ?></div>
    <?php } ?>

    <form method="post">
        <input type="email" name="email" placeholder="Registered Email" required>
        <input type="password" name="new_password" placeholder="New Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
        <button class="btn" name="submit">Reset Password</button>

        <div class="form-footer">
            Remember your password? <a href="login.php">Login</a>
        </div>
    </form>
</div>

</body>
</html>
