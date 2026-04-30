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
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Digital Mandir</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<div class="navbar">
    <h2>🛕 Digital Mandir</h2>
    <div>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <button class="theme-toggle" title="Switch to Dark Mode"></button>
    </div>
</div>

<script src="theme-toggle.js"></script>


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
