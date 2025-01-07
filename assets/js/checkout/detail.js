function changeProvinsi() {
    kota.value = '';
    var data_provinsi = new FormData();
    data_provinsi.append('id_provinsi', document.getElementById('provinsi').value);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
       if (this.readyState == 1) {}
       if (this.readyState == 4 && this.status == 200) {
          document.getElementById('kota').innerHTML = this.responseText;
          var getscriptres = document.getElementsByTagName('script');
          for (var i = 0; i < getscriptres.length - 0; i++) {
             if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
          }
       }
    }
    xhttp.open("POST", "../../system/checkout/req-kota.php", true);
    xhttp.send(data_provinsi);
 }
 
 function changeKota() {
    kecamatan.value = '';
    var data_kota = new FormData();
    data_kota.append('id_kota', document.getElementById('kota').value);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
       if (this.readyState == 1) {}
       if (this.readyState == 4 && this.status == 200) {
          document.getElementById('kecamatan').innerHTML = this.responseText;
          var getscriptres = document.getElementsByTagName('script');
          for (var i = 0; i < getscriptres.length - 0; i++) {
             if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
          }
       }
    }
    xhttp.open("POST", "../../system/checkout/req-kecamatan.php", true);
    xhttp.send(data_kota);
 }
 
 function SimpanLlokasi(id_inv_sl) {
    if (provinsi.value == '') {
       provinsi.style.borderColor = '#EA2027';
    } else {
       provinsi.style.borderColor = '#e2e2e2';
    }
    if (kota.value == '') {
       kota.style.borderColor = '#EA2027';
    } else {
       kota.style.borderColor = '#e2e2e2';
    }
    if (kecamatan.value == '') {
       kecamatan.style.borderColor = '#EA2027';
    } else {
       kecamatan.style.borderColor = '#e2e2e2';
    }
    if (alamat_lengkap.value == '') {
       alamat_lengkap.style.borderColor = '#EA2027';
    } else {
       alamat_lengkap.style.borderColor = '#e2e2e2';
    }
    if (notelp.value == '') {
       notelp.style.borderColor = '#EA2027';
    } else {
       notelp.style.borderColor = '#e2e2e2';
    }
    if (provinsi.value && kota.value && alamat_lengkap.value && notelp.value) {
       var data_lokasi = new FormData();
       data_lokasi.append('id_invoice', id_inv_sl);
       data_lokasi.append('id_provinsi', document.getElementById('provinsi').value);
       data_lokasi.append('id_kota', document.getElementById('kota').value);
       data_lokasi.append('id_kecamatan', document.getElementById('kecamatan').value);
       data_lokasi.append('alamat_lengkap', document.getElementById('alamat_lengkap').value);
       data_lokasi.append('notelp', document.getElementById('notelp').value);
       var xhttp = new XMLHttpRequest();
       xhttp.onreadystatechange = function () {
          if (this.readyState == 1) {
             simpan_lokasi.style.display = 'none';
             loading_lokasi.style.display = 'flex';
          }
          if (this.readyState == 4 && this.status == 200) {
             document.getElementById('res').innerHTML = this.responseText;
             simpan_lokasi.style.display = 'flex';
             loading_lokasi.style.display = 'none';
             var getscriptres = document.getElementsByTagName('script');
             for (var i = 0; i < getscriptres.length - 0; i++) {
                if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
             }
          }
       }
       xhttp.open("POST", "../../system/checkout/save-location.php", true);
       xhttp.send(data_lokasi);
    }
 }
 
 function BatalLlokasi() {
    setting_lokasi.style.display = 'none';
 }
 
 function ubahAlamat() {
    setting_lokasi.style.display = 'flex';
 }
 
 function UbahOngkir(id_keca_tujuan, idlocuo, idivuo, user_id) {
    console.log(id_keca_tujuan);
    var data_ongkir = new FormData();
    data_ongkir.append('id_keca_tujuan_v', id_keca_tujuan);
    data_ongkir.append('idlocuo', idlocuo);
    data_ongkir.append('idivuo', idivuo);
    data_ongkir.append('user_id', user_id);
    data_ongkir.append('berat_barang', document.getElementById('berat_barang').value);
    data_ongkir.append('jumlah_barang', document.getElementById('jumlah_barang').value);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
       if (this.readyState == 1) {
          // Deteksi tampilan dekstop
          if (window.innerWidth >= 768) {
             ubah_ongkir.style.display = 'block';
          } else {
             ubah_ongkir.style.display = 'flex';
          }
 
          body.style.overflow = 'hidden';
          loading_ubah_ongkir.style.display = 'flex';
          res_ubah_ongkir.style.display = 'none';
       }
       if (this.readyState == 4 && this.status == 200) {
          document.getElementById('res_ubah_ongkir').innerHTML = this.responseText;
          loading_ubah_ongkir.style.display = 'none';
          res_ubah_ongkir.style.display = 'grid';
       }
    }
 
    xhttp.open("POST", "../../system/checkout/pilih-ongkir.php", true);
    xhttp.send(data_ongkir);
 }
 
 function CloseUbahOngkir() {
    ubah_ongkir.style.display = 'none';
    body.style.overflow = 'auto';
 }
 
 function UbahOpsiOngkir(name_kurir, idarr_kurir, kurir_layanan_ongkir, etd_ongkir, harga_ongkir) {
    var data_ubah_ongkir = new FormData();
    data_ubah_ongkir.append('id_invoice', document.getElementById('id_invoice').value);
    data_ubah_ongkir.append('name_kurir', name_kurir);
    data_ubah_ongkir.append('idarr_kurir', idarr_kurir);
    data_ubah_ongkir.append('kurir_layanan_ongkir', kurir_layanan_ongkir);
    data_ubah_ongkir.append('etd_ongkir', etd_ongkir);
    data_ubah_ongkir.append('harga_ongkir', harga_ongkir);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
       if (this.readyState == 1) {}
       if (this.readyState == 4 && this.status == 200) {
          document.getElementById('res').innerHTML = this.responseText;
          ubah_ongkir.style.display = 'none';
          body.style.overflow = 'auto';
          var getscriptres = document.getElementsByTagName('script');
          for (var i = 0; i < getscriptres.length - 0; i++) {
             if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
          }
       }
    }
    xhttp.open("POST", "../../system/checkout/ubah-ongkir.php", true);
    xhttp.send(data_ubah_ongkir);
 }
 
 function change_image() {
    const file = document.getElementById('inp_bukti_transfer').files[0];
    const fileType = file['type'];
    const validImageTypes = ['image/jpeg', 'image/png'];
    if (!validImageTypes.includes(fileType)) {
       alert_file_npng_bt.style.display = 'block';
       inp_bukti_transfer.value = '';
    } else {
       alert_file_npng_bt.style.display = 'none';
    }
 }
 
 
