<?php

include '../../../config.php';

$v_id_chat = $_POST['v_id_chat'];
$tanggal_sekarang = date('d/m/Y');

$update_dibaca_chat = $server->query("UPDATE `chat` SET `status`='dibaca' WHERE `pengirim_user_id`='$v_id_chat' AND `penerima_user_id`='1' ");

$select_chat_view = $server->query("SELECT * FROM `chat` WHERE `pengirim_user_id`='1' AND `penerima_user_id`='$v_id_chat' OR `pengirim_user_id`='$v_id_chat' AND `penerima_user_id`='1' ORDER BY `chat`.`id` ASC ");
while ($data_chat_view = mysqli_fetch_assoc($select_chat_view)) {
    $tanggal_chat_view = date('d/m/Y', $data_chat_view['waktu']);
    $jam_chat_view = date('H:i', $data_chat_view['waktu']);
    if ($data_chat_view['pengirim_user_id'] == $v_id_chat) {
?>
        <div class="box_isi_chat">
            <div class="box_bubble_chat_left">
                <div class="bubble_chat_left">
                    <p class="isi_bubble_chat_left"><?php echo $data_chat_view['text_chat']; ?></p>
                </div>
                <div class="box_waktu_chat_left">
                    <?php
                    if ($tanggal_sekarang == $tanggal_chat_view) {
                    ?>
                        <p class="waktu_chat_left"><?php echo $jam_chat_view; ?></p>
                    <?php
                    } else {
                    ?>
                        <p class="waktu_chat_left"><?php echo $tanggal_chat_view ?></p>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php
    } else {
    ?>
        <div class="box_isi_chat">
            <div class="box_bubble_chat_right">
                <div class="bubble_chat_right">
                    <p class="isi_bubble_chat_right"><?php echo $data_chat_view['text_chat']; ?></p>
                </div>
                <div class="box_waktu_chat_right">
                    <?php
                    if ($data_chat_view['status'] == "") {
                    ?>
                        <p class="ri-check-fill status_pesan_ico_d"></p>
                    <?php
                    } else if ($data_chat_view['status'] == "dibaca") {
                    ?>
                        <p class="ri-check-double-line status_pesan_ico_r"></p>
                    <?php
                    }
                    ?>
                    <?php
                    if ($tanggal_sekarang == $tanggal_chat_view) {
                    ?>
                        <p class="waktu_chat_right"><?php echo $jam_chat_view; ?></p>
                    <?php
                    } else {
                    ?>
                        <p class="waktu_chat_right"><?php echo $tanggal_chat_view ?></p>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
<?php
    }
}
?>

<style type="text/css">
    .box_isi_chat {
        width: 100%;
        overflow: hidden;
    }

    .box_bubble_chat_left {
        max-width: 60%;
        float: left;
    }

    .bubble_chat_left {
        background: var(--border-grey);
        padding: 8px 13px 8px 13px;
        overflow: hidden;
        border-radius: 3px 10px 10px 10px;
    }

    .isi_bubble_chat_left {
        float: left;
        text-align: left;
        font-size: 14px;
        color: var(--black);
        word-break: break-all;
    }

    .box_waktu_chat_right {
        width: 100%;
        height: 20px;
    }

    .waktu_chat_left {
        float: left;
        font-size: 11px;
        margin-top: 3px;
        line-height: 22px;
        margin-left: 5px;
        color: var(--semi-black);
    }

    .box_bubble_chat_right {
        max-width: 60%;
        float: right;
    }

    .bubble_chat_right {
        background: var(--orange);
        padding: 8px 13px 8px 13px;
        overflow: hidden;
        border-radius: 10px 10px 3px 10px;
    }

    .isi_bubble_chat_right {
        float: right;
        text-align: right;
        font-size: 14px;
        color: var(--white);
        word-break: break-all;
    }

    .box_waktu_chat_right {
        width: 100%;
        height: 20px;
    }

    .waktu_chat_right {
        float: right;
        font-size: 11px;
        margin-top: 3px;
        margin-right: 5px;
        color: var(--semi-black);
        line-height: 22px;
    }

    .status_pesan_ico_d {
        font-size: 16px;
        line-height: 22px;
        float: right;
        margin-right: 5px;
        color: var(--semi-black);
        margin-top: 2px;
    }

    .status_pesan_ico_r {
        font-size: 16px;
        line-height: 22px;
        float: right;
        margin-right: 5px;
        color: var(--orange);
        margin-top: 2px;
    }
</style>