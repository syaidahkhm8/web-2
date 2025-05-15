<?php
include 'config/database.php';

$kartu_labels = [];
$kartu_data = [];
$res = mysqli_query($conn, "
    SELECT k.nama AS nama_kartu, COUNT(a.id) AS total
    FROM kartu_diskon k
    LEFT JOIN anggota a ON a.kartu_diskon_id = k.id
    GROUP BY k.id
    ORDER BY k.nama
");
while ($row = mysqli_fetch_assoc($res)) {
    $kartu_labels[] = $row['nama_kartu'];
    $kartu_data[] = $row['total'];
}
header('Content-Type: application/json');
echo json_encode([
    'labels' => $kartu_labels,
    'data' => $kartu_data
]);
?>