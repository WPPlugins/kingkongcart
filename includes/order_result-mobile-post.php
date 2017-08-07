<?php
header("Content-Type: text/html; charset=UTF-8");
		require_once('HttpClient.class.php');

		$P_STATUS = $_POST['P_STATUS'];
		$P_UNAME  = iconv("EUC-KR", "UTF-8", $_POST['P_UNAME']);
		$P_RMESG1 = iconv("EUC-KR", "UTF-8", $_POST['P_RMESG1']);
		$P_RMESG2 = iconv("EUC-KR", "UTF-8", $_POST['P_RMESG2']);
		$P_OID 	  = $_POST['P_OID'];
		$P_REQ_URL = $_GET['P_REQ_URL'];
		$P_REQ_URL = str_replace("https://", "ssl://", $P_REQ_URL);

		if($_GET['P_STATUS'] == "00"){
			$client = new HttpClient("fcmobile.inicis.com");
			$client->post('/smart/pay_req_url.php', array(
					'P_MID' => $_GET['P_NOTI'],
					'P_TID' => $_GET['P_TID']
			));

			$get_status = $client->getStatus();

			if($get_status == "200"){
				$content = $client->getContent();
			}
		}

if($P_STATUS == "00" or $get_status == "200"){
?>
	<script>
		window.opener.document.pay_result.result_status.value 	= "success";
		window.opener.document.pay_result.P_RMESG1.value 		= "<?php echo $P_RMESG1;?>";
		window.opener.document.pay_result.P_RMESG2.value 		= "<?php echo $P_RMESG2;?>";
		window.opener.document.getElementById("payment_loading").style.display 	= "block";
		window.close();
		window.opener.document.pay_result.submit();
	</script>
<?php
} else {
?>
	<script>
		window.opener.document.pay_result.result_status.value 	= "failed";
		window.opener.document.pay_result.P_RMESG1.value 		= "<?php echo $P_RMESG1;?>";
		window.opener.document.pay_result.P_RMESG2.value 		= "<?php echo $P_RMESG2;?>";
		window.close();
	</script>
<?php
}
?>