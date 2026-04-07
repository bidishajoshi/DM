<?php
session_start();
include 'config.php';

$msg = "";

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "❌ Invalid email format";
    } else {

        $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

        if (mysqli_num_rows($query) == 1) {
            $user = mysqli_fetch_assoc($query);

            if (password_verify($pass, $user['password'])) {

                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user']    = $user['name'];

                header("Location: dashboard.php");
                exit();
            } else {
                $msg = "❌ Incorrect password";
            }
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
<title>User Login</title>

<!-- Same font + theme as Registration -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
/* ===== SAME BASE AS REGISTRATION ===== */
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: "Poppins", "Segoe UI", sans-serif;
    background: linear-gradient(135deg, #667eea, #764ba2);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* ===== CARD ===== */
.form-box {
    width: 420px;
    background: rgba(255,255,255,0.95);
    padding: 35px 30px;
    border-radius: 16px;
    box-shadow: 0 20px 45px rgba(0,0,0,0.25);
    animation: slideUp 0.7s ease;
}

@keyframes slideUp {
    from { transform: translateY(40px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* ===== HEADING ===== */
.form-box h2 {
    text-align: center;
    font-size: 26px;
    color: #333;
    margin-bottom: 8px;
}

.subtitle {
    text-align: center;
    font-size: 13px;
    color: #777;
    margin-bottom: 22px;
}

/* ===== INPUTS ===== */
.form-box input {
    width: 100%;
    padding: 13px;
    margin-top: 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
    transition: 0.3s;
}

.form-box input:focus {
    outline: none;
    border-color: #6a5acd;
    box-shadow: 0 0 8px rgba(106,90,205,0.35);
}

/* ===== SHOW PASSWORD ===== */
.show-pass {
    position: relative;
}

.show-pass span {
    position: absolute;
    top: 50%;
    right: 12px;
    transform: translateY(-50%);
    font-size: 13px;
    color: #6a5acd;
    cursor: pointer;
    font-weight: 600;
}

/* ===== MESSAGE ===== */
.error {
    background: #fdecea;
    color: #e74c3c;
    padding: 8px;
    border-radius: 6px;
    font-size: 13px;
    text-align: center;
    margin-bottom: 12px;
}

/* ===== BUTTON ===== */
.btn {
    width: 100%;
    background: linear-gradient(135deg, #6a5acd, #836fff);
    border: none;
    color: white;
    padding: 13px;
    margin-top: 20px;
    font-size: 16px;
    border-radius: 10px;
    cursor: pointer;
    transition: 0.4s;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(106,90,205,0.5);
}

/* ===== FOOTER ===== */
.form-footer {
    text-align: center;
    margin-top: 18px;
    font-size: 13px;
}

.form-footer a {
    color: #6a5acd;
    font-weight: 600;
    text-decoration: none;
}

.form-footer a:hover {
    text-decoration: underline;
}
</style>
</head>

<body>

<div class="form-box">
    <h2>User Login</h2>
    <p class="subtitle">Welcome back, please login</p>

    <?php if($msg!=""){ ?>
        <div class="error"><?php echo $msg; ?></div>
    <?php } ?>

    <form method="post">
        <input type="email" name="email" placeholder="Email" required>

        <div class="show-pass">
            <input type="password" name="password" id="password" placeholder="Password" required>
            
        </div>

        <button class="btn" name="login">Login</button>

        <div class="form-footer">
            Don’t have an account?
            <a href="register.php">Register</a><br><br>
            <a href="forgot_password.php">Forgot Password?</a>
        </div>
    </form>
</div>

<script>
function togglePass() {
    const pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
