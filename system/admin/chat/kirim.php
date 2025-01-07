<?php
include '../../../config.php';

$v_id_chat = $_POST['v_id_chat'];
$input_chat = mysqli_real_escape_string($server, $_POST['input_chat']);

$time = time();

$kirim_chat = $server->query("INSERT INTO `chat`(`pengirim_user_id`, `penerima_user_id`, `text_chat`, `waktu`) VALUES ('1', '$v_id_chat', '$input_chat', '$time')");
