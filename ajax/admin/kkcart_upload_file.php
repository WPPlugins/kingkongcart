<?php

$array_root = explode("/plugins/",$_SERVER['SCRIPT_FILENAME']);
$root = explode("/",$array_root[0]);
$count_root = count($root);
$abs_root = str_replace($root[$count_root-1],"",$array_root[0]);


require_once($abs_root.'wp-load.php');
require_once($abs_root.'wp-admin/includes/image.php');
global $wpdb;


if (isset($_FILES['file'])) {
    $aExtraInfo = getimagesize($_FILES['file']['tmp_name']);
    $sImage = "data:" . $aExtraInfo["mime"] . ";base64," . base64_encode(file_get_contents($_FILES['file']['tmp_name']));
}

	echo "added_array = [{imgurl: '".$sImage."', name: '".$_FILES['file']['name']."', tmp_name: '".$_FILES['file']['tmp_name']."' }];";
?>