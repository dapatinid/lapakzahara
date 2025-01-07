<?php
include '../config.php';

$page_admin = 'laporan';

if (isset($_COOKIE['login_admin'])) {
    if ($akun_adm == 'false') {
        header("location: " . $url . "system/admin/logout");
    }
} else {
    header("location: " . $url . "admin/login/");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Laporan</title>
    <link rel="icon" href="../../assets/icons/<?php echo $favicon; ?>" type="image/svg">
    <link rel="stylesheet" href="../../assets/css/admin/laporan/index.css">
    <!-- Tambahkan ini di dalam tag <head> untuk mengaktifkan Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../../assets/css/admin/laporan/index.css">
</head>

<body>
    <div class="admin">
<?php include '../partials/menu.php'; ?>        <div class="content_admin">
            <h1 class="title_content_admin">Laporan</h1>
            <div class="isi_content_admin">
                <!-- Box untuk grafik -->
                <div class="box_c_lap_adm">
                    <canvas id="myChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('myChart').getContext('2d');

            var data = {
                labels: ['Hari Ini', 'Bulan Ini', 'Tahun Ini'],
                datasets: [{
                    label: 'Total Transaksi',
                    data: [
                        <?php echo $semua_harga_lap; ?>,
                        <?php echo $bi_semua_harga_lap; ?>,
                        <?php echo $ti_semua_harga_lap; ?>
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                    ],
                    borderWidth: 1
                }]
            };

            var options = {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            };

            var myChart = new Chart(ctx, {
                type: 'bar',
                data: data,
                options: options
            });
        });
    </script>
    <!-- JS -->
</body>

</html>
