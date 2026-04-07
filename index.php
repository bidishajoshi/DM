<?php
include("config.php");

// Pagination settings
$limit = 6; // Number of temples per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Get total number of temples
$totalQuery = "SELECT COUNT(*) AS total FROM temples";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalTemples = $totalRow['total'];
$totalPages = ceil($totalTemples / $limit);

// Fetch temples for the current page
$query = "SELECT * FROM temples ORDER BY temple_id DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Digital Mandir</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <h2>🛕 Digital Mandir</h2>
    <div>
        <a href="register.php">Register</a>
        <a href="login.php">Login</a>
    </div>
</div>

<div class="hero">
    <div>
        <h1>Bring Devotion Online</h1>
        <p>Book temple services anytime, anywhere</p>
    </div>
</div>

<div class="container1">
    <h2>Why Digital Mandir?</h2><br>
    <div class="cards">
        <div class="card">
            <h3>Online Booking</h3>
            <p>Book pujas and offerings easily from home.</p>
        </div>
        <div class="card">
            <h3>Multiple Temples</h3>
            <p>Explore famous temples and their services.</p>
        </div>
        <div class="card">
            <h3>Easy Management</h3>
            <p>Admins can manage temples and bookings.</p>
        </div>
    </div>
</div>

<div style="text-align:center; margin:20px;">
    <div class ="nearest-section">
        <button onclick="findNearest()" class="btn">📍 Find Nearest Temple</button>
    </div>
</div>

<script>
function findNearest() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            let lat = position.coords.latitude;
            let lon = position.coords.longitude;
            window.location.href = "nearest.php?lat=" + lat + "&lon=" + lon;
        });
    } else {
        alert("Geolocation not supported");
    }
}
</script>

<div class="container">
<?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
?>
    <div class="temple-card">
        <img src="images/temples/<?php echo $row['image']; ?>" alt="Temple Image">
        <h2><?php echo $row['name']; ?></h2>
        <p class="location"><?php echo $row['location']; ?></p>
        <p><?php echo $row['description']; ?></p>
        <a href="booking.php?temple_id=<?php echo $row['temple_id']; ?>" class="btn">Book Now</a>
    </div>
<?php
    }
} else {
    echo "<p>No temples added yet.</p>";
}
?>
</div>

<!-- Pagination links -->
 <style>
 .pagination {
    text-align: center;
    margin: 30px 0;
}

.page-btn {
    display: inline-block;
    margin: 0 5px;
    padding: 8px 12px;
    background-color: #6a1b9a; /* Button color */
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: 0.3s;
}

.page-btn:hover {
    background-color: #72458e; /* Darker on hover */
}

.page-btn.current {
    background-color: #555; /* Highlight current page */
    cursor: default;
}
</style>

<div class="pagination">
<?php
if ($totalPages > 1) {
    if ($page > 1) {
        echo '<a class="page-btn" href="?page=' . ($page - 1) . '">&laquo; Prev</a>';
    }

    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == $page) {
            echo '<span class="page-btn current">' . $i . '</span>';
        } else {
            echo '<a class="page-btn" href="?page=' . $i . '">' . $i . '</a>';
        }
    }

    if ($page < $totalPages) {
        echo '<a class="page-btn" href="?page=' . ($page + 1) . '">Next &raquo;</a>';
    }
}
?>
</div>


<div class="footer">
    © 2025 Digital Mandir | Final Year BCA Project
</div>

</body>
</html>
