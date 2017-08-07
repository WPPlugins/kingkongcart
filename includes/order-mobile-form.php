<script>
    
    addEventListener("load", function()
    {
        setTimeout(updateLayout, 0);
    }, false);
 
    var currentWidth = 0;
    
    function updateLayout()
    {
        if (window.innerWidth != currentWidth)
        {
            currentWidth = window.innerWidth;
 
            var orient = currentWidth == 320 ? "profile" : "landscape";
            document.body.setAttribute("orient", orient);
            setTimeout(function()
            {
                window.scrollTo(0, 1);
            }, 100);            
        }
    }
 
    setInterval(updateLayout, 400);
    
window.name = "BTPG_CLIENT";

var width = 330;
var height = 480;
var xpos = (screen.width - width) / 2;
var ypos = (screen.width - height) / 2;
var position = "top=" + ypos + ",left=" + xpos;
var features = position + ", width=320, height=440";
var date = new Date();
var date_str = "testoid_"+date.getFullYear()+""+date.getMinutes()+""+date.getSeconds();
if( date_str.length != 16 )
{
    for( i = date_str.length ; i < 16 ; i++ )
    {
        date_str = date_str+"0";
    }
}
function setOid()
{
    //document.ini.P_OID.value = ""+date_str;
}

function on_app()
{
       	var order_form = document.ini;
		var paymethod;
		if(order_form.paymethod.value == "wcard")
			paymethod = "CARD";
		else if(order_form.paymethod.value == "mobile")
			paymethod = "HPP";
		else if(order_form.paymethod.value == "vbank")
			paymethod = "VBANK";
		else if(order_form.paymethod.value == "culture")
			paymethod = "CULT";
		else if(order_form.paymethod.value == "hpmn")
			paymethod = "HPMN";	

       	param = "";
       	param = param + "mid=" + order_form.P_MID.value + "&";
       	param = param + "oid=" + order_form.P_OID.value + "&";
       	param = param + "price=" + order_form.P_AMT.value + "&";
       	param = param + "goods=" + order_form.P_GOODS.value + "&";
       	param = param + "uname=" + order_form.P_UNAME.value + "&";
       	param = param + "mname=" + order_form.P_MNAME.value + "&";
       	param = param + "mobile=000-111-2222" + order_form.P_MOBILE.value + "&";
       	param = param + "paymethod=" + paymethod + "&";
       	param = param + "noteurl=" + order_form.P_NOTI_URL.value + "&";
       	param = param + "ctype=1" + "&";
       	param = param + "returl=" + "&";
       	param = param + "email=" + order_form.P_EMAIL.value;
		var ret = location.href="INIpayMobile://" + encodeURI(param);
}

