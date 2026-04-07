<?php
include "../config.php";

if (isset($_POST['add_service'])) {
    $temple_id = $_POST['temple_id'];
    $service_name = $_POST['service_name'];
    $price = $_POST['price'];

    $query = "INSERT INTO services (temple_id, service_name, price)
              VALUES ('$temple_id', '$service_name', '$price')";
    mysqli_query($conn, $query);

    echo "<script>alert('Service Added Successfully');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Services</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="navbar">
    🛕 Admin Panel – Add Services
</div>

<div class="form-box">
    <h2>Add Temple Service</h2>

    <form method="post">

        <!-- Select Temple -->
        <select name="temple_id" required>
            <option value="">Select Temple</option>

            <?php
            $temples = mysqli_query($conn, "SELECT * FROM temples");
            while ($row = mysqli_fetch_assoc($temples)) {
                echo "<option value='{$row['temple_id']}'>
                        {$row['name']}
                      </option>";
            }
            ?>
        </select>

        <input type="text" name="service_name" placeholder="Service Name (e.g. Rudrabhishek)" required>

        <input type="number" name="price" placeholder="Price (Rs.)" required>

        <button type="submit" name="add_service">Add Service</button>

    </form>
</div>

</body>
</html>
