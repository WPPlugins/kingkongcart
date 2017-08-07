<?php

if($_POST['coupon_submit']){

	$coupon_kind 		= $_POST['coupon_kind'];
	$coupon_name 		= $_POST['coupon_name'];
	$coupon_discount 	= $_POST['coupon_discount'];
	$start_date 		= $_POST['start_date'];
	$end_date 			= $_POST['end_date'];
	$coupon_quantity 	= $_POST['coupon_quantity'];
	$added_product 		= $_POST['coupon_added_product'];
	$added_product_each = $_POST['added_product_each'];
	$products_add_kind	= $_POST['products_add_kind'];
	$coupon_image_url 	= $_POST['coupon_image_url'];
	$min_price 			= $_POST['min_price'];



	if($products_add_kind == "all"){
		$capability = "all";
	} else {
		$capability = "limit";
	}

	if($coupon_kind == "0"){
		echo "<div style='margin:10px 10px; background:#ffaaaa; padding:10px 10px; border-radius:5px'>쿠폰 종류를 선택 하셔야 합니다.</div>";
	} elseif(!$coupon_name){
		echo "<div style='margin:10px 10px; background:#ffaaaa; padding:10px 10px; border-radius:5px'>쿠폰명을 입력하셔야 합니다.</div>";
	} elseif(!$coupon_discount){
		echo "<div style='margin:10px 10px; background:#ffaaaa; padding:10px 10px; border-radius:5px'>할인금액(할인율)을 입력하셔야 합니다.</div>";
	} elseif(!$start_date or !$end_date){
		echo "<div style='margin:10px 10px; background:#ffaaaa; padding:10px 10px; border-radius:5px'>사용기간을 입력하셔야 합니다.</div>";
	} elseif(!$coupon_quantity){
		echo "<div style='margin:10px 10px; background:#ffaaaa; padding:10px 10px; border-radius:5px'>발급개수를 입력하셔야 합니다. 무제한 발급이라면 9999 를 입력하세요.</div>";
	} elseif($capability == "limit" and !$added_product_each){
		echo "<div style='margin:10px 10px; background:#ffaaaa; padding:10px 10px; border-radius:5px'>사용가능 상품을 등록하셔야 합니다.</div>";
	} else {
    global $wpdb; // Not sure if you need this, maybe

    $coupon_insert = array(
      'post_title'    => $coupon_name,
      'post_status'   => 'publish',
      'post_type'     => 'kkcart_coupon',
      'post_author'   => 1
    );
	$page_exists = get_page_by_title( $coupon_name, 'OBJECT', 'kkcart_coupon' );

	if($page_exists == null){
    	$insert = wp_insert_post( $coupon_insert );	
	} else {
		echo "<div style='margin:10px 10px; background:#ffaaaa; padding:10px 10px; border-radius:5px'>쿠폰명은 중복될 수 없습니다.</div>";
	}
    if($insert){

    	$coupon_insert_meta = array(
    		'capability'		=> $capability,
    		'coupon_kind' 		=> $coupon_kind,
    		'coupon_discount'	=> $coupon_discount,
    		'start_date'		=> $start_date,
    		'end_date'			=> $end_date,
    		'added_product'		=> $added_product,
    		'coupon_image_url'	=> $coupon_image_url,
    		'min_price'			=> $min_price
    	);

    	$coupon_insert_meta = serialize($coupon_insert_meta);
    	$added_product_each = serialize($added_product_each);

    	update_post_meta($insert, "coupon_detail", $coupon_insert_meta);
    	update_post_meta($insert, "coupon_count", $coupon_quantity);
    	update_post_meta($insert, "coupon_product", $added_product_each);

		$_POST['coupon_kind'] = "";
		$_POST['coupon_name'] = "";
		$_POST['coupon_discount'] = "";
		$_POST['start_date'] = "";
		$_POST['end_date'] = "";
		$_POST['coupon_quantity'] = "";
		$_POST['coupon_added_product'] = "";
		$_POST['added_product_each'] = "";
		$_POST['products_add_kind'] = "";	


    }
	}

}


$args = array(
	'post_type' => 'kkcart_coupon',
	'post_status' => 'publish'
	);

$coupons = new WP_Query($args);



?>
<ul>
	<li><span class="dashboard-content-title">쿠폰설정</span></li>
	<li>
		<span class="dashboard-content-subtitle">
		쿠폰 발행과 설정을 하실 수 있습니다. 쿠폰 발행시 종류와 할인금액(할인율) 설정을 신중히 선택하시기 바랍니다.
		</span>
	</li>
