<?php

/* INIsecurepaystart.php
 *
 * 이니페이 웹페이지 위변조 방지기능이 탑재된 결제요청페이지.
 * 코드에 대한 자세한 설명은 매뉴얼을 참조하십시오.
 * <주의> 구매자의 세션을 반드시 체크하도록하여 부정거래를 방지하여 주십시요.
 *
 * http://www.inicis.com
 * Copyright (C) 2006 Inicis Co., Ltd. All rights reserved.
 */



    /**************************
     * 1. 라이브러리 인클루드 *
     **************************/
    require("libs/INILib.php");

    /***************************************
     * 2. INIpay50 클래스의 인스턴스 생성  *
     ***************************************/
    $inipay = new INIpay50;

    /**************************
     * 3. 암호화 대상/값 설정 *
     **************************/
    $inipay->SetField("inipayhome", KINGKONGCART_ABSPATH."payment/INICIS");       // 이니페이 홈디렉터리(상점수정 필요)
    $inipay->SetField("type", "chkfake");      // 고정 (절대 수정 불가)
    $inipay->SetField("debug", "true");        // 로그모드("true"로 설정하면 상세로그가 생성됨.)
    $inipay->SetField("enctype","asym"); 			//asym:비대칭, symm:대칭(현재 asym으로 고정)
    /**************************************************************************************************
     * admin 은 키패스워드 변수명입니다. 수정하시면 안됩니다. 1111의 부분만 수정해서 사용하시기 바랍니다.
     * 키패스워드는 상점관리자 페이지(https://iniweb.inicis.com)의 비밀번호가 아닙니다. 주의해 주시기 바랍니다.
     * 키패스워드는 숫자 4자리로만 구성됩니다. 이 값은 키파일 발급시 결정됩니다.
     * 키패스워드 값을 확인하시려면 상점측에 발급된 키파일 안의 readme.txt 파일을 참조해 주십시오.
     **************************************************************************************************/
	$inipay->SetField("admin", $inicis_key_pwd); 				// 키패스워드(키발급시 생성, 상점관리자 패스워드와 상관없음)
    $inipay->SetField("checkopt", "false"); 		//base64함:false, base64안함:true(현재 false로 고정)

		//필수항목 : mid, price, nointerest, quotabase
		//추가가능 : INIregno, oid
		//*주의* : 	추가가능한 항목중 암호화 대상항목에 추가한 필드는 반드시 hidden 필드에선 제거하고 
		//          SESSION이나 DB를 이용해 다음페이지(INIsecureresult.php)로 전달/셋팅되어야 합니다.
    $inipay->SetField("mid", $inicis_key_id);            // 상점아이디
    $inipay->SetField("price", $with_shipping_price);                // 가격
    $inipay->SetField("nointerest", "no");             //무이자여부(no:일반, yes:무이자)
    $inipay->SetField("quotabase", "lumpsum:00:02:03:04:05:06:07:08:09:10:11:12");//할부기간

    /********************************
     * 4. 암호화 대상/값을 암호화함 *
     ********************************/
    $inipay->startAction();

    /*********************
     * 5. 암호화 결과  *
     *********************/
 		if( $inipay->GetResult("ResultCode") != "00" ) 
		{
			echo $inipay->GetResult("ResultMsg");
			exit(0);
		}

    /*********************
     * 6. 세션정보 저장  *
     *********************/
		$_SESSION['INI_MID'] = $inicis_key_id;						// 상점ID
		$_SESSION['INI_ADMIN'] = $inicis_key_pwd;					// 키패스워드(키발급시 생성, 상점관리자 패스워드와 상관없음)
		$_SESSION['INI_PRICE'] = $with_shipping_price;     			// 가격 
		$_SESSION['INI_RN'] = $inipay->GetResult("rn"); 			// 고정 (절대 수정 불가)
		$_SESSION['INI_ENCTYPE'] = $inipay->GetResult("enctype"); 	// 고정 (절대 수정 불가)

