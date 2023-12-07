<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "googlemaps";
$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if(isset($_POST['namalokasi'])) {
    $nama_lokasi = $_POST['namalokasi'];
    $query = "SELECT * FROM koor WHERE namalokasi LIKE '%$nama_lokasi%'";
    $result = mysqli_query($conn, $query);
} else {
    $result = null;
}

echo "<script>";
echo "var map;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 1.3022381506937928, lng: 124.91334778314386},
          zoom: 13
        });";

while ($result && $row = mysqli_fetch_assoc($result)) {
    echo "var marker = new google.maps.Marker({
            position: {lat: " . $row['latitude'] . ", lng: " . $row['longitude'] . "},
            map: map,
            title: '" . $row['namalokasi'] . "'
          });";
    echo "map.setCenter({lat: " . $row['latitude'] . ", lng: " . $row['longitude'] . "});
          map.setZoom(15);";
}

echo "}
      </script>";
?>

<!DOCTYPE html>
<html>
<head>
    <title>PLN</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-dFHYjTqEVLndbN2gdvXsx09jfJHmNc8&callback=initMap" async defer></script>
</head>
<body>
    <form method="POST" action="">
        <label>Cari Lokasi:</label>
        <input type="text" name="namalokasi">
        <button type="submit">Cari</button>
    </form>
    <div id="map" style="height: 500px;"></div>
</body>
</html>