</ul>
<form id="coupon_form" method="post">
<div style="clear:both; padding-bottom:20px; border-bottom:1px dashed #e0e0e0">
<table style="padding-left:20px">
	<tr>
		<th>종류</th>
		<td colspan="3">
			<select name="coupon_kind">
				<option value="0">종류를 선택하세요.</option>
				<option value="1">무료배송쿠폰</option>
				<option value="2">정액제쿠폰</option>
				<option value="3">할인쿠폰</option>
			</select>
		</td>
		<td rowspan="6" style="vertical-align:middle; padding-left:20px">
			<input type="submit" name="coupon_submit" class="button button-primary" value="쿠폰등록" style="width:100px; height:70px">
		</td>
	</tr>
	<tr>
		<th>쿠폰명</th>
		<td>
			<input type="text" style="width:300px" name="coupon_name" value="<?php echo $coupon_name;?>">
		</td>
	</tr>
	<tr>
		<th>쿠폰이미지</th>
		<td style="vertical-align:middle">
			<div style="width:300px; min-height:80px; background:#e0e0e0">
				<div style="max-width:280px; margin:0 auto; text-align:center; padding-top:10px; position:relative" class="coupon_image">업로드 버튼을 눌러 등록하시기 바랍니다.</div>
				<div style="text-align:center; margin-top:10px; padding-bottom:10px"><input type="button" class="button" value="업로드" onclick="image_upload('background');"></div>
				<input type="hidden" name="coupon_image_url">
			</div>
		</td>
	</tr>
	<tr>
		<th>할인금액(할인율)</th>
		<td><input type="text" style="width:130px" name="coupon_discount" value="<?php echo $coupon_discount;?>"></td>
	</tr>
	<tr>
		<th>사용가능한도</th>
		<td><input type="text" style="width:130px" name="min_price" value="<?php echo $min_price;?>">원 이상 사용가능</td>
	</tr>
	<tr>
		<th>사용기간</th>
		<td><input type="text" style="width:100px;" id="start_date" name="start_date" value="<?php echo $start_date;?>"> ~ <input type="text" style="width:100px" id="end_date" name="end_date" value="<?php echo $end_date;?>"></td>
	</tr>
	<tr>
		<th>발급수</th>
		<td>
			<input type="text" style="width:80px" name="coupon_quantity" value="<?php echo $coupon_quantity;?>">
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle">사용가능상품</th>
		<td><input type="button" class="button find_products" value="상품찾기"><br>*상품찾기를 통해 선택하여 주시기 바랍니다.</td>
	</tr>
	<tr class="added_product_tr" style="display:none">
		<th>등록된 상품/카테고리</th>
		<td class="added_product_td"></td>
	</tr>
</table>
	<div class="find_product_area" style="margin:10px 30px; width:80%; padding:10px 10px; border:1px dashed green; display:none">
		<table>
			<tr>
				<th>등록 종류</th>
				<td>
					<select class="products_add_kind" name="products_add_kind">
						<option>선택하세요</option>
						<option value="all">모든상품에 사용</option>
						<option value="1">카테고리 등록</option>
						<option value="2">개별상품 등록</option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="products_add_area"></td>
			</tr>
		</table>
	</div>
</div>
</form>
<table style="width:100%">
	<tr style="height:40px; vertical-align:middle">
		<th>번호</th>
		<th>종류</th>
		<th>쿠폰명</th>
		<th>할인금액</th>
		<th>발급수</th>
		<th>사용기간</th>
		<th>옵션</th>
	</tr>
<?php

	if ( $coupons->have_posts() ) {
		$cnt = 1;
		while ( $coupons->have_posts() ){
			$coupons->the_post();

		$post_id = get_the_ID();

		$get_coupon = unserialize(get_post_meta($post_id, "coupon_detail", true));
		$coupon_count = get_post_meta($post_id, "coupon_count", true);

		switch($get_coupon['coupon_kind']){

			case "1" :
				$coupon_kind_name = "무료배송쿠폰";
			break;

			case "2" :
				$coupon_kind_name = "정액제쿠폰";
			break;

			case "3" :
				$coupon_kind_name = "할인쿠폰";
			break;

			default :
				$coupon_kind_name = "쿠폰등록에러";
			break;
		}
?>

	<tr>
		<td style="text-align:center"><?php echo $cnt;?></td>
		<td style="text-align:center"><?php echo $coupon_kind_name;?></td>
		<td><?php echo get_the_title($post_id);?></td>
		<td style="text-align:center"><?php echo $get_coupon['coupon_discount'];?></td>
		<td style="text-align:center"><?php echo $coupon_count;?></td>
		<td style="text-align:center"><?php echo $get_coupon['start_date'];?> ~ <?php echo $get_coupon['end_date'];?></td>
		<td style="text-align:center"><input type="button" class="button" value="삭제" onclick="remove_coupon(<?php echo $post_id;?>);"> <input type="button" class="button" value="수정" onclick="location.href='admin.php?page=kkcart_dashboard&dashboard_type=coupon_modi&coupon_id=<?php echo $post_id;?>';"></td>
	</tr>

<?php

		$cnt++;

		}
	} else {
?>
	<tr>
		<td colspan="7" style="text-align:center; color:gray">발행된 쿠폰이 없습니다.</td>
	</tr>
<?php
	}
