<?php
include '../config.php';

$page_admin = 'chat';

if (isset($_COOKIE['login_admin'])) {
    if ($akun_adm == 'false') {
        header("location: " . $url . "system/admin/logout");
    }
} else {
    header("location: " . $url . "admin/login/");
}

$tanggal_sekarang = date('d/m/Y');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="icon" href="../../assets/icons/<?php echo $favicon; ?>" type="image/svg">
    <link rel="stylesheet" href="../../assets/css/chat/index.css">
</head>

<body>
    <!-- CONTENT -->
    <div class="admin">
        <?php include '../partials/menu.php'; ?>
        <div class="content_admin">
            <div style="width: 100%; height: 100%">
                <div style="width:100%; height: 100%; background-color: var(--white); border-radius: 3px; display: flex; flex-direction: row;">
                    <div class="list_chat" id="list_chat">
               <div class="header_chat">
                  <a href="<?php echo $url; ?>"><img src="../../assets/icons/chat.svg" class="logo_chat"></a>
                  <h1>Chat!</h1>
               </div>
               <div class="list_user_chat">
                  <div class="box_isi_list_user_chat">
                     <?php
                        $iduserarr = array();
                        $select_chat = $server->query("SELECT * FROM `chat` WHERE `pengirim_user_id`='$iduser' OR `penerima_user_id`='$iduser' ORDER BY `chat`.`id` DESC ");
                        while ($data_chat = mysqli_fetch_assoc($select_chat)) {
                            $iduserarr[] = $data_chat['pengirim_user_id'];
                            $iduserarr[] = $data_chat['penerima_user_id'];
                        }
                        $arruniq = array_unique($iduserarr);
                        foreach ($arruniq as $keyiduserarr => $valueiduserarr) {
                            if ($valueiduserarr != $iduser) {
                                // SELECT LAST CHAT
                                $select_chat_view = $server->query("SELECT * FROM `chat` WHERE `pengirim_user_id`='$iduser' AND `penerima_user_id`='$valueiduserarr' OR `pengirim_user_id`='$valueiduserarr' AND `penerima_user_id`='$iduser' ORDER BY `chat`.`id` DESC ");
                                $data_chat_view = mysqli_fetch_assoc($select_chat_view);
                                // SELECT DATA USER CHAT
                                $select_user_chat = $server->query("SELECT * FROM `akun` WHERE `id`='$valueiduserarr' ");
                                $data_user_chat = mysqli_fetch_assoc($select_user_chat);
                        
                                $tanggal_chat_view = date('d/m/Y', $data_chat_view['waktu']);
                                $jam_chat_view = date('H:i', $data_chat_view['waktu']);
                        ?>
                     <div class="isi_list_user_chat" onclick="show_detail_chat('<?php echo $valueiduserarr; ?>', '../../assets/image/profile/<?php echo $data_user_chat['foto']; ?>', '<?php echo $data_user_chat['nama_lengkap']; ?>', '<?php echo $data_user_chat['verifikasi_user']; ?>')">
                        <img src="../../assets/image/profile/<?php echo $data_user_chat['foto']; ?>">
                        <div class="text_list_chat">
                           <div class="name_text_list_chat">
                              <div class="isi_name_text_list_chat">
                                 <p><?php echo $data_user_chat['nama_lengkap']; ?> <?php if ($data_user_chat['verifikasi_user'] == 'Ya') { ?>
      <img src="<?php echo $url; ?>/assets/icons/verifikasi-user.png" id="img-verif" data-original-title="null" class="has-tooltip" style="width: 20px; height: auto; vertical-align: middle;">
    <?php } ?></p> 
                              </div>
                              <div class="isi_time_text_list_chat">
                                 <?php
                                    if ($tanggal_sekarang == $tanggal_chat_view) {
                                    ?>
                                 <p><?php echo $jam_chat_view; ?></p>
                                 <?php
                                    } else {
                                    ?>
                                 <p><?php echo $tanggal_chat_view ?></p>
                                 <?php
                                    }
                                    ?>
                              </div>
                           </div>
                           <div class="box_isi_text_chat">
                              <?php
                                 if ($data_chat_view['pengirim_user_id'] != $valueiduserarr) {
                                     if ($data_chat_view['status'] == '') {
                                 ?>
                              <i class="ri-check-line"></i>
                              <?php
                                 } else {
                                 ?>
                              <i class="ri-check-double-line"></i>
                              <?php
                                 }
                                 }
                                 ?>
                              <p><?php echo $data_chat_view['text_chat']; ?></p>
                              <?php
                                 if ($data_chat_view['pengirim_user_id'] == $valueiduserarr) {
                                 }
                                 ?>
                           </div>
                        </div>
                     </div>
                     <?php
                        }
                        }
                        ?>
                  </div>
               </div>
            </div>
            <div class="detail_chat" id="detail_chat">
               <div class="box_detail_chat" id="box_detail_chat">
                  <div class="header_dc">
    <i class="ri-arrow-left-line" onclick="back_to_list_chat()"></i>
    <img src="" id="img_dc">
    <h5 id="name_dc"> </h5> 
    <p id="verification_status_dc"></p> 
    <img src="<?php echo $url; ?>/assets/icons/verifikasi-user.png" id="verification_icon_dc" style="margin-left: 5px;width: 15px; height: auto; vertical-align: middle; display: none;">
</div>
                  <div class="chat_dc" id="chat_dc">
                  </div>
                  <div class="menu_dc">
                     <input type="text" class="input_chat" id="input_chat" placeholder="Ketik pesan" maxlength="150">
                     <i class="ri-send-plane-2-fill" onclick="kirim_chat()"></i>
                  </div>
                  <input type="hidden" id="v_id_chat">
               </div>
               <div class="box_detail_chat2" id="box_detail_chat2">
                  <img src="../../assets/icons/chat.svg">
                  <p>Mulai chat dengan siapa saja.</p>
               </div>
            </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTENT --> 

    <?php
    if (isset($_GET['mulai'])) {
        $idusrget = $_GET['mulai'];
        $s_userchat = $server->query("SELECT * FROM `akun` WHERE `id`='$idusrget' ");
        $data_s_userchat = mysqli_fetch_assoc($s_userchat);
    ?>
        <script>
            window.onload = function() {
                var width_co = window.innerWidth;
                if (width_co < 900) {
                    list_chat.style.display = 'none';
                    detail_chat.style.display = 'flex';
                }
                show_detail_chat('<?php echo $data_s_userchat['id']; ?>', '../../assets/image/profile/<?php echo $data_s_userchat['foto']; ?>', '<?php echo $data_s_userchat['nama_lengkap']; ?>');
            }
        </script>
    <?php
    }
    ?>

    <!-- JS -->
    <script src="../../assets/js/admin/chat/index.js"></script>
    <!-- JS -->
</body>

</html>