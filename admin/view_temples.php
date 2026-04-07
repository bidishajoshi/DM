<?php
include '../config.php';

/* Pagination setup */
$limit = 5; // temples per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

/* Fetch temples with limit */
$result = mysqli_query(
    $conn,
    "SELECT * FROM temples LIMIT $limit OFFSET $offset"
);

/* Total records */
$totalResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM temples");
$totalRow = mysqli_fetch_assoc($totalResult);
$totalRecords = $totalRow['total'];

$totalPages = ceil($totalRecords / $limit);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Temples</title>
    <link rel="stylesheet" href="../css/style.css">

    <style>
        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background: #6a1b9a;
            color: white;
        }
        img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }

        .btn {
            padding: 6px 12px;
            text-decoration: none;
            color: white;
            border-radius: 4px;
            font-size: 14px;
            margin: 2px;
            display: inline-block;
        }
        .edit-btn {
            background: #1976d2;
        }
        .delete-btn {
            background: #d32f2f;
        }

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
</head>
<body>

<div class="navbar">Admin – View Temples</div>

<div class="container">
<table>
<tr>
    <th>S.N.</th>
    <th>Temple Name</th>
    <th>Location</th>
    <th>Image</th>
    <th>Action</th>
</tr>

<?php
$sn = $offset + 1;
while($row = mysqli_fetch_assoc($result)){
?>
<tr>
    <td><?php echo $sn++; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['location']; ?></td>
    <td>
        <img src="../images/temples/<?php echo $row['image']; ?>">
    </td>
    <td>
        <a class="btn edit-btn" href="edit_temple.php?id=<?php echo $row['temple_id']; ?>">
            Edit
        </a>
        <a class="btn delete-btn"
           href="delete_temple.php?id=<?php echo $row['temple_id']; ?>"
           onclick="return confirm('Are you sure you want to delete this temple?')">
            Delete
        </a>
    </td>
</tr>
<?php } ?>
</table>

<!-- Pagination -->
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

</div>

</body>
</html>