function on_web()
{	
	//alert('on_web');
	var order_form = document.ini;
	var paymethod = jQuery("[name=paymethod]").val();
	var wallet = window.open("", "BTPG_WALLET", features);
	<!--
	if (wallet == null) 
	{
		if ((webbrowser.indexOf("Windows NT 5.1")!=-1) && (webbrowser.indexOf("SV1")!=-1)) 
		{    // Windows XP Service Pack 2
			alert("팝업이 차단되었습니다. 브라우저의 상단 노란색 [알림 표시줄]을 클릭하신 후 팝업창 허용을 선택하여 주세요.");
		} 
		else 
		{
			alert("팝업이 차단되었습니다.");
		}
		return false;
	}
	-->
	var p_uname 		= jQuery("[name=P_UNAME]").val();					// 구매자명
	var p_mobile 		= jQuery("[name=P_MOBILE]").val();					// 연락처
	var p_email 		= jQuery("[name=P_EMAIL]").val();					// 이메일주소
	var postcode1 		= jQuery("[name=postcode1]").val();					// 우편번호1
	var postcode2 		= jQuery("[name=postcode2]").val();					// 우편번호2
	var basic_address 	= jQuery("[name=address]").val();					// 기본주소
	var else_address 	= jQuery("[name=else_address]").val();				// 나머지주소
	var shipping_name 	= jQuery("[name=shipping_name]").val();				// 수취자명
	var shipping_tel 	= jQuery("[name=shipping_tel1]").val();				// 수취자 연락처
	var input_memo 		= jQuery("[name=input_memo]").val();				// 배송메모
	var privacy_check 	= jQuery("[name=privacy_check]").prop("checked"); 	// 개인정보보호동의

	if(!p_uname){
		alert("구매자 명을 입력하세요.");
		jQuery("[name=P_UNAME]").focus();
		return false;
	} else if(!p_mobile){
		alert("구매자 연락처를 입력하세요.");
		jQuery("[name=P_MOBILE]").focus();
		return false;
	} else if(!p_email){
		alert("구매자 이메일 주소를 입력하세요.");
		jQuery("[name=P_EMAIL]").focus();
		return false;
	} else if(!postcode1){
		alert("우편번호를 검색버튼을 통해 입력하세요.");
		jQuery("[name=postcode1]").focus();
		return false;
	} else if(!postcode2){
		alert("우편번호를 검색버튼을 통해 입력하세요.");
		jQuery("[name=postcode2]").focus();
		return false;
	} else if(!basic_address){
		alert("우편번호 검색을 통해 주소를 입력하세요.");
		jQuery("[name=postcode1]").focus();
		return false;
	} else if(!shipping_tel){
		alert("받으시는분의 이름을 입력하세요.");
		jQuery("[name=shipping_tel1]").focus();
		return false;
	} else if(!privacy_check){
		alert("개인정보 보호정책에 동의하셔야 결제가 가능합니다.");
		jQuery("[name=privacy_check]").focus();
		return false;
	} else {
			order_form.target = "BTPG_WALLET";
			order_form.action = "https://mobile.inicis.com/smart/" + paymethod + "/";
			order_form.submit();
	}
	

}

function onSubmit()
{
	var order_form = document.ini;
	var inipaymobile_type = jQuery("[name=inipaymobile_type]").val();
	if( inipaymobile_type == "app" )
		return on_app();
	else if( inipaymobile_type == "web" )
		return on_web();
}

</script>
<div name="payment_loading" id="payment_loading" style="display:none; position:absolute; top:0px; left:0px; width:100%">
	<div style="position:fixed; top:45%; width:100%; text-align:center; z-index:999; color:#fff; font-size:20px; font-weight:bold">결제정보를 처리중입니다.<br>잠시만 기다려주세요.</div>
	<div style="position:fixed; top:0px; width:100%; height:100%; background:#000; opacity:0.6; z-index:998"></div>
</div>
<form id="form1" name="ini" id="ini" method="post" action="" accept-charset="euc-kr" >
<div id="kingkongcart-order-mobile" style="padding:0px 5px">
	<h3 style="font-size:18px; margin-bottom:10px">상품정보</h3>
<?php
	for ($i=0; $i < count($cart); $i++) {

		$product_id 		= $cart[$i]['product_id'];
		$option1 			= $cart[$i]['first']['name'];
		$option1_plus_price = $cart[$i]['first']['plus_price'];
		$option2			= $cart[$i]['second']['name'];
		$option2_plus_price = $cart[$i]['second']['plus_price'];
		$quantity			= $cart[$i]['quantity'];

		$product_info 		= new kingkong_product($product_id);
		$product_kind 		= $product_info->kind;

		$each_total_mileage = $product_info->mileage_price * $quantity;
		$each_total_price	= ($product_info->result_price * $quantity) + ($option1_plus_price * $quantity) + ($option2_plus_price * $quantity);

 		$thumbnail_ids = unserialize(get_post_meta($product_id,"kingkongcart_added_thumbnail_id", true));
		$thumbnail_url = wp_get_attachment_image_src($thumbnail_ids[0],'thumbnail');
?>
	<ul style="margin:0px; padding:5px 10px; list-style:none; height:110px; border-top:1px solid #e0e0e0; position:relative">
		<li style="float:left"><img src="<?php echo $thumbnail_url[0];?>" style="width:100px; height:auto"></li>
		<li style="float:left; margin-left:10px">
			<ul style="margin:0; padding:0; list-style:none">
				<li style="font-size:16px"><?php echo $product_info->title;?></li>
<?php 
	if($option1){ 
?>
				<li>옵션 1: <?php echo $option1;?> (추가금액 <?php echo number_format($option1_plus_price);?>원)</li>
<?php 
	}

	if($option2){
?>
				<li>옵션 2: <?php echo $option2;?> (추가금액 <?php echo number_format($option2_plus_price);?>원)</li>
<?php
	}
?>
				<li>수량 : <?php echo $quantity;?></li>
				<li style="font-size:14px"><STRONG><?php echo number_format($product_info->result_price * $quantity);?></STRONG>원</li>
			</ul>
		</li>
		<li style="position:absolute; right:10px; top:45px; background:gray; padding:0px 5px; color:#fff" onclick="kingkongcart_remove_cart(<?php echo $i;?>);">삭제</li>
	</ul>
<?php
		$total_price 	+= $each_total_price;
		$total_mileage 	+= $each_total_mileage;
	}

