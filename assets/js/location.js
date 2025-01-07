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
    xhttp.open("POST", "./../system/checkout/req-kota.php", true);
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
    xhttp.open("POST", "./../system/checkout/req-kecamatan.php", true);
    xhttp.send(data_kota);
 }
 
 function SimpanLokasi(idInvoice) {
    // Mengambil nilai dari elemen formulir
    var provinsi = document.getElementById("provinsi").value;
    var kota = document.getElementById("kota").value;
    var kecamatan = document.getElementById("kecamatan").value;
    var notelp = document.getElementById("notelp").value;
    var alamatLengkap = document.getElementById("alamat_lengkap").value;

    // Membuat objek data untuk dikirim ke server
    var data = {
        id_invoice: idInvoice,
        provinsi: provinsi,
        kota: kota,
        kecamatan: kecamatan,
        notelp: notelp,
        alamat_lengkap: alamatLengkap
    };

    // Menyimpan data menggunakan Fetch API
    fetch("./../system/location/save-location.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(result => {
        console.log(result);
        // Tambahkan logika atau tindakan tambahan jika diperlukan setelah penyimpanan berhasil
    })
    .catch(error => {
        console.error("Error:", error);
    });
}
     
 
 function BatalLlokasi() {
    setting_lokasi.style.display = 'none';
 }
 
 function ubahAlamat() {
    setting_lokasi.style.display = 'flex';
 }