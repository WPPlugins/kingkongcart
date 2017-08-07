<?php
	if($_POST['result_status']){
		switch($_POST['result_status']){
			case "success" :
				include("order-mobile-success.php");
			break;

			case "failed" :
				include("order-mobile-error.php");
			break;
		}
	} else {
		include("order-mobile-form.php");
	}
?>