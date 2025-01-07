<?php
include '../../../config.php';

$nama_toko_edt = mysqli_real_escape_string($server, $_POST['nama_toko_edt']);
$level_toko_edt = mysqli_real_escape_string($server, $_POST['level_toko_edt']);
$nama_pengguna_edt = mysqli_real_escape_string($server, $_POST['nama_pengguna_edt']);
$no_wa_edt = mysqli_real_escape_string($server, $_POST['no_wa_edt']);
$verifikasi_toko_edt = mysqli_real_escape_string($server, $_POST['verifikasi_toko_edt']);
$id_user_edit_akun = mysqli_real_escape_string($server, $_POST['id_user_edit_akun']);
$jumlah_deposit_edt = str_replace('.', '', $_POST['jumlah_deposit_edt']);

// Update kolom 'nama_lengkap', 'email', 'no_whatsapp', 'tipe_akun' di tabel 'akun'
$edit_akun = $server->query("UPDATE `akun` SET 
                                `nama_toko`='$nama_toko_edt', 
                                `verifikasi_toko`='$verifikasi_toko_edt', 
                                `nama_pengguna`='$nama_pengguna_edt', 
                                `no_whatsapp`='$no_wa_edt', 
                                `level_toko`='$level_toko_edt' 
                            WHERE `id`='$id_user_edit_akun'");

if (!$edit_akun) {
    echo "Terjadi kesalahan. Gagal memperbarui akun.";
    exit;
}

// Cek apakah $jumlah_deposit_edt berubah dan tidak kosong atau 0
if ($jumlah_deposit_edt !== '' && $jumlah_deposit_edt != 0) {
    $result = $server->query("SELECT `jumlah_deposit` FROM `riwayat_deposit` WHERE `user_id`='$id_user_edit_akun' ORDER BY `tanggal` DESC LIMIT 1");
    $row = $result->fetch_assoc();
    $jumlah_deposit_terakhir = $row['jumlah_deposit'];

    if ($jumlah_deposit_edt != $jumlah_deposit_terakhir) {
        // Nilai default untuk kolom lainnya
        $tanggal = date('Y-m-d H:i:s');
        $nama_bank = "BANK REKSEL";  // Ganti dengan nilai default Anda
        $rekening_asal = "1234567890";  // Ganti dengan nilai default Anda
        $atas_nama = "REKSEL.COM";  // Ganti dengan nilai default Anda
        $status = 0;  // Ganti dengan nilai default Anda
        $keterangan = "Bonus dari Administator";  // Ganti dengan nilai default Anda

        // Lakukan insert ke dalam tabel 'riwayat_deposit'
        $insert_saldo = $server->query("INSERT INTO `riwayat_deposit` 
                                        (`tanggal`, `jumlah_deposit`, `nama_bank`, `rekening_asal`, `atas_nama`, `status`, `user_id`, `keterangan`) 
                                        VALUES 
                                        ('$tanggal', '$jumlah_deposit_edt', '$nama_bank', '$rekening_asal', '$atas_nama', '$status', '$id_user_edit_akun', '$keterangan')");

        if ($insert_saldo) {
            // Update atau insert ke tabel 'saldo'
            $saldo_result = $server->query("SELECT * FROM `saldo` WHERE `user_id`='$id_user_edit_akun'");
            if ($saldo_result->num_rows > 0) {
                // Jika user_id sudah ada, lakukan update
                $server->query("UPDATE `saldo` SET 
                                    `jumlah_saldo` = `jumlah_saldo` + '$jumlah_deposit_edt', 
                                    `deposit` = `deposit` + '$jumlah_deposit_edt'
                                WHERE `user_id`='$id_user_edit_akun'");
            } else {
                // Jika user_id belum ada, lakukan insert
                $server->query("INSERT INTO `saldo` (`user_id`, `jumlah_saldo`, `deposit`) 
                                    VALUES ('$id_user_edit_akun', '$jumlah_deposit_edt', '$jumlah_deposit_edt')");
            }

            echo "Akun berhasil diperbarui.";
        } else {
            echo "Terjadi kesalahan. Gagal memperbarui akun.";
        }
    } else {
        echo "Akun berhasil diperbarui.";
    }
} else {
    echo "Akun berhasil diperbarui.";
}
?>
<script>
    var boxEditAkun = document.getElementById('box_edit_akun');
    if (boxEditAkun) {
        boxEditAkun.style.display = 'none';
        window.location.href = 'index.php';
    } else {
        console.error("Element dengan ID box_edit_akun tidak ditemukan.");
    }
</script>