?>
</table>
<script>

	jQuery(document).ready(function(){

		jQuery("[name=coupon_kind").change(function(){

			jQuery("[name=coupon_discount]").val("배송비 문의중...");
			if(jQuery(this).val() == "1"){

			  var data = {
			    'action': 'get_shipping_cost'
			  };

			  jQuery.post(ajaxurl, data, function(response) {
			  	jQuery("[name=coupon_discount]").val(response);
			  	jQuery("[name=coupon_discount]").prop("readonly", true);
			  });
				
			} else {
				jQuery("[name=coupon_discount]").val("");
				jQuery("[name=coupon_discount]").prop("readonly", false);
			}
		});

		jQuery("#start_date").datepicker({
			dateFormat: 'yy-mm-dd'
		});

		jQuery("#end_date").datepicker({
			dateFormat: 'yy-mm-dd'
		});

		jQuery("[name=coupon_kind]").val("<?php echo $coupon_kind;?>");

		if(jQuery("[name=coupon_kind]").val() == "1"){
			jQuery("[name=coupon_discount]").prop("readonly", true);
		}

		jQuery(".find_products").click(function(){
			jQuery(".find_product_area").show();
		});

		jQuery(".products_add_kind").change(function(){

			jQuery(".products_add_area").html("정보를 불러오고 있습니다. 잠시만 기다려주세요.");

			switch(jQuery(this).val()){

				case "1" : 	// 카테고리 등록

					var data = {
						'action' : 'get_products_category'
					};

					jQuery.post(ajaxurl, data, function(response) {
						jQuery(".products_add_area").html(response);
					});
					
				break;

				case "2" : 	// 개별상품 등록

					var data = {
						'action' : 'get_product_list'
					};

					jQuery.post(ajaxurl, data, function(response) {
						jQuery(".products_add_area").html(response);
					});

				break;

				case "all" :

					jQuery(".products_add_area").html("모든 상품에 해당쿠폰을 사용가능 하도록 설정 합니다.");

				break;

				default :
					jQuery(".products_add_area").html("등록 종류를 선택하세요.");
				break;
			}

		});


	});

	function add_product_into_coupon(kind, id, title){

		switch(kind){

			case "category" :

				var duplicate = 0;

				if(jQuery(".select_product_category").val() != "-1"){
					var selected_category = jQuery(".select_product_category").val();

					// each loop 를 통해 중복 여부를 체크
					jQuery(".added_product_each").each(function(i){
						if(jQuery(this).val() == "cat-"+selected_category){
							duplicate = 1;
						}
					});

					if(duplicate == 1){
						alert("이미 등록되어있는 카테고리 입니다.");
					} else {
					jQuery(".added_product_tr").show();
					jQuery(".added_product_td").append("<div id='cat-"+selected_category+"' style='margin-top:5px; padding:5px 5px; background:#e0e0e0; border-radius:5px; text-align:center; border:1px solid #cecece; position:relative'>Category:"+selected_category+"<div onclick=\"remove_added_list('cat',"+selected_category+")\" style='position:absolute; top:4px; right:10px; cursor:pointer'>X</div><input type='hidden' name='added_product_each[]' class='added_product_each' value='cat-"+selected_category+"'></div>");
					}
				}

			break;

			case "product" :

				var duplicate = 0;

				jQuery(".added_product_each").each(function(i){
					if(jQuery(this).val() == "prd-"+id){
						duplicate = 1;
					}
				});

				if(duplicate == 1){
					alert("이미 등록되어 있는 상품 입니다.");
				} else {
					jQuery(".added_product_tr").show();
					jQuery(".added_product_td").append("<div id='prd-"+id+"' style='margin-top:5px; padding:5px 5px; background:#e0e0e0; border-radius:5px; text-align:center; border:1px solid #cecece; position:relative'>Product:"+title+"<div onclick=\"remove_added_list('prd',"+id+")\" style='position:absolute; top:4px; right:10px; cursor:pointer'>X</div><input type='hidden' name='added_product_each[]' class='added_product_each' value='prd-"+id+"'></div>");
				}
			break;

		}

	}


	function remove_added_list(kind, id){
		jQuery("#"+kind+"-"+id).remove();
	}

	function remove_coupon(id){

		if(confirm("해당쿠폰을 정말로 삭제하시겠습니까?") == true){
			var data = {
				'action' : 'remove_coupon',
				'post_id' : id
			};

			jQuery.post(ajaxurl, data, function(response) {
				location.reload();
			});
		}


	}


	function image_upload(){
		wp.media.editor.send.attachment = function(props, attachment){
			jQuery(".coupon_image").html("<img src='"+attachment.url+"' style='max-width:280px; height:auto'><div class='remove_coupon_image' style='position:absolute; top:15px; right:10px; cursor:pointer' onclick='remove_coupon_image();'>삭제</div>");
			jQuery("[name=coupon_image_url]").val(attachment.url);
		}
		wp.media.editor.open(this);
		return false;
	}


	function remove_coupon_image(){
		jQuery(".coupon_image").html("업로드 버튼을 눌러 등록하시기 바랍니다.");
		jQuery("[name=coupon_image_url]").val("");
	}

































</script>