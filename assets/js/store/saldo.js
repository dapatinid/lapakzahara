function show_add_kategori() {
    tambah_kategori_form.style.display = 'flex';
}

function batal_add_kategori() {
    tambah_kategori_form.style.display = 'none';
} 

function hide_tp_fs() {
    tambah_kategori_form.style.display = 'none';
}

// Gunakan variabel JavaScript yang telah disisipkan
console.log('Nilai minimal dari PHP:', nilaiMinimal);
 
// Fungsi untuk menyimpan data profile
function simpan_edit_profile() {
    var p_jumlah = document.getElementById('p_jumlah');
    var jumlah = document.getElementById('jumlah');
    var p_nama_bank = document.getElementById('p_nama_bank');
    var nama_bank = document.getElementById('nama_bank');
    var p_rekening_tujuan = document.getElementById('p_rekening_tujuan');
    var rekening_tujuan = document.getElementById('rekening_tujuan');
    var p_atas_nama = document.getElementById('p_atas_nama');
    var atas_nama = document.getElementById('atas_nama');
    var pesanPenarikan = document.getElementById('pesan_penarikan');
    var bu_e_pro = document.getElementById('bu_e_pro');
    var loading_e_pro = document.getElementById('loading_e_pro');

    pesanPenarikan.style.display = 'none'; // Sembunyikan pesan penarikan saat memulai

    if (jumlah.value == '') {
        p_jumlah.style.color = '#EA2027';
        jumlah.style.border = '1px solid #EA2027';

    } else {
        p_jumlah.style.color = '#959595';
        jumlah.style.border = '1px solid #e2e2e2';
        // Periksa apakah jumlah kurang dari PHP
        var parsedValue = parseFloat(jumlah.value.replace(/\./g, '').replace(',', '.'));
        if (parsedValue < nilaiMinimal) {
            pesanPenarikan.innerHTML = '<span style="color: red; font-weight: 500; font-size: 13px;">Minimal Penarikan Rp' + nilaiMinimal.toLocaleString('id-ID') + '!</span>';
            pesanPenarikan.style.display = 'block'; // Tampilkan pesan penarikan
            p_jumlah.style.color = '#EA2027';
            jumlah.style.border = '1px solid #EA2027'; // Ubah label merah
            return; // Jangan lanjut jika jumlah kurang dari 50.000
        } else {
            pesanPenarikan.style.display = 'none'; // Sembunyikan pesan penarikan
        }
    }

    if (nama_bank.value == '') {
        p_nama_bank.style.color = '#EA2027';
        nama_bank.style.border = '1px solid #EA2027';
    } else {
        p_nama_bank.style.color = '#959595';
        nama_bank.style.border = '1px solid #e2e2e2';
    }

    if (rekening_tujuan.value == '') {
        p_rekening_tujuan.style.color = '#EA2027';
        rekening_tujuan.style.border = '1px solid #EA2027';
    } else {
        p_rekening_tujuan.style.color = '#959595';
        rekening_tujuan.style.border = '1px solid #e2e2e2';
    }

    if (atas_nama.value == '') {
        p_atas_nama.style.color = '#EA2027';
        atas_nama.style.border = '1px solid #EA2027';
    } else {
        p_atas_nama.style.color = '#959595';
        atas_nama.style.border = '1px solid #e2e2e2';
    }

    if (jumlah.value && nama_bank.value && rekening_tujuan.value && atas_nama.value) {
        var jumlahDipotong = Math.floor(parseFloat(jumlah.value.replace(/\./g, '').replace(',', '.')) * 0.95);
        var jumlahValue = document.getElementById('jumlah').value;
        var jumlahTanpaTitik = jumlahValue.replace(/[.,]/g, ''); // Menghapus titik dan koma

        var data_edit_profile = new FormData();
        data_edit_profile.append('jumlah', jumlahTanpaTitik);
        data_edit_profile.append('jumlah_dipotong', Math.floor(jumlahDipotong)); // Jumlah yang telah dipotong
        data_edit_profile.append('nama_bank', nama_bank.value);
        data_edit_profile.append('rekening_tujuan', rekening_tujuan.value);
        data_edit_profile.append('atas_nama', atas_nama.value);

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 1) {
                bu_e_pro.style.display = 'none';
                loading_e_pro.style.display = 'flex';
            }
            if (this.readyState == 4 && this.status == 200) {
                pesanPenarikan.innerHTML = this.responseText;
                pesanPenarikan.style.display = 'block'; // Tampilkan pesan penarikan
                bu_e_pro.style.display = 'flex';
                loading_e_pro.style.display = 'none';
                var getscriptres = document.getElementsByTagName('script');
                for (var i = 0; i < getscriptres.length; i++) {
                    if (getscriptres[i].text != null) eval(getscriptres[i].text);
                }
            }
        }

        xhttp.open('POST', '../../system/store/saldo.php', true);
        xhttp.send(data_edit_profile);
    }
}
