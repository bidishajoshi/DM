<?php
include 'config.php';

if (!isset($_GET['lat']) || !isset($_GET['lon'])) {
    echo "Location not received.";
    exit;
}

$userLat = $_GET['lat'];
$userLon = $_GET['lon'];

$result = mysqli_query($conn, "SELECT * FROM temples");

$templesWithDistance = [];

/* Distance function */
function distance($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371;

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon/2) * sin($dLon/2);

    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    return $earthRadius * $c;
}

/* Store temples within 10 KM */
while ($row = mysqli_fetch_assoc($result)) {

    $row['distance'] = distance(
        $userLat,
        $userLon,
        $row['latitude'],
        $row['longitude']
    );

    if ($row['distance'] <= 10) {
        $templesWithDistance[] = $row;
    }
}

/* Sort by distance */
usort($templesWithDistance, function ($a, $b) {
    return $a['distance'] <=> $b['distance'];
});

/* ---------- PAGINATION ---------- */
$recordsPerPage = 5;
$totalRecords   = count($templesWithDistance);
$totalPages     = ceil($totalRecords / $recordsPerPage);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
if ($page > $totalPages) $page = $totalPages;

$startIndex = ($page - 1) * $recordsPerPage;
$paginatedTemples = array_slice(
    $templesWithDistance,
    $startIndex,
    $recordsPerPage
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Nearest Temples (≤ 10 KM)</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="nearest-container">
  <h2 class="page-title">🛕 Nearest Temples</h2>
<p class="subtitle">Temples within 10 KM from your location</p><br><br>


    <?php if ($totalRecords > 0) { ?>

        <?php foreach ($paginatedTemples as $temple) { ?>
          <div class="temple-card">
    <div class="temple-info">
        <h3><?php echo $temple['name']; ?></h3>
        <p class="location"><?php echo $temple['location']; ?></p>
    </div>
    <div class="distance-badge">
        <?php echo round($temple['distance'], 2); ?> km
    </div>
</div>

        <?php } ?>

  <div class="pagination">
<style>
    /* ===== GLOBAL ===== */
body {
    margin: 0;
    font-family: "Segoe UI", sans-serif;
    background: linear-gradient(135deg, #ba8ad7, #ba8ad7);
    color: #333;
}

/* ===== CONTAINER ===== */
.nearest-container {
    max-width: 900px;
    margin: 40px auto;
    padding: 25px;
    background: #ffffff;
    border-radius: 14px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

/* ===== TITLE ===== */
.page-title {
    text-align: center;
    color: #6a1b9a;
    margin-bottom: 5px;
}

.subtitle {
    text-align: center;
    color: #777;
    margin-bottom: 25px;
    font-size: 14px;
}

/* ===== TEMPLE CARD ===== */
.temple-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fafafa;
    padding: 18px 20px;
    margin-bottom: 15px;
    border-radius: 12px;
    border-left: 5px solid #6a1b9a;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.temple-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.12);
}

/* ===== INFO ===== */
.temple-info h3 {
    margin: 0;
    color: #4a148c;
    font-size: 18px;
}

.temple-info .location {
    margin-top: 5px;
    font-size: 14px;
    color: #666;
}

/* ===== DISTANCE BADGE ===== */
.distance-badge {
    background: #6a1b9a;
    color: #fff;
    padding: 8px 14px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: bold;
    min-width: 80px;
    text-align: center;
}

/* ===== PAGINATION ===== */
.pagination {
    margin-top: 30px;
    text-align: center;
}

.pagination a {
    display: inline-block;
    padding: 8px 14px;
    margin: 3px;
    border-radius: 8px;
    border: 1px solid #d1c4e9;
    color: #6a1b9a;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.2s ease;
}

.pagination a:hover {
    background: #ede7f6;
}

.pagination a.active {
    background: #6a1b9a;
    color: #fff;
    border-color: #6a1b9a;
}

/* ===== EMPTY STATE ===== */
.nearest-container p {
    text-align: center;
    color: #777;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 600px) {
    .temple-card {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .distance-badge {
        align-self: flex-end;
    }
}

</style>
    <!-- PREVIOUS -->
    <?php if ($page > 1) { ?>
        <a href="?lat=<?php echo $userLat; ?>&lon=<?php echo $userLon; ?>&page=<?php echo $page - 1; ?>">
            « Previous
        </a>
    <?php } ?>

    <!-- PAGE NUMBERS -->
    <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
        <a href="?lat=<?php echo $userLat; ?>&lon=<?php echo $userLon; ?>&page=<?php echo $i; ?>"
           class="<?php echo ($i == $page) ? 'active' : ''; ?>">
            <?php echo $i; ?>
        </a>
    <?php } ?>

    <!-- NEXT -->
    <?php if ($page < $totalPages) { ?>
        <a href="?lat=<?php echo $userLat; ?>&lon=<?php echo $userLon; ?>&page=<?php echo $page + 1; ?>">
            Next »
        </a>
    <?php } ?>

</div>


    <?php } else { ?>
        <p>No temples found within 10 km.</p>
    <?php } ?>
</div>

</body>
</html>
