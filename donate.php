<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['temple_id'])) {
    echo "Temple not selected";
    exit();
}

$temple_id = $_GET['temple_id'];
$user_id   = $_SESSION['user_id'];

$message = ""; // To store success or error message
$redirect = false; // Flag to redirect

if (isset($_POST['donate'])) {
    $name = mysqli_real_escape_string($conn, $_POST['donor_name']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $payment = mysqli_real_escape_string($conn, $_POST['payment_method']);

    $query = "INSERT INTO donations (user_id, temple_id, donor_name, amount, payment_method)
              VALUES ('$user_id', '$temple_id', '$name', '$amount', '$payment')";

    if (mysqli_query($conn, $query)) {
        $message = "🙏 Thank you for your donation, $name! Redirecting to dashboard...";
        $redirect = true; // Set redirect flag
    } else {
        $message = "❌ Something went wrong. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Donate</title>
    <link rel="stylesheet" href="style.css">
    <?php if ($redirect): ?>
    <script>
        // Redirect after 3 seconds
        setTimeout(function(){
            window.location.href = "dashboard.php";
        }, 3000);
    </script>
    <?php endif; ?>
</head>
<body>

<div class="form-box">
    <h2>Donate to Temple</h2>

    <?php
    if ($message != "") {
        echo "<p class='message'>$message</p>";
    }
    ?>

    <?php if (!$redirect): // Only show form if not redirecting ?>
    <form method="post">
        <input type="hidden" name="temple_id" value="<?php echo $temple_id; ?>">

        <input type="text" name="donor_name" placeholder="Your Name" required>

        <input type="number" name="amount" placeholder="Donation Amount (Rs)" required>

        <select name="payment_method" required>
            <option value="">Select Payment</option>
            <option value="Esewa">Esewa</option>
            <option value="Khalti">Khalti</option>
            <option value="Cash">Cash</option>
        </select>

        <button class="btn" name="donate">Donate Now</button>
    </form>
    <?php endif; ?>
</div>

</body>
</html>
