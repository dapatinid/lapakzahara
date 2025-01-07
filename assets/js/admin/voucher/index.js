function buat_voucher_show() {
    back_btv.style.display = 'flex';
    name_v_p.innerHTML = 'Buat';
    idvouceredit.value = '';
    persen_vo.value = '';
    max_vo.value = '';
    hari_vo.value = '';
}

function close_btv() {
    back_btv.style.display = 'none';
    idvouceredit.value = '';
    tipe_vo.value = '';
    persen_vo.value = '';
    max_vo.value = '';
    hari_vo.value = '';
}

function show_edit_voucher(idedtv, tipevo, persenvo, maxvo, harivo) {
    back_btv.style.display = 'flex';
    name_v_p.innerHTML = 'Buat';
    idvouceredit.value = idedtv;
    tipe_vo.value = tipevo;
    persen_vo.value = persenvo;
    max_vo.value = maxvo;
    hari_vo.value = harivo;
}

function show_confirm_hapus(idvohapusv) {
    if (confirm("Apakah anda yakin ingin menghapus voucher ini ?") == true) {
        idvohapus.value = idvohapusv;
        hapus_confirm_vo.click();
    }
}