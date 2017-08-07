<?php

	if($_POST['coupon_modify']){


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
	$coupon_id 			= $_POST['coupon_id'];
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


	$page_exists = get_page_by_title( $coupon_name, 'OBJECT', 'kkcart_coupon' );

	if($page_exists == null or $coupon_name == get_the_title($coupon_id)){

    	$coupon_modify = array(
    		'ID'			=> $coupon_id,
  			'post_title' 	=> $coupon_name
    	);

    	wp_update_post( $coupon_modify );

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

    	update_post_meta($coupon_id, "coupon_detail", $coupon_insert_meta);
    	update_post_meta($coupon_id, "coupon_count", $coupon_quantity);
    	update_post_meta($coupon_id, "coupon_product", $added_product_each);

	} else {
		echo "<div style='margin:10px 10px; background:#ffaaaa; padding:10px 10px; border-radius:5px'>쿠폰명은 중복될 수 없습니다.</div>";
	}

	}

	}



	$post_id = $_GET['coupon_id'];
	$get_coupon = unserialize(get_post_meta($post_id, "coupon_detail", true));
	$coupon_count = get_post_meta($post_id, "coupon_count", true);
	$coupon_product = unserialize(get_post_meta($post_id, "coupon_product", true));
		
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

	$coupon_image_url = $get_coupon['coupon_image_url'];

?>
<ul>
	<li><span class="dashboard-content-title">쿠폰수정</span></li>
	<li>
		<span class="dashboard-content-subtitle">
		해당 쿠폰의 설정을 변경시에는 전체수량과 금액 설정에 유의 하시기 바랍니다.
		</span>
	</li>
</ul>
<form method="post">
<input type="hidden" name="coupon_id" value="<?php echo $post_id;?>">
<table style="padding-left:20px">
	<tr>
		<th>종류</th>
		<td>
			<select name="coupon_kind">
				<option value="0">종류를 선택하세요.</option>
				<option value="1">무료배송쿠폰</option>
				<option value="2">정액제쿠폰</option>
				<option value="3">할인쿠폰</option>
			</select>
		</td>
	</tr>
	<tr>
		<th>쿠폰명</th>
		<td>
			<input type="text" style="width:300px" name="coupon_name" value="<?php echo get_the_title($post_id);?>">
		</td>
	</tr>
	<tr>
		<th>쿠폰이미지</th>
		<td style="vertical-align:middle">
			<div style="width:300px; min-height:80px; background:#e0e0e0">
				<div style="max-width:280px; margin:0 auto; text-align:center; padding-top:10px; position:relative" class="coupon_image">
<?php
	if($coupon_image_url){
?>
					<img src="<?php echo $coupon_image_url;?>" style="max-width:280px; height:auto"><div class='remove_coupon_image' style='position:absolute; top:15px; right:10px; cursor:pointer' onclick='remove_coupon_image();'>삭제</div>
<?php
	} else {
?>
					업로드 버튼을 눌러 등록하시기 바랍니다.
<?php
	}
?>
					
				</div>
				<div style="text-align:center; margin-top:10px; padding-bottom:10px"><input type="button" class="button" value="업로드" onclick="image_upload('background');"></div>
				<input type="hidden" name="coupon_image_url" value="<?php echo $coupon_image_url;?>">
			</div>
		</td>
	</tr>
	<tr>
		<th>할인금액(할인율)</th>
		<td><input type="text" style="width:130px" name="coupon_discount" value="<?php echo $get_coupon['coupon_discount'];?>"></td>
	</tr> 
	<tr>
		<th>사용가능한도</th>
		<td><input type="text" style="width:130px" name="min_price" value="<?php echo $get_coupon['min_price'];?>">원 이상 사용가능</td>
	</tr>
	<tr>
		<th>사용기간</th>
		<td><input type="text" style="width:100px;" id="start_date" name="start_date" value="<?php echo $get_coupon['start_date'];?>"> ~ <input type="text" style="width:100px" id="end_date" name="end_date" value="<?php echo $get_coupon['end_date'];?>"></td>
	</tr>
	<tr>
		<th>발급수</th>
		<td>
			<input type="text" style="width:80px" name="coupon_quantity" value="<?php echo $coupon_count;?>">
		</td>
	</tr>
	<tr>
		<th style="vertical-align:middle">사용가능상품</th>
		<td><input type="button" class="button find_products" value="상품찾기"><br>*상품찾기를 통해 선택하여 주시기 바랍니다.</td>
	</tr>
<?php

	if($get_coupon['capability'] == "all"){
?>
	<tr class="added_product_tr">
		<th>등록된 상품/카테고리</th>
		<td class="added_product_td" style="height:50px;">모든 상품에 사용</td>
	</tr>
<?php
	} else {
?>
	<tr class="added_product_tr">
		<th>등록된 상품/카테고리</th>
		<td class="added_product_td" style="height:50px;">
<?php

	for ($i=0; $i < count($coupon_product); $i++) {

		$product_title = $coupon_product[$i];
		$product_explode = explode("-",$coupon_product[$i]);
		$product_prefix  = $product_explode[0];
		$product_id 	 = $product_explode[1];

		switch($product_prefix){
			case "prd" :
				$long_prefix = "Product";
				$result_id 	 = get_the_title($product_id);
			break;

			case "cat" :
				$long_prefix = "Category";
				$result_id 	 = $product_id;
			break;
		}
?>
	
		<div id="<?php echo $product_prefix;?>-<?php echo $product_id;?>" style='margin-top:5px; padding:5px 5px; background:#e0e0e0; border-radius:5px; text-align:center; border:1px solid #cecece; position:relative'><?php echo $long_prefix;?>:<?php echo $result_id;?><div onclick="remove_added_list('<?php echo $product_prefix;?>',<?php echo $product_id;?>)" style='position:absolute; top:4px; right:10px; cursor:pointer'>X</div><input type='hidden' name='added_product_each[]' class='added_product_each' value="<?php echo $product_prefix;?>-<?php echo $product_id;?>"></div>

<?php
	}

?>
		</td>
	</tr>
<?php
	}
?>


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

<input type="button" class="button" value="이전화면" style="margin-left:30px; margin-top:30px" onclick="history.back();"> <input type="button" class="button" value="삭제" style="margin-top:30px" onclick="remove_coupon(<?php echo $post_id;?>);"> <input type="submit" name="coupon_modify" class="button button-primary" value="쿠폰수정" style="margin-top:30px">
</form>
<script>
	jQuery(document).ready(function(){
		jQuery("[name=coupon_kind]").val("<?php echo $get_coupon['coupon_kind'];?>");

		if(jQuery("[name=coupon_kind]").val() == "1"){
			jQuery("[name=coupon_discount]").prop("readonly", true);
		}

		jQuery("#start_date").datepicker({
			dateFormat: 'yy-mm-dd'
		});

		jQuery("#end_date").datepicker({
			dateFormat: 'yy-mm-dd'
		});

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

	function remove_coupon(id){

		if(confirm("해당쿠폰을 정말로 삭제하시겠습니까?") == true){
			var data = {
				'action' : 'remove_coupon',
				'post_id' : id
			};

			jQuery.post(ajaxurl, data, function(response) {
				location.href='admin.php?page=kkcart_dashboard&dashboard_type=coupon';
			});
		}
	}

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