session_start();
$_SESSION['mobile_temp_cart'] = $cart;
$shipping_cost = check_shipping_cost($total_price);

if($shipping_cost == 0){
	$shipping_cost = "무료배송";
	$shipping_cost_data = 0;
	$with_shipping_price = $total_price - sanitize_text_field( $_POST['using_mileage'] );
	$shipping_cost_title = $shipping_cost;
} else {
	$shipping_cost_data = $shipping_cost;
	$with_shipping_price = $total_price + $shipping_cost - sanitize_text_field( $_POST['using_mileage'] );
	$shipping_cost = number_format($shipping_cost).$currency_text;
	$shipping_cost_title = $shipping_cost." 포함";
}

?>
	<ul class="total_price_table" style="display:table; top:0px; width:100%; z-index:999; background:#fff; margin:0px; padding:5px 10px; list-style:none; border-bottom:1px solid #e0e0e0; border-top:1px solid #e0e0e0">
		<li style="float:right; font-size:18px">총 구매금액 : <?php echo number_format($with_shipping_price);?> 원</li>
		<li style="clear:both; float:right;"><span style="color:#da2128; font-weight:bold">+</span> 배송비 : <?php echo $shipping_cost_title;?></li>
<?php
	if($_POST['using_mileage']){
?>
		<li style="clear:both; float:right;padding-top:5px"><span style="color:#da2128; font-weight:bold">+</span> 적립금 <?php echo number_format($_POST['using_mileage']);?>원 사용 <span style="font-size:11px; background:gray; color:#fff; padding:3px 5px; position:relative; top:-2px" onclick="location.href='';">사용취소</span></li>
<?php
	}
