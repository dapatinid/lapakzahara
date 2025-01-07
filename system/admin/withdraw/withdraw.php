<?php
include '../../../config.php';

// Variabel dari tabel riwayat_penarikan
$nama_lengkap_edt = mysqli_real_escape_string($server, $_POST['nama_lengkap_edt']);
$jumlah_dipotong_edt = mysqli_real_escape_string($server, $_POST['jumlah_dipotong_edt']);
$nama_bank_edt = mysqli_real_escape_string($server, $_POST['nama_bank_edt']);
$rekening_tujuan_edt = mysqli_real_escape_string($server, $_POST['rekening_tujuan_edt']);
$atas_nama_edt = mysqli_real_escape_string($server, $_POST['atas_nama_edt']);
$status_edt = mysqli_real_escape_string($server, $_POST['status_edt']);
$keterangan_edt = mysqli_real_escape_string($server, $_POST['keterangan_edt']);
$id_user_edit_akun = mysqli_real_escape_string($server, $_POST['id_user_edit_akun']);
$idpenarikan_edt = mysqli_real_escape_string($server, $_POST['idpenarikan_edt']);

// Periksa apakah request penarikan ditemukan
$select_sql = "SELECT * FROM riwayat_penarikan WHERE idpenarikan = $idpenarikan_edt";
$result = $conn->query($select_sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $jumlah_penarikan = $row["jumlah"];

    // Update status request penarikan saldo
    $update_sql = "UPDATE riwayat_penarikan SET status = $status_edt, keterangan = '$keterangan_edt' WHERE idpenarikan = $idpenarikan_edt";
    if ($conn->query($update_sql) === TRUE) {
        if ($status_edt == 1) {
            // Kurangi jumlah saldo pengguna sesuai dengan jumlah penarikan dan update kolom withdraw di tabel saldo
            $user_id = $row["user_id"];

            // Periksa apakah pengguna sudah memiliki nilai di kolom withdraw
            $check_existing_withdraw_sql = "SELECT withdraw FROM saldo WHERE user_id = $user_id";
            $withdraw_result = $conn->query($check_existing_withdraw_sql);

            if ($withdraw_result->num_rows > 0) {
                $withdraw_row = $withdraw_result->fetch_assoc();
                $existing_withdraw = $withdraw_row["withdraw"];
                $total_withdraw = $existing_withdraw + $jumlah_penarikan;
                $update_saldo_sql = "UPDATE saldo SET jumlah_saldo = jumlah_saldo - $jumlah_penarikan, withdraw = $total_withdraw WHERE user_id = $user_id";
            } else {
                $update_saldo_sql = "UPDATE saldo SET jumlah_saldo = jumlah_saldo - $jumlah_penarikan, withdraw = $jumlah_penarikan WHERE user_id = $user_id";
            }

            if ($conn->query($update_saldo_sql) === TRUE) {
                echo "Persetujuan berhasil disimpan. Jumlah saldo pengguna berhasil dikurangi.";
            } else {
                echo "Gagal mengupdate jumlah saldo: " . $conn->error;
            }
        } else {
            echo "Persetujuan berhasil disimpan.";
        }
    } else {
        echo "Gagal menyimpan persetujuan: " . $conn->error;
    }
} else {
    echo "Request penarikan tidak ditemukan.";
}

?>
<script>
    var boxEditAkun = document.getElementById('box_edit_akun');
    if (boxEditAkun) {
        boxEditAkun.style.display = 'none';
        location.reload();
    } else {
        console.error("Element dengan ID box_edit_akun tidak ditemukan.");
    }
</script>
