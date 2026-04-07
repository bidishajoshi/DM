
<link rel="stylesheet" href="../style.css">
<?php
include '../config.php';

if (isset($_POST['add_temple'])) {

    $name = $_POST['name'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    $image = time() . "_" . $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    move_uploaded_file($tmp, "../images/temples/" . $image);

    $query = "INSERT INTO temples 
              (name, location, description, image, latitude, longitude)
              VALUES 
              ('$name', '$location', '$description', '$image', '$latitude', '$longitude')";

    mysqli_query($conn, $query);

    echo "<script>alert('Temple Added Successfully');</script>";
}
?>




<!DOCTYPE html>
<html>
<head>
    <title>Add Temple</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="navbar">
    🛕 Admin Panel – Add Temple
</div>

<div class="form-box">
    <h2>Add New Temple</h2>

   <form method="post" enctype="multipart/form-data">

    <input type="text" name="name" placeholder="Temple Name" required>

    <input type="text" name="location" placeholder="Location" required>

    <textarea name="description" placeholder="Temple Description" required></textarea>

    <input type="number" step="any" name="latitude" placeholder="Latitude (e.g. 27.7104)" required>

    <input type="number" step="any" name="longitude" placeholder="Longitude (e.g. 85.3488)" required>

    <input type="file" name="image" required>

    <button type="submit" name="add_temple">Add Temple</button>

</form>

</div>

</body>
</html>
