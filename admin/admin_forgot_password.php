<?php
session_start();
include '../config.php';

$msg = "";

if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if ($new_pass !== $confirm_pass) {
        $msg = "❌ Passwords do not match";
    } else {
        $query = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username'");
        if (mysqli_num_rows($query) == 1) {
            $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE admin SET password='$hashed_pass' WHERE username='$username'");
            $msg = "✅ Password reset successfully. <a href='admin_login.php'>Login now</a>";
        } else {
            $msg = "❌ Username not registered";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Reset Password</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
* { box-sizing: border-box; }
body { margin:0; font-family:"Poppins","Segoe UI",sans-serif; background: linear-gradient(135deg, #667eea, #764ba2); min-height:100vh; display:flex; justify-content:center; align-items:center; }
.form-box { width:420px; background: rgba(255,255,255,0.95); padding:35px 30px; border-radius:16px; box-shadow:0 20px 45px rgba(0,0,0,0.25); animation: slideUp 0.7s ease; }
@keyframes slideUp { from {transform:translateY(40px);opacity:0;} to {transform:translateY(0);opacity:1;} }
.form-box h2 { text-align:center; font-size:26px; color:#333; margin-bottom:8px; }
.subtitle { text-align:center; font-size:13px; color:#777; margin-bottom:22px; }
.form-box input { width:100%; padding:13px; margin-top:12px; border-radius:8px; border:1px solid #ccc; font-size:14px; transition:0.3s; }
.form-box input:focus { outline:none; border-color:#6a5acd; box-shadow:0 0 8px rgba(106,90,205,0.35); }
.show-pass { position: relative; }
.show-pass span { position: absolute; top:50%; right:12px; transform:translateY(-50%); font-size:13px; color:#6a5acd; cursor:pointer; font-weight:600; }
.error { background:#fdecea; color:#e74c3c; padding:8px; border-radius:6px; font-size:13px; text-align:center; margin-bottom:12px; }
.btn { width:100%; background: linear-gradient(135deg,#6a5acd,#836fff); border:none; color:white; padding:13px; margin-top:20px; font-size:16px; border-radius:10px; cursor:pointer; transition:0.4s; }
.btn:hover { transform:translateY(-2px); box-shadow:0 10px 25px rgba(106,90,205,0.5); }
.form-footer { text-align:center; margin-top:18px; font-size:13px; }
.form-footer a { color:#6a5acd; font-weight:600; text-decoration:none; }
.form-footer a:hover { text-decoration:underline; }
</style>
</head>

<body>
<div class="form-box">
    <h2>Admin Reset Password</h2>
    <p class="subtitle">Enter your username and new password</p>

    <?php if($msg!=""){ ?>
        <div class="error"><?php echo $msg; ?></div>
    <?php } ?>

    <form method="post">
        <input type="text" name="username" placeholder="Username" required>

        <div class="show-pass">
            <input type="password" name="new_password" id="new_password" placeholder="New Password" required>
            <span onclick="togglePass()">Show</span>
        </div>

        <div class="show-pass">
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
            <span onclick="togglePassConfirm()">Show</span>
        </div>

        <button class="btn" name="submit">Reset Password</button>

        <div class="form-footer">
            Remember password? <a href="admin_login.php">Login</a>
        </div>
    </form>
</div>

<script>
function togglePass() {
    const pass = document.getElementById("new_password");
    pass.type = pass.type === "password" ? "text" : "password";
}
function togglePassConfirm() {
    const pass = document.getElementById("confirm_password");
    pass.type = pass.type === "password" ? "text" : "password";
}
</script>
</body>
</html>