function upload_bukti_transfer_manual(id_inv_bt, nama_bank_bt) {
    if (inp_bukti_transfer.value == '') {
        inp_bukti_transfer.style.borderColor = '#EA2027';
    } else {
        inp_bukti_transfer.style.borderColor = '#e2e2e2';
        var data_bukti_transfer = new FormData();
        data_bukti_transfer.append('inp_bukti_transfer', document.getElementById('inp_bukti_transfer').files[0]);
        data_bukti_transfer.append('id_inv_bt', id_inv_bt);
        data_bukti_transfer.append('nama_bank_bt', nama_bank_bt);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 1) {
                ubt.style.display = 'none';
                loading_ubt.style.display = 'flex';
            }
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('res').innerHTML = this.responseText;
                var getscriptres = document.getElementsByTagName('script');
                for (var i = 0; i < getscriptres.length - 0; i++) {
                    if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
                }
            }
        }
        xhttp.open("POST", "../../system/checkout/upload-bukti-transfer.php", true);
        xhttp.send(data_bukti_transfer);
    }
}
 
 function pembayaran_manual_show() {
    var pop_transfer_manual = document.getElementById('pop_transfer_manual');
    if (pop_transfer_manual) {
        if (window.innerWidth >= 768) {
            pop_transfer_manual.style.display = 'block';
        } else {
            pop_transfer_manual.style.display = 'flex';
        }
        body.style.overflow = 'hidden';
    }
}

 
 function closeTransferManual() {
    pop_transfer_manual.style.display = 'none';
    body.style.overflow = 'auto';
 }
 

 function ubah_tipe_pembayaran() {
    back_ubah_tipe_pembayaran.style.display = 'flex';
 }
 
 function ubah_tp(id_invoice_tp, v_tp) {
    var data_tipe_pembayaran = new FormData();
    data_tipe_pembayaran.append('id_invoice_tp', id_invoice_tp);
    data_tipe_pembayaran.append('v_tp', v_tp);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
       if (this.readyState == 1) {
          cod.className = 'isi_box_ubah_tipe_pembayaran';
          online.className = 'isi_box_ubah_tipe_pembayaran';
          document.getElementById(v_tp).className = 'isi_box_ubah_tipe_pembayaran_active';
          back_ubah_tipe_pembayaran.style.display = 'none';
       }
       if (this.readyState == 4 && this.status == 200) {
          document.getElementById('res').innerHTML = this.responseText;
          var getscriptres = document.getElementsByTagName('script');
          for (var i = 0; i < getscriptres.length - 0; i++) {
             if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
          }
       }
    }
    xhttp.open("POST", "../../system/checkout/ubah-tipe-pembayaran.php", true);
    xhttp.send(data_tipe_pembayaran);
 }
 
 function buat_pesanan_mp_cod(id_inv_mpcod) {
    var data_buat_pesanan_cod = new FormData();
    data_buat_pesanan_cod.append('id_inv_mpcod', id_inv_mpcod);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
       if (this.readyState == 1) {
          p_cod.style.display = 'none';
          loading_p_cod.style.display = 'block';
       }
       if (this.readyState == 4 && this.status == 200) {
          document.getElementById('res').innerHTML = this.responseText;
          p_cod.style.display = 'block';
          loading_p_cod.style.display = 'none';
          var getscriptres = document.getElementsByTagName('script');
          for (var i = 0; i < getscriptres.length - 0; i++) {
             if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
          }
       }
    }
    xhttp.open("POST", "../../system/checkout/pesanan-cod.php", true);
    xhttp.send(data_buat_pesanan_cod);
 }
 
 function ubah_dikirim_dari(idlocdk, idinvloc) {
    var data_udkd = new FormData();
    data_udkd.append('idlocdk', idlocdk);
    data_udkd.append('idinvloc', idinvloc);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
       if (this.readyState == 1) {
          dikirim_dari.style.display = 'none';
       }
       if (this.readyState == 4 && this.status == 200) {
          document.getElementById('res').innerHTML = this.responseText;
          var getscriptres = document.getElementsByTagName('script');
          for (var i = 0; i < getscriptres.length - 0; i++) {
             if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
          }
       }
    }
    xhttp.open("POST", "../../system/checkout/ubah-dikirim-dari.php", true);
    xhttp.send(data_udkd);
 }
 
 function ubahdikirimdari() {
    dikirim_dari.style.display = 'flex';
 }
 
 function ubahcatatan() {
    back_catatan.style.display = 'flex';
 }
 
 function simpan_catatan(idivcat) {
    var data_scat = new FormData();
    data_scat.append('idivcat', idivcat);
    data_scat.append('i_catatanb', document.getElementById('i_catatanb').value);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
       if (this.readyState == 1) {
          p_butacat.style.display = 'none';
          load_butacat.style.display = 'block';
       }
       if (this.readyState == 4 && this.status == 200) {
          document.getElementById('res').innerHTML = this.responseText;
          var getscriptres = document.getElementsByTagName('script');
          for (var i = 0; i < getscriptres.length - 0; i++) {
             if (getscriptres[i + 0].text != null) eval(getscriptres[i + 0].text);
          }
       }
    }
    xhttp.open("POST", "../../system/checkout/simpan-catatan.php", true);
    xhttp.send(data_scat);
 }
 