?>
		
	</ul>
	<h3 style="font-size:18px; margin-bottom:10px; margin-top:15px"><STRONG>주문자 정보</STRONG></h3>
	<ul style="margin:0px; padding:5px 10px; list-style:none">
		<li>
			<ul style="margin:0; padding:0; list-style:none">
				<li style="font-size:16px">주문하시는 분</li>
				<li><input type="text" name="P_UNAME" class="order_name" style="width:100%; height:40px; font-size:18px" value="<?php echo $user_display_name;?>" placeholder="주문자 성함을 입력하세요."></li>
			</ul>
			<ul style="margin:10px 0px 0px 0px; padding:0; list-style:none">
				<li style="font-size:16px">연락처</li>
				<li><input type="number" name="P_MOBILE" style="width:100%; height:40px; font-size:18px" value="<?php echo $phone1;?>" placeholder="주문자 연락처를 입력하세요."></li>
			</ul>
			<ul style="margin:10px 0px 0px 0px; padding:0; list-style:none">
				<li style="font-size:16px">이메일</li>
				<li><input type="text" name="P_EMAIL" style="width:100%; height:40px; font-size:18px" value="<?php echo $user_email;?>" placeholder="이메일주소를 입력하세요."></li>
			</ul>
		</li>
	</ul>
	<h3 style="font-size:18px; margin-top:20px; margin-bottom:10px"><STRONG>배송지 정보</STRONG></h3>
	<ul style="margin:0px; padding:5px 10px; list-style:none">
		<li>
			<ul style="margin:0; padding:0; list-style:none">
				<li><input type="checkbox" style="width:30px; height:30px" name="equal_info_mobile"> <span style="position:relative; top:-10px; font-size:16px">주문자와 동일</span></li>
			</ul>
			<ul style="margin:10px 0px 0px 0px; padding:0; list-style:none">
				<li style="font-size:16px">배송지 주소</li>
				<li><input type="text" style="width:30%; height:40px; font-size:18px" name="postcode1" value="<?php echo $zipcode1;?>" readonly>-<input type="text" style="width:30%; height:40px; font-size:18px" name="postcode2" value="<?php echo $zipcode2;?>" readonly> <button type="button" style="width:30%; height:40px;" onclick="showDaumPostcode();">우편번호검색</button></li>
				<li style="margin-top:10px">
					<div id="layer" style="display:none;border:5px solid;position:relative;width:100%;height:400px;left:0px;top:0%;overflow:hidden"><img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px" onclick="closeDaumPostcode()"></div>
				</li>
			</ul>
			<ul style="margin:10px 0px 0px 0px; padding:0; list-style:none">
				<li style="font-size:16px">기본주소</li>
				<li><input type="text" style="width:100%; height:40px; font-size:18px" name="address" class="basic_address" placeholder="기본주소를 입력하세요." value="<?php echo $address_jibun;?>" readonly></li>
			</ul>
			<ul style="margin:10px 0px 0px 0px; padding:0; list-style:none">
				<li style="font-size:16px">나머지주소</li>
				<li><input type="text" style="width:100%; height:40px; font-size:18px" name="else_address" placeholder="상세주소를 입력하세요." value="<?php echo $address_else;?>"></li>
			</ul>
			<ul style="margin:10px 0px 0px 0px; padding:0; list-style:none">
				<li style="font-size:16px">받으시는 분</li>
				<li><input type="text" style="width:100%; height:40px; font-size:18px" name="shipping_name" placeholder="받으시는분 성함을 입력하세요."></li>
			</ul>
			<ul style="margin:10px 0px 0px 0px; padding:0; list-style:none">
				<li style="font-size:16px">연락처</li>
				<li><input type="number" name="shipping_tel1" class="receive_tel" style="width:100%; height:40px; font-size:18px" placeholder="받으시는분 연락처를 입력하세요."></li>
			</ul>
			<ul style="margin:10px 0px 0px 0px; padding:0; list-style:none">
				<li style="font-size:16px">배송메모</li>
				<li><textarea name="input_memo" style="width:100%; height:80px; font-size:18px" placeholder="남기실 말씀을 입력하세요."></textarea></li>
			</ul>
		</li>
	</ul>
	<h3 style="font-size:18px; margin-top:20px; margin-bottom:10px"><STRONG>적립금 사용</STRONG></h3>
	<ul style="margin:0px; padding:5px 10px; list-style:none">
		<li>
			<ul style="margin:0; padding:0; list-style:none">
				<li style="font-size:16px">사용할 적립금</li>
				<li><input type="number" name="input_mileage" value="<?php echo $_POST['using_mileage'];?>" style="width:50%; height:40px; font-size:18px" placeholder="사용할 금액을 입력하세요."> <button type="button" style="width:45%; height:40px;" onclick="use_mileage(<?php echo $user_id;?>);">적립금 사용</button></li>
				<li>보유적립금 : <?php echo number_format($kingkong_mileage);?>원</li>
			</ul>
		</li>
	</ul>
	<h3 style="font-size:18px; margin-top:20px; margin-bottom:10px"><STRONG>개인정보 보호정책</STRONG></h3>
	<ul style="margin:0px; padding:5px 10px; list-style:none">
		<li>
			<ul style="margin:0; padding:0; list-style:none">
				<li style="font-size:16px">개인정보 보호정책 <div onclick="window.open('<?php echo get_the_permalink(KINGKONG_PRIVACY);?>');" style="float:right; min-width:50px; padding:0px 5px; height:auto; font-size:11px; background:#da2128; color:#fff; text-align:center">+ 크게보기</div></li>
				<li><textarea style="width:100%; height:80px;"><?php echo get_post_field('post_content',KINGKONG_PRIVACY);?></textarea></li>
			</ul>
			<ul style="margin:10px 0px 0px 0px; padding:0; list-style:none">
				<li><input type="checkbox" name="privacy_check" style="width:30px; height:30px"> <span style="position:relative; top:-10px; font-size:16px">개인정보 보호정책에 동의합니다.</span></li>
			</ul>
		</li>
	</ul>
	<h3 style="font-size:18px; margin-top:20px; margin-bottom:10px"><STRONG>결제수단</STRONG></h3>
	<ul style="margin:0px; padding:5px 10px; list-style:none">
		<li>
			<ul style="margin:0; padding:0; list-style:none">
				<li><input type="radio" name="payment_method" value="wcard" style="width:30px; height:30px"> <span style="position:relative; top:-10px; font-size:16px">신용카드</span></li>
				<li><input type="radio" name="payment_method" value="bank" style="width:30px; height:30px"> <span style="position:relative; top:-10px; font-size:16px">계좌이체</span></li>
				<li><input type="radio" name="payment_method" value="vbank" style="width:30px; height:30px"> <span style="position:relative; top:-10px; font-size:16px">가상계좌</span></li>
				<li><input type="radio" name="payment_method" value="mobile" style="width:30px; height:30px"> <span style="position:relative; top:-10px; font-size:16px">휴대폰결제</span></li>
				<li><input type="radio" name="payment_method" value="culture" style="width:30px; height:30px"> <span style="position:relative; top:-10px; font-size:16px">문화상품권</span></li>
				<li><input type="radio" name="payment_method" value="hpmn" style="width:30px; height:30px"> <span style="position:relative; top:-10px; font-size:16px">해피머니상품권</span></li>
			</ul>
		</li>
	</ul>
	<ul style="margin:0px; padding:5px 10px; list-style:none">
		<button type="button" style="width:100%; height:40px; font-size:16px;" onclick="onSubmit();">결제진행</button>
		<button type="button" style="width:100%; height:40px; font-size:16px; margin-top:5px">결제취소</button>
	</ul>
