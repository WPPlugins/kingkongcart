<?php
	$P_TYPE = $_POST['P_TYPE'];
	$P_RMESG1 = $_POST['P_RMESG1'];
	$P_RMESG2 = $_POST['P_RMESG2'];
?>
	다음과 같은 원인으로 결제에 실패하였습니다.<br>
<?php
	if($P_TYPE){
?>
	결제타입 : <?php echo $P_TYPE;?><br>
<?php
	}
?>
	결과코드 : <?php echo $P_RMESG1;?><br>
<?php
	if($P_RMESG2){
?>
	결과내용 : <?php echo $P_RMESG2;?>
<?php
	}
?>