?>
<script language=javascript src="http://plugin.inicis.com/pay61_secuni_cross.js"></script>
<script language=javascript>
StartSmartUpdate();
</script>
<!--
* 웹SITE 가 https를 이용하면 https://plugin.inicis.com/pay61_secunissl_cross.js를 사용 
* 웹SITE 가 Unicode(UTF-8)를 이용하면 http://plugin.inicis.com/pay61_secuni_cross.js를 사용 
* 웹SITE 가 https, unicode를 이용하면 https://plugin.inicis.com/pay61_secunissl_cross.js 사용 
--> 
<!--
※ 주의 ※
 상단 자바스크립트는 지불페이지를 실제 적용하실때 지불페이지 맨위에 위치시켜 
 적용하여야 만일에 발생할수 있는 플러그인 오류를 미연에 방지할 수 있습니다.

<script language=javascript src="http://plugin.inicis.com/pay61_secuni_cross.js"></script> 
  <script language=javascript>
  StartSmartUpdate();	// 플러그인 설치(확인)
  </script>
--> 


<script language=javascript>

var openwin;

function pay(frm)
{
	// MakePayMessage()를 호출함으로써 플러그인이 화면에 나타나며, Hidden Field
	// 에 값들이 채워지게 됩니다. 일반적인 경우, 플러그인은 결제처리를 직접하는 것이
	// 아니라, 중요한 정보를 암호화 하여 Hidden Field의 값들을 채우고 종료하며,
	// 다음 페이지인 INIsecureresult.php로 데이터가 포스트 되어 결제 처리됨을 유의하시기 바랍니다.

	if(document.ini.clickcontrol.value == "enable")
	{
		
		if(document.ini.goodname.value == "")  // 필수항목 체크 (상품명, 상품가격, 구매자명, 구매자 이메일주소, 구매자 전화번호)
		{
			alert("상품명이 빠졌습니다. 필수항목입니다.");
			return false;
		}
		else if(document.ini.buyername.value == "")
		{
			alert("구매자명이 빠졌습니다. 필수항목입니다.");
			return false;
		} 
		else if(document.ini.buyeremail.value == "")
		{
			alert("구매자 이메일주소가 빠졌습니다. 필수항목입니다.");
			return false;
		}
		else if(document.ini.buyertel.value == "")
		{
			alert("구매자 전화번호가 빠졌습니다. 필수항목입니다.");
			return false;
		}
		else if( ( navigator.userAgent.indexOf("MSIE") >= 0 || navigator.appName == 'Microsoft Internet Explorer' ) &&  (document.INIpay == null || document.INIpay.object == null) )  // 플러그인 설치유무 체크
		{
			alert("\n이니페이 플러그인 128이 설치되지 않았습니다. \n\n안전한 결제를 위하여 이니페이 플러그인 128의 설치가 필요합니다. \n\n다시 설치하시려면 Ctrl + F5키를 누르시거나 메뉴의 [보기/새로고침]을 선택하여 주십시오.");
			return false;
		}
		else
		{
			/******
			 * 플러그인이 참조하는 각종 결제옵션을 이곳에서 수행할 수 있습니다.
			 * (자바스크립트를 이용한 동적 옵션처리)
			 */

			if(jQuery("[name=product_kind]").val() > 0){

				if(jQuery("[name=postcode1]").val() == "" || jQuery("[name=postcode2]").val() == ""){
					alert("우편번호를 기입하시기 바랍니다.");
					return false;
				} else if (jQuery("[name=address]").val() == "" || jQuery("[name=else_address]").val() == ""){
					alert("주소를 기입하시기 바랍니다.");
					return false;
				} else if (jQuery("[name=shipping_tel1]").val() == "" || jQuery("[name=shipping_tel2]").val() == "" || jQuery("[name=shipping_tel3]").val() == ""){
					alert("배송받으시는 분의 연락처를 기입하시기 바랍니다.");
					return false;
				} else if ( jQuery(".agree_input_privacy").val()){
						if (jQuery(".agree_input_privacy").prop("checked") == false){
						alert("개인정보 보호정책에 동의하셔야 합니다.");
						return false;
						}
				}
			} else {

				if ( jQuery(".agree_input_privacy").val() ){
					if (jQuery(".agree_input_privacy").prop("checked") == false){
						alert("개인정보 보호정책에 동의하셔야 합니다.");
						return false;
					}
				}

			}
			
						 
			if (MakePayMessage(frm))
			{
				disable_click();
				openwin = window.open("childwin.html","childwin","width=299,height=149");		
				return true;
			}
			else
			{
				if( IsPluginModule() )     //plugin타입 체크
   				{
					alert("결제를 취소하셨습니다.");
				}
				return false;
			}
		}
	}
	else
	{
		return false;
	}
}


