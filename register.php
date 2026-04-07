<?php
include 'config.php';

$msg = "";

if (isset($_POST['submit'])) {

    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $pass  = $_POST['password'];

    if (!preg_match("/^[A-Za-z ]+$/", $name)) {
        $msg = "❌ Name must contain only letters";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "❌ Invalid email format";
    }
    elseif (!preg_match("/^(97|98)[0-9]{8}$/", $phone)) {
        $msg = "❌ Phone must start with 97 or 98 and be 10 digits";
    }
    elseif (!preg_match("/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W]).{6,}$/", $pass)) {
        $msg = "❌ Weak password";
    }
    else {
        $hashed = password_hash($pass, PASSWORD_DEFAULT);

        $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $msg = "❌ Email already registered";
        } else {
            mysqli_query($conn,
                "INSERT INTO users (name,email,phone,password)
                 VALUES ('$name','$email','$phone','$hashed')");
            $msg = "✅ Registration successful. Please login.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>User Registration</title>
<link rel="stylesheet" href="style.css">

<style>
.error { color:red; font-size:13px; }
.success { color:green; }

/* ================= BASE ================= */
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

/* ================= CARD ================= */
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

/* ================= HEADING ================= */
.form-box h2 {
    text-align: center;
    font-size: 26px;
    color: #333;
    margin-bottom: 5px;
}

.form-box h2 span {
    color: #6a5acd;
}

.subtitle {
    text-align: center;
    font-size: 13px;
    color: #777;
    margin-bottom: 25px;
}

/* ================= INPUTS ================= */
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

/* ================= ERRORS ================= */
.error {
    font-size: 12px;
    color: #e74c3c;
    margin-top: 3px;
}

.success {
    background: #e8f9f0;
    color: #27ae60;
    padding: 8px;
    border-radius: 6px;
    font-size: 14px;
    text-align: center;
    margin-bottom: 15px;
}

/* ================= BUTTON ================= */
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

/* ================= FOOTER ================= */
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

<script>
function validateName() {
    let name = document.getElementById("name").value;
    let msg = document.getElementById("nameErr");
    msg.innerHTML = /^[A-Za-z ]+$/.test(name) ? "" : "Only letters allowed";
}

function validateEmail() {
    let email = document.getElementById("email").value;
    let msg = document.getElementById("emailErr");
    msg.innerHTML =
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email) ? "" : "Invalid email format";
}

function validatePhone() {
    let phone = document.getElementById("phone").value;
    let msg = document.getElementById("phoneErr");
    msg.innerHTML =
        /^(97|98)[0-9]{8}$/.test(phone) ? "" : "Phone must be 97/98 + 8 digits";
}

function validatePassword() {
    let pass = document.getElementById("password").value;
    let msg = document.getElementById("passErr");
    msg.innerHTML =
        /^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W]).{6,}$/.test(pass)
        ? ""
        : "Min 8 chars,  uppercase,lowercase,  number &  symbol";
}
</script>

</head>
<body>

<div class="form-box">
<h2>User Registration</h2>

<p class="<?php echo strpos($msg,'✅')!==false ? 'success':'error'; ?>">
<?php echo $msg; ?>
</p>

<form method="post">

<input type="text" id="name" name="name"
placeholder="Full Name"
onkeyup="validateName()" required>
<div id="nameErr" class="error"></div>

<input type="email" id="email" name="email"
placeholder="Email"
onkeyup="validateEmail()" required>
<div id="emailErr" class="error"></div>

<input type="text" id="phone" name="phone"
placeholder="97XXXXXXXX"
onkeyup="validatePhone()" required>
<div id="phoneErr" class="error"></div>

<input type="password" id="password" name="password"
placeholder="Min 8 chars, Uppercase, Number & Symbol"
onkeyup="validatePassword()" required>

<div id="passErr" class="error"></div>

<button class="btn" name="submit">Register</button><br><br>
<div class="form-footer">
    Already have an account?
    <a href="login.php">Login</a>
</div>

</form>
</div>

</body>
</html>
