<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "googlemaps";
$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$error = '';
$result = null;

if (isset($_POST['namalokasi'])) {
    $nama_lokasi = $_POST['namalokasi'];
    if (empty($nama_lokasi)) {
        echo "<script>alert('Mohon isi nama lokasi terlebih dahulu.')</script>";
    } else {
        $query = "SELECT * FROM kooro WHERE namalokasi LIKE '%$nama_lokasi%'";
        $result = mysqli_query($conn, $query);
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>PLN</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-dFHYjTqEVLndbN2gdvXsx09jfJHmNc8&callback=initMap" async defer></script>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <form method="POST" action="">
        <label>Cari Lokasi:</label>
        <input type="text" name="namalokasi">
        <button type="submit">Cari</button>
    </form>
    <?php if (!empty($error)) : ?>
        <p><?php echo $error; ?></p>
    <?php else : ?>
        <div id="map"></div>
        <div id="cards"></div>
        <script>
            var map;
            var markers = [];
            var cards = document.getElementById('cards');

            function initMap() {
                map = new google.maps.Map(document.getElementById('map'), {
                    center: {
                        lat: 1.3022381506937928,
                        lng: 124.91334778314386
                    },
                    zoom: 13
                });

                <?php if ($result && mysqli_num_rows($result) > 0) : ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        var marker = new google.maps.Marker({
                            position: {
                                lat: <?php echo $row['latitude']; ?>,
                                lng: <?php echo $row['longitude']; ?>
                            },
                            map: map,
                            title: '<?php echo $row['namalokasi']; ?>'
                        });
                        markers.push(marker);

                        var card = document.createElement('div');
                        card.classList.add('card');
                        var cardContent = '<h3><?php echo $row['namalokasi']; ?></h3>' +
                            '<p>ALAMAT   : <?php echo $row['alamat']; ?></p>' +
                            '<p>NO TELP  : <?php echo $row['notelp']; ?></p>' +
                            '<p>ID PEL   : <?php echo $row['idpel']; ?></p>' +
                            '<p>DAYA     : <?php echo $row['daya']; ?></p>' +
                            '<p>RBM      : <?php echo $row['rbm']; ?></p>' +
                            '<p>NO METER : <?php echo $row['nometer']; ?></p>';
                        card.innerHTML = cardContent;
                        cards.appendChild(card);
                    <?php endwhile; ?>

                    if (markers.length > 0) {
                        var bounds = new google.maps.LatLngBounds();
                        for (var i = 0; i < markers.length; i++) {
                            bounds.extend(markers[i].getPosition());
                        }
                        map.fitBounds(bounds);
                    }
                <?php else : ?>
                    var card = document.createElement('div');
                    card.classList.add('card');
                    card.setAttribute('id', 'no-result');
                    var cardContent = '<p>Tidak ditemukan hasil yang sesuai.</p>';
                    card.innerHTML = cardContent;
                    cards.appendChild(card);
                <?php endif; ?>
            }
        </script>
    <?php endif; ?>
</body>

</html>