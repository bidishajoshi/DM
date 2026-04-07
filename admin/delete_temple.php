<?php
include '../config.php';

$id = $_GET['id'];

// First delete image (optional but good practice)
$result = mysqli_query($conn, "SELECT image FROM temples WHERE temple_id=$id");
$row = mysqli_fetch_assoc($result);
$image = $row['image'];

if($image != ""){
    unlink("../images/temples/".$image);
}

// Delete record
mysqli_query($conn, "DELETE FROM temples WHERE temple_id=$id");

header("Location: view_temples.php");
exit;
?>