</div>
<?php
	$product_order_code = create_kingkong_order_number();
?>
<input type="hidden" name="P_MID" value="<?php echo $inicis_key_id;?>"> 
<input type="hidden" name="P_NEXT_URL" value="<?php echo KINGKONGCART_PLUGINS_URL."/includes/order_result-mobile-post.php";?>">
<input type="hidden" name="P_RETURN_URL" value="<?php echo KINGKONGCART_PLUGINS_URL."/includes/order_result-mobile-post.php?oid=".$product_order_code;?>">
<input type="hidden" name="P_NOTI_URL" value="<?php echo KINGKONGCART_PLUGINS_URL."/payment/INICIS/mx_rnoti.php";?>">
<input type="hidden" name="P_HPP_METHOD" value="1">
<input type="hidden" name="paymethod">
<input type="hidden" name="inipaymobile_type" value="web">
<input type="hidden" name="all_product_id" value="<?php echo $all_product_id;?>">

<input type="hidden" name="without_shipping_cost" value="<?php echo $total_price;?>">

<!--주문번호-->
<input type="hidden" name="P_OID" id="textfield2" style="border-color:#cdcdcd; border-width:1px; border-style:solid; color:#555555; height:15px;" value="<?php echo $product_order_code;?>" />
<!--상품명-->
<input type="hidden" name="P_GOODS" value="<?php echo $payment_insert_title;?>" id="textfield3" style="border-color:#cdcdcd; border-width:1px; border-style:solid; color:#555555; height:15px;"/>
<!--가격-->
<input type="hidden" name="P_AMT" value="<?php echo $with_shipping_price;?>" id="textfield4" style="border-color:#cdcdcd; border-width:1px; border-style:solid; color:#555555; height:15px;"/>
<!--상점이름-->
<input type="hidden" name="P_MNAME" value="<?php wp_title();?>" id="textfield6" style="border-color:#cdcdcd; border-width:1px; border-style:solid; color:#555555; height:15px;"/>
<input type="hidden" name="P_RESERVED" value="twotrs_isp=Y&block_isp=Y">
</form>

