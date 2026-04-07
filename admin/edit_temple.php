<?php
include '../config.php';

// Get temple ID from URL
if(!isset($_GET['id'])){
    header("Location: view_temples.php");
    exit;
}

$id = $_GET['id'];

// Fetch current temple data
$result = mysqli_query($conn, "SELECT * FROM temples WHERE temple_id=$id");
if(mysqli_num_rows($result) == 0){
    echo "Temple not found!";
    exit;
}
$row = mysqli_fetch_assoc($result);

// Handle form submission
if(isset($_POST['update'])) {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];

    // Handle image upload
    if($_FILES['image']['name'] != "") {
        $new_image = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];

        // Delete old image if exists
        if($row['image'] != "" && file_exists("../images/temples/".$row['image'])){
            unlink("../images/temples/".$row['image']);
        }

        move_uploaded_file($tmp_name, "../images/temples/".$new_image);
    } else {
        $new_image = $row['image']; // keep old image
    }

    // Update record in database
    $update_query = "UPDATE temples 
                     SET name='$name', location='$location', description='$description',
                         latitude='$lat', longitude='$lng', image='$new_image' 
                     WHERE temple_id=$id";
    mysqli_query($conn, $update_query);

    header("Location: view_temples.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Temple</title>
</head>
<body>
<h2>Edit Temple</h2>
<form method="post" enctype="multipart/form-data">
    <label>Temple Name:</label><br>
    <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required><br><br>

    <label>Location:</label><br>
    <input type="text" name="location" value="<?php echo htmlspecialchars($row['location']); ?>" required><br><br>

    <label>Description:</label><br>
    <textarea name="description" rows="4" cols="50" required><?php echo htmlspecialchars($row['description']); ?></textarea><br><br>

    <label>Latitude:</label><br>
    <input type="text" name="lat" value="<?php echo htmlspecialchars($row['latitude']); ?>" required><br><br>

    <label>Longitude:</label><br>
    <input type="text" name="lng" value="<?php echo htmlspecialchars($row['longitude']); ?>" required><br><br>

    <label>Current Image:</label><br>
    <?php if($row['image'] != "") { ?>
        <img src="../images/temples/<?php echo $row['image']; ?>" width="100"><br><br>
    <?php } ?>

    <label>Change Image:</label><br>
    <input type="file" name="image"><br><br>

    <input type="submit" name="update" value="Update Temple">
</form>
<br>
<a href="view_temples.php">Back to Temple List</a>
</body>
</html>