function enable_click()
{
	//document.ini.clickcontrol.value = "enable"
	jQuery("[name=ini]").find("[name=clickcontrol]").val("enable");
}

function disable_click()
{
	//document.ini.clickcontrol.value = "disable"
	jQuery("[name=ini]").find("[name=clickcontrol]").val("disable");
}

function focus_control()
{
	if(jQuery("[name=ini]").find("[name=clickcontrol]").val() == "disable")
		openwin.focus();
}
</script>


<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->


jQuery("body").ready(function(){

	enable_click();

});
</script>

<!--
※ 주의 ※
 아래의 body TAG의 내용중에 
 onload="javascript:enable_click()" onFocus="javascript:focus_control()" 이 부분은 수정없이 그대로 사용.
 아래의 form TAG내용도 수정없이 그대로 사용.
-->

<form id="inicis_form" class="kingkong_order_form" name="ini" method="post" action="" onSubmit="return pay(this)"> 
<!-- 결제수단 -->

<!--
<select name="gopaymethod">
    <option value="">[ 결제방법을 선택하세요. ] 
    <option value="Card">신용카드 결제
    <option value="VCard">신용카드 ISP결제
    <option value="DirectBank">실시간 은행계좌이체 
    <option value="HPP">핸드폰 결제
    <option value="PhoneBill">받는전화결제 
    <option value="Ars1588Bill">1588 전화 결제 
    <option value="VBank">무통장 입금(가상계좌) 
    <option value="OCBPoint">OK 캐쉬백포인트 결제
    <option value="Culture">문화상품권 결제
    <option value="kmerce">K-merce 상품권 결제
    <option value="TeenCash">틴캐시 결제
    <option value="dgcl">스마트 문상 결제
    <option value="BCSH">도서문화 상품권 결제
    <option value="MMLG">M마일리지
    <option value="HPMN">해피머니 상품권 결제
    <option value="YPAY">옐로페이
    <option value="onlycard" >신용카드 결제(전용메뉴) 
    <option value="onlyisp">신용카드 ISP결제 (전용메뉴) 
    <option value="onlydbank">실시간 은행계좌이체 (전용메뉴) 
    <option value="onlycid"> 신용카드/계좌이체/무통장입금(전용메뉴) 
    <option value="onlyvbank">무통장입금(가상계좌-전용메뉴) 
    <option value="onlyhpp"> 핸드폰 결제(전용메뉴) 
    <option value="onlyphone"> 전화 결제(전용메뉴) 
    <option value="onlyocb"> OK 캐쉬백 결제 - 복합결제 불가능(전용메뉴) 
    <option value="onlyocbplus"> OK 캐쉬백 결제- 복합결제 가능(전용메뉴) 
    <option value="onlyculture"> 문화상품권 결제(전용메뉴) 
    <option value="onlykmerce"> K-merce 상품권 결제(전용메뉴)
    <option value="onlyteencash">틴캐시 결제(전용메뉴)
    <option value="onlydgcl">스마트 문상 결제(전용메뉴)
    <option value="onlybcsh">도서문화 상품권 결제(전용메뉴)
    <option value="onlymmlg">M마일리지(전용메뉴)
    <option value="onlyhpmn">해피머니 상품권 결제(전용메뉴)
    <option value="onlyypay">옐로페이(전용메뉴)
</select>
-->

<!-- 상 품 명 -->
<input type="hidden" name="goodname" size="20" value="<?php echo $payment_insert_title;?>">
<!-- 성명 -->
<input type="hidden" name="buyername" size="20" value="<?php echo $user_display_name;?>">
<!-- 전 자 우 편 -->
<input type="hidden" name="buyeremail" size="20" value="<?php echo $user_email;?>">
<!--
			※ 주의 ※
			보호자 이메일 주소입력 받는 필드는 소액결제(핸드폰 , 전화결제)
			중에  14세 미만의 고객 결제시에 부모 이메일로 결제 내용통보하라는 정통부 권고 사항입니다. 
			다른 결제 수단을 이용시에는 해당 필드(parentemail)삭제 하셔도 문제없습니다.
