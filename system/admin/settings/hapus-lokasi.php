<?php
include '../../../config.php';

$idlokasihap = $_POST['idlokasihap'];

$dellokset = $server->query("DELETE FROM `setting_lokasi` WHERE `id`='$idlokasihap' ");