function BatalLcatatan() {
    document.getElementById('back_catatan').style.display = 'none';
}

function BatalLvoucher() {
    document.getElementById('back_pv').style.display = 'none';
}

function ubah_voucher() {
    back_pv.style.display = 'flex';
}

function hide_back_pv() {
    back_pv.style.display = 'none';
}
function pakai_voucher(user_id, idinvvoucherp, idvoucherp, voucher_bk, loading_voucherp) {
    var data_pakai_voucher = new FormData();
    data_pakai_voucher.append('user_id', user_id);
    data_pakai_voucher.append('idinvvoucherp', idinvvoucherp);
    data_pakai_voucher.append('idvoucherp', idvoucherp);

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 1) {
            var element_voucher_bk = document.getElementById(voucher_bk);
            var element_loading_voucherp = document.getElementById(loading_voucherp);
            
            if (element_voucher_bk && element_loading_voucherp) {
                element_voucher_bk.style.display = 'none';
                element_loading_voucherp.style.display = 'block';
            } else {
                console.error('Elemen dengan ID ' + voucher_bk + ' atau ' + loading_voucherp + ' tidak ditemukan.');
            }
        }
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('res').innerHTML = this.responseText;
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length; i++) {
                if (getscriptres[i].text != null) eval(getscriptres[i].text);
            }
            // Ubah teks pada elemen #ubah_onkirrrr menjadi "Ganti" setelah memilih voucher
            document.getElementById('ubah_onkirrrr').innerText = 'Ganti';
        }
    }

    xhttp.open("POST", "../../system/checkout/pilih-voucher.php", true);
    xhttp.send(data_pakai_voucher);
}

function hapus_voucher(idinvvoucherp) {
    var data_hapus_voucher = new FormData();
    data_hapus_voucher.append('idinvvoucherp', idinvvoucherp);

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('res').innerHTML = this.responseText;
            var getscriptres = document.getElementsByTagName('script');
            for (var i = 0; i < getscriptres.length; i++) {
                if (getscriptres[i].text != null) eval(getscriptres[i].text);
            }

            // Ubah teks pada elemen #ubah_onkirrrr menjadi "Pilih Voucher" setelah menghapus
            document.getElementById('ubah_onkirrrr').innerText = 'Pilih Voucher';
        }
    }

    xhttp.open("POST", "../../system/checkout/hapus-voucher.php", true);
    xhttp.send(data_hapus_voucher);
}