<form method="post" id="use_mileage_form">
	<input type="hidden" name="using_coupon_capability" value="<?php echo sanitize_text_field( $_POST['using_coupon_capability'] );?>">
	<input type="hidden" name="using_coupon_kind" value="<?php echo sanitize_text_field( $_POST['using_coupon_kind'] );?>">
	<input type="hidden" name="using_coupon_discount" value="<?php echo sanitize_text_field( $_POST['using_coupon_discount'] );?>">
	<input type="hidden" name="using_mileage" value="<?php echo sanitize_text_field( $_POST['using_mileage'] );?>">
	<input type="hidden" name="without_shipping_cost" value="<?php echo $total_price;?>">
	<input type="hidden" name="will_using_coupon_id" value="<?php echo $_POST['will_using_coupon_id'];?>">
</form>

<form method="post" id="pay_result" name="pay_result">

	<input type="hidden" name="price" value="<?php echo $with_shipping_price;?>">
	<input type="hidden" name="tel" value="">
	<input type="hidden" name="email" value="<?php echo $user_email;?>">
	<input type="hidden" name="product_title" value="<?php echo $payment_insert_title;?>">
	<input type="hidden" name="address" value="">
	<input type="hidden" name="postcode1" value="">
	<input type="hidden" name="postcode2" value="">
	<input type="hidden" name="receive_else_address" value="">
	<input type="hidden" name="receive_name">
	<input type="hidden" name="uname" value="<?php echo $user_display_name;?>">
	<input type="hidden" name="receive_tel" value="">
	<input type="hidden" name="result_status">
	<input type="hidden" name="pay_method_type">
	<input type="hidden" name="P_RMESG1">
	<input type="hidden" name="P_RMESG2">
	<input type="hidden" name="memo">
	<input type="hidden" name="product_code" value="<?php echo $product_order_code;?>">
	<input type="hidden" name="shipping_cost" value="<?php echo $shipping_cost;?>">
	<input type="hidden" name="total_mileage" value="<?php echo $total_mileage;?>">
	<input type="hidden" name="using_mileage" value="<?php echo sanitize_text_field( $_POST['using_mileage'] );?>">
</form>
<script>
	jQuery(document).ready(function(){
		jQuery(function(){
				var original_position = jQuery(".total_price_table").position().top;
			jQuery(window).scroll(function(){
				if(jQuery(this).scrollTop() >= original_position ){
					//jQuery(".total_price_table").css("position","fixed");
				} else {
					//jQuery(".total_price_table").css("position","relative");
				}
			});
		});

		jQuery("[name=P_UNAME]").change(function(){
			jQuery("#pay_result").find("[name=uname]").val(jQuery(this).val());
		});

		jQuery("[name=P_MOBILE]").change(function(){
			jQuery("#pay_result").find("[name=tel]").val(jQuery(this).val());
		})

		jQuery("[name=shipping_name]").change(function(){
			jQuery("#pay_result").find("[name=receive_name]").val(jQuery(this).val());
		});

		jQuery("[name=else_address]").change(function(){
			jQuery("#pay_result").find("[name=receive_else_address]").val(jQuery(this).val());
		});

		jQuery(".receive_tel").change(function(){
			jQuery("#pay_result").find("[name=receive_tel]").val(jQuery(this).val());
		});

		jQuery("[name=input_memo]").change(function(){
			jQuery("#pay_result").find("[name=memo]").val(jQuery(this).val());
		});

		jQuery("[name=payment_method]").change(function(){
			jQuery("[name=paymethod]").val(jQuery(this).val());
			jQuery("#pay_result").find("[name=pay_method_type]").val(jQuery(this).val());
		});
	});
</script>