<?php
header('Content-type: application/xml');
include "config.php"; // Ganti dengan nama file koneksi database Anda

$query = mysqli_query($conn, "SELECT * FROM iklan"); // Sesuaikan dengan nama tabel iklan yang Anda gunakan.

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
        xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml"
        xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0"
        xmlns:newsmap="http://www.google.com/schemas/sitemap-news/0.9"
        xmlns:pagemap="http://www.google.com/schemas/sitemap-pagemap/1.0"
        xmlns:metadata="http://www.google.com/schemas/sitemap-metadata/1.0">
    <?php
    while ($data = mysqli_fetch_array($query)) {
    ?>
        <url>
            <loc><?php echo htmlspecialchars($url . '/product/' . $data['slug']); ?></loc>
            <lastmod><?php echo date('Y-m-d\TH:i:sP', strtotime($data['waktu'])); ?></lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
            <metadata:metadata>
                <metadata:description><?php echo htmlspecialchars($data['deskripsi']); ?></metadata:description>
                <metadata:price currency="IDR"><?php echo htmlspecialchars($data['harga']); ?></metadata:price>
                <metadata:stock><?php echo htmlspecialchars($data['stok']); ?></metadata:stock>
                <metadata:sold><?php echo htmlspecialchars($data['terjual']); ?></metadata:sold>
                <metadata:location><?php echo htmlspecialchars($data['lokasi']); ?></metadata:location>
                <metadata:weight><?php echo htmlspecialchars($data['berat']); ?></metadata:weight>
            </metadata:metadata>
        </url>
    <?php
    }
    ?>
</urlset>