-->  
<!-- 보호자 전자우편 -->
<input type="hidden" name="parentemail" size="20" value="parents@parents.com">
<!-- 이 동 전 화 -->
<input type="hidden" name="buyertel" size="20" value="<?php echo $phone[0]."-".$phone[1]."-".$phone[2];?>">


<!-- 기타설정 -->
<input type="hidden" name="currency" size="20" value="WON">

<!--
SKIN : 플러그인 스킨 칼라 변경 기능 - 5가지 칼라(ORIGINAL/BLUE중 택1, GREEN, YELLOW, RED, PURPLE )  기본/파랑, 녹색, 노랑, 빨강, 보라색
HPP : 컨텐츠 또는 실물 결제 여부에 따라 HPP(1)과 HPP(2)중 선택 적용(HPP(1):컨텐츠, HPP(2):실물).
Card(0): 신용카드 지불시에 이니시스 대표 가맹점인 경우에 필수적으로 세팅 필요 ( 자체 가맹점인 경우에는 카드사의 계약에 따라 설정) - 자세한 내용은 메뉴얼  참조.
OCB : OK CASH BAG 가맹점으로 신용카드 결제시에 OK CASH BAG 적립을 적용하시기 원하시면 "OCB" 세팅 필요 그 외에 경우에는 삭제해야 정상적인 결제 이루어짐.
no_receipt : 은행계좌이체시 현금영수증 발행여부 체크박스 비활성화 (현금영수증 발급 계약이 되어 있어야 사용가능)
-->
<input type="hidden" name="acceptmethod" size="20" value="HPP(1):Card(0):OCB:receipt:cardpoint">


<!--
상점 주문번호 : 무통장입금 예약(가상계좌 이체),전화결재 관련 필수필드로 반드시 상점의 주문번호를 페이지에 추가해야 합니다.
결제수단 중에 은행 계좌이체 이용 시에는 주문 번호가 결제결과를 조회하는 기준 필드가 됩니다.
상점 주문번호는 최대 40 BYTE 길이입니다.
주의:절대 한글값을 입력하시면 안됩니다.
-->
<input type="hidden" name="oid" size="40" value="<?php echo create_kingkong_order_number();?>">


<!--
플러그인 좌측 상단 상점 로고 이미지 사용
이미지의 크기 : 90 X 34 pixels
플러그인 좌측 상단에 상점 로고 이미지를 사용하실 수 있으며,
주석을 풀고 이미지가 있는 URL을 입력하시면 플러그인 상단 부분에 상점 이미지를 삽입할수 있습니다.
-->
<!--input type=hidden name=ini_logoimage_url  value="http://[사용할 이미지주소]"-->

<!--
좌측 결제메뉴 위치에 이미지 추가
이미지의 크기 : 단일 결제 수단 - 91 X 148 pixels, 신용카드/ISP/계좌이체/가상계좌 - 91 X 96 pixels
좌측 결제메뉴 위치에 미미지를 추가하시 위해서는 담당 영업대표에게 사용여부 계약을 하신 후
주석을 풀고 이미지가 있는 URL을 입력하시면 플러그인 좌측 결제메뉴 부분에 이미지를 삽입할수 있습니다.
-->
<!--input type=hidden name=ini_menuarea_url value="http://[사용할 이미지주소]"-->

<!--
플러그인에 의해서 값이 채워지거나, 플러그인이 참조하는 필드들
삭제/수정 불가
uid 필드에 절대로 임의의 값을 넣지 않도록 하시기 바랍니다.
-->
<input type="hidden" name="ini_encfield" value="<?php echo($inipay->GetResult("encfield")); ?>">
<input type="hidden" name="ini_certid" value="<?php echo($inipay->GetResult("certid")); ?>">
<input type="hidden" name="quotainterest" value="">
<input type="hidden" name="paymethod" value="">
<input type="hidden" name="cardcode" value="">
<input type="hidden" name="cardquota" value="">
<input type="hidden" name="rbankcode" value="">
<input type="hidden" name="reqsign" value="DONE">
<input type="hidden" name="encrypted" value="">
<input type="hidden" name="sessionkey" value="">
<input type="hidden" name="uid" value=""> 
<input type="hidden" name="sid" value="">
<input type="hidden" name="version" value="4000">
<input type="hidden" name="clickcontrol" value="">

