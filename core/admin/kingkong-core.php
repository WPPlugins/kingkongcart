<?php
/*
*  File name : Core functions
*/

add_action( 'admin_menu', 'register_kkcart_menu_page' );                                                // Register menu items
add_action( 'init', 'create_kkcart_custom_post' );                                                      // 커스텀포스트 정의
add_filter('manage_edit-kkcart_product_columns', 'add_kkcart_product_columns');                         // 컬럼 추가
add_action('manage_kkcart_product_posts_custom_column', 'manage_kkcart_product_columns', 10, 2);        // 커스텀 포스트 컬럼 추가
add_action("admin_init", "kkcart_product_meta_init");                                                   // 메타필드 등록
add_action('post_edit_form_tag', 'add_post_enctype');                                                   // 인코딩 타입 변경
add_action("template_redirect", 'kingkongcart_redirect');                                               // Page Redirection
add_action('admin_enqueue_scripts', 'kkcart_admin_style');                                              // 어드민 스타일 등록
add_action('wp_enqueue_scripts', 'kkcart_style');                                                                  // Front-End 스타일 등록







/* 어드민 바 관리자 아닐 시 숨기기 **************************************************************************/

add_action('set_current_user', 'kingkong_hide_admin_bar'); 

function kingkong_hide_admin_bar() { 
    if (!current_user_can('manage_options')) { 
        show_admin_bar(false); 
    } 
}





/* 로그아웃 후 리다이렉트 **********************************************************************************/

function admin_kingkong_login_page() {

    if(is_user_logged_in()){
        if(!current_user_can('manage_options')){
            wp_redirect(get_the_permalink(KINGKONG_LOGIN), 301);
            exit();
        } else {
            site_url();
            exit();
        }
    } else {
        wp_redirect(get_the_permalink(KINGKONG_LOGIN), 301);
        exit();
    }
}

add_filter('login_redirect', 'admin_kingkong_login_page',10, 3);


/* 인풋 처리를 위한 Form 전송 인코딩 타입을 multipartform-data 로 변환 ****************************************/

function add_post_enctype() {
    echo ' enctype="multipart/form-data"'; //files 인풋 처리를 위한 form 전송 인코딩 타입 변환
}





/* 킹콩카트 어드민 Style sheet 등록 ************************************************************************/

function kkcart_admin_style() {
    wp_enqueue_media();
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
    wp_enqueue_style('kingkongcart-admin-style', KINGKONGCART_PLUGINS_URL."/files/css/admin/kingkongcart-admin.css" );
    wp_enqueue_script('iris', KINGKONGCART_PLUGINS_URL."/files/js/admin/iris.min.js", array('jquery'));
    wp_enqueue_script('kingkongcart-admin-js', KINGKONGCART_PLUGINS_URL."/files/js/admin/kingkongcart-admin.js", array('jquery'));
}






/* 킹콩카트 Style & JS 등록 ******************************************************************************/

function kkcart_style() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('kingkongcart-elevateZoom', KINGKONGCART_PLUGINS_URL."/files/js/elevateZoom-3.0.8.min.js");
    wp_enqueue_style('kingkongcart-style', KINGKONGCART_PLUGINS_URL."/files/css/kingkongcart.css");
    wp_enqueue_script('kingkongcart-js', KINGKONGCART_PLUGINS_URL."/files/js/kingkongcart.js", array('jquery'));
    wp_enqueue_script('postcode', 'http://dmaps.daum.net/map_js_init/postcode.js');
    wp_localize_script('kingkongcart-js', 'ajax_kingkongcart', 
                array(
                            'ajax_url' => admin_url('admin-ajax.php')
                        ));
}






/* 킹콩카트 어드민 메뉴 설정 ********************************************************************************/

function register_kkcart_menu_page(){


    $new_order = get_order_count(1);
    $bubble_style = " <span class='update-plugins count-$new_order'><span class='update-count'>".$new_order."</span></span>";

    add_menu_page( 'kingkong', __('킹콩카트','kingkong').$bubble_style, 'administrator', 'kkcart_dashboard', 'kkcart_dashboard', KINGKONGCART_PLUGINS_URL.'/files/images/kingkong.svg', '25.4' ); 
    add_submenu_page('kkcart_dashboard', 'kingkong', __('대시보드','kingkong'), 'administrator', 'kkcart_dashboard'); 
    add_submenu_page('kkcart_dashboard', 'kingkong', __('주문관리','kingkong').$bubble_style, 'administrator', 'kkcart_order', 'kkcart_order');
    add_submenu_page('kkcart_dashboard', 'kingkong', __('정산관리','kingkong'), 'administrator', 'kkcart_balance', 'kkcart_balance');
    add_submenu_page('kkcart_dashboard', 'kingkong', __('재고관리','kingkong'), 'administrator', 'kkcart_quantity', 'kkcart_quantity');
    add_submenu_page('kkcart_dashboard', 'kingkong', __('문의관리','kingkong'), 'administrator', 'kkcart_board', 'kkcart_board');

    //add_menu_page( 'kingkongshop', __('킹콩 SHOP','kingkong'), 'administrator', 'kkcart_shop', 'kkcart_shop', KINGKONGCART_PLUGINS_URL.'/files/images/kingkong.svg', '25.3');
}




/* 커스텀 포스트 정의 **************************************************************************************/

function create_kkcart_custom_post() {

    register_post_type( 'kkcart_product',
        array(
            'labels' => array(
                'name' => __( '킹콩-상품관리' ),
                'singular_name' => __( '상품관리' ),
                'add_new' => __('상품 등록'),
                'add_new_item' => __( '상품 등록하기' )
            ),
        'menu_icon' => KINGKONGCART_PLUGINS_URL.'/files/images/kingkong.svg',
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'has_archive' => true,
        'menu_position' => 25.5
        )
    );

    register_post_type( 'kkcart_coupon',
        array(
            'labels' => array(
                'name' => __( '킹콩카드-쿠폰' ),
                'singuler_name' => __( '쿠폰관리' ),
                'add_new' => __('쿠폰등록'),
                'add_new_item' => __( '쿠폰 등록하기' )
            ),
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false
        )
    );

   register_taxonomy('section','kkcart_product',
    array(
        'public' => true,
        'hierarchical' => true,
        'label' => '상품 카테고리',
        'query_var' => true,
        'rewrite' => array('slug' => 'section', 'with_front' => false),
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'menu_position' => 2
        )
    );

}






/* 상품등록 메타필드 구성 ****************************************************************************************/

function kkcart_product_meta_init(){
    add_meta_box("kkcart_product_meta_thumnail", "킹콩카트 상품정보 설정", "kkcart_product_meta_thumnail", "kkcart_product", "normal", "low");
    register_taxonomy_for_object_type('section', 'kkcart_product'); 
    register_taxonomy_for_object_type('post_tag', 'kkcart_product');
}   






/* 상품관리 커스텀 컬럼 생성 **************************************************************************************/

function add_kkcart_product_columns($serial_list_columns) {
    $new_columns['cb'] = '<input type="checkbox" />';   
    $new_columns['thumbnail'] = __('대표썸네일');
    $new_columns['title'] = _x('제품명', 'column name');
    $new_columns['price'] = __('판매가격');
    $new_columns['discount_rate'] = __('할인율');
    $new_columns['discount_price'] = __('할인가격');
    $new_columns['mileage_rate'] = __('적립률');
    $new_columns['mileage_price'] = __('적립금');
    $new_columns['date'] = _x('등록일', 'column name');
 
    return $new_columns;
}




/* 상품 카테고리 페이지 숏코드 삽입  *********************************************************************************/


add_filter("manage_edit-section_columns", 'kingkong_category_columns'); 
 
function kingkong_category_columns($theme_columns) {
    $new_columns = array(
        'cb' => '<input type="checkbox" />',
        'name' => __('Name'),
        'header_icon' => '',
        'shortcode' => '페이지 숏코드',
        'slug' => __('Slug'),
        'posts' => __('Posts')
        );
    return $new_columns;
}


add_filter("manage_section_custom_column", 'kingking_category_insert_columns', 10, 3);
 
function kingking_category_insert_columns($out, $column_name, $theme_id) {
    $theme = get_term($theme_id, 'section');
    switch ($column_name) {
        case 'shortcode': 
                $out =  "[kingkong_product_loop cat=".$theme_id."]";
            break;
 
        default:
            break;
    }
    return $out;    
}




/* 상품 리스트 (커스텀포스트) 컬럼 등록 *****************************************************************************/

function manage_kkcart_product_columns($column_name, $id) {
    global $wpdb;

    $info = unserialize(get_post_meta($id, 'kingkongcart-product-info', true));

    switch ($column_name) {

    case 'thumbnail':
        $added_thumbnail_ids = get_post_meta($id, 'kingkongcart_added_thumbnail_id', true);
        $each_thumb_id = unserialize($added_thumbnail_ids);
        $thumbnail_url = wp_get_attachment_thumb_url($each_thumb_id[0]);
        echo "<a href='post.php?post=".$id."&action=edit'><img src='".$thumbnail_url."' style='width:80px; height:auto; padding:10px 10px; background:#fff; border:1px solid #e8e8e8'></a>";
        break;

    case 'price':

        if($info[1]){
            echo number_format($info[1]);
        } else {
            echo number_format($info[0]);
        }
        
        break;

    case 'discount_rate':

        if($info[2]){
            echo $info[2]."%";
        }else {
            echo "없음";
        }
        break;

    case 'discount_price':

        if($info[3]){
            echo number_format($info[3]);
        }else {
            echo "없음";
        }
        break;

    case 'mileage_rate':

        if($info[4]){
            echo $info[4]."%";
        }else {
            echo "없음";
        }
        break;

    case 'mileage_price':

        if($info[5]){
            echo number_format($info[5]);
        }else {
            echo "없음";
        }
        break;

    } // end switch
}






/* 상품 싱글페이지 리다이렉션  *****************************************************************************/

function kingkongcart_redirect() {
    global $wp, $post;
    $plugindir = dirname( __FILE__ );

    if ( ($wp->query_vars["post_type"] == 'kkcart_product') and (sanitize_text_field( $_POST['status_type'] )) == false ) {

        $templatefilename = 'single-product.php';

        if (file_exists(TEMPLATEPATH . '/' . "kingkongcart-".$templatefilename)) {
            $return_template = TEMPLATEPATH . '/' . "kingkongcart-".$templatefilename;

        } else {
            $return_template = KINGKONGCART_ABSPATH . '/templates/' . $templatefilename;
        }

        do_kingkongcart_redirect($return_template);
    }

    if ( ($wp->query_vars["post_type"] == 'kkcart_product') and (sanitize_text_field( $_POST['status_type'] )) == 'qna' ) {

        $templatefilename = 'board-qna-write.php';

        if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
            $return_template = TEMPLATEPATH . '/' . $templatefilename;

        } else {
            $return_template = KINGKONGCART_ABSPATH . '/templates/' . $templatefilename;
        }

        do_kingkongcart_redirect($return_template);
    }

    if ( ($wp->query_vars["post_type"] == 'kkcart_product') and (sanitize_text_field( $_POST['status_type'] )) == 'afternote' ) {

        $templatefilename = 'board-afternote-write.php';

        if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
            $return_template = TEMPLATEPATH . '/' . $templatefilename;

        } else {
            $return_template = KINGKONGCART_ABSPATH . '/templates/' . $templatefilename;
        }

        do_kingkongcart_redirect($return_template);
    }

    if ( ($post->ID == KINGKONGCART_ORDER) AND (sanitize_text_field( $_POST['status_type'] ) == "order_result") ) {

        $templatefilename = 'order_result.php';

        if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
            $return_template = TEMPLATEPATH . '/' . $templatefilename;

        } else {
            $return_template = KINGKONGCART_ABSPATH . '/includes/' . $templatefilename;
        }

        do_kingkongcart_redirect($return_template);
    }

    if ( ($post->ID == KINGKONG_ORDER_RESULT) AND (sanitize_text_field( $_POST['status_type'] ) == "order_result") ) {

        $templatefilename = 'order_result_private.php';

        if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
            $return_template = TEMPLATEPATH . '/' . $templatefilename;

        } else {
            $return_template = KINGKONGCART_ABSPATH . '/includes/' . $templatefilename;
        }

        do_kingkongcart_redirect($return_template);
    }

    if ( ($post->ID == KINGKONG_LOGIN) ) {

        $templatefilename = 'login.php';

        if(!is_user_logged_in()){
            if (file_exists(TEMPLATEPATH . '/' . "page-".$templatefilename)) {
                $return_template = TEMPLATEPATH . '/' . "page-".$templatefilename;
            } else {
                    $return_template = KINGKONGCART_ABSPATH . '/templates/' . $templatefilename;
            }
        } else {
                wp_redirect(home_url());
        }
        do_kingkongcart_redirect($return_template);
    }

    if ( ($post->ID == KINGKONG_NOTUSER_REGIST) ){

        $templatefilename = 'kingkong_notuser_regist.php';

        if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
            $return_template = TEMPLATEPATH . '/' . $templatefilename;

        } else {
            $return_template = KINGKONGCART_ABSPATH . '/templates/' . $templatefilename;
        }

        do_kingkongcart_redirect($return_template);
    }
        
    if ( ($_GET['page_type'] == "download") && ($_GET['product_id'] != "") ) {

        $templatefilename = 'product_download.php';



        $return_template = $plugindir . '/includes/' . $templatefilename;
        $return_template = str_replace("/core/admin", "", $return_template);


        do_kingkongcart_redirect($return_template);
    }
}

function do_kingkongcart_redirect($url) {
    global $post, $wp_query;
    if (have_posts()) {
        include($url);
        die();
    } else {
        $wp_query->is_404 = true;
    }
}





/* 마지막 로그인 시간을 체크하기 위한 hooking *************************************/

add_action('wp_login', 'set_kingkong_last_login');
 
function set_kingkong_last_login($login) {
   $user = get_userdatabylogin($login);
   update_usermeta( $user->ID, 'last_login', current_time('mysql') );
}



function add_dashboard_widgets() {
    if (current_user_can( 'manage_options' )) {
        add_meta_box('id', '킹콩카트 알림판', 'kingkongcart_dashboard_notice', 'dashboard', 'normal', 'high');
    }
}

add_action('wp_dashboard_setup', 'add_dashboard_widgets' );


/* 킹콩카트 관리자 모드 메인 대시보드 알림판 ************************************/

function kingkongcart_dashboard_notice(){
?>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ko_KR/sdk.js#xfbml=1&appId=327060404112194&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div>
    <table>
        <tr>
            <th style='color:#e70033;'>신규주문</th>
            <td style="width:100px"><a href="admin.php?page=kkcart_order&order_type=new-order"><b><?php echo get_order_count(1);?></b> 건</a></td>
            <td rowspan="3">
                <table>
                    <tr>
                        <td><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/kingkong-big.png" style="width:40px; height:auto"></td>
                        <td style="padding-left:10px">
                            페이스북 공식 페이지 좋아요를 눌러주세요~<br>
                            최신 업데이트 소식과 글들을 받아보실 수 있습니다.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <th style='color:#f85a7a;'>배송대기</th>
            <td><a href="admin.php?page=kkcart_order&order_type=shipping-ready"><?php echo get_order_count(2);?> 건</a></td>
        </tr>
        <tr>
            <th style='color:#fe94ab;'>입금대기</th>
            <td><a href="admin.php?page=kkcart_order&order_type=pending"><?php echo get_order_count(0);?> 건</a></td>
        </tr>
        <tr>
            <th></th>
            <td></td>
            <td colspan="2" style="text-align:center"><div class="fb-like" data-href="https://facebook.com/kingkongcart" data-width="350px" data-layout="button" data-action="like" data-show-faces="true" data-share="true"></div></td>
        </tr>
        <tr>
            <td colspan="4" style="padding-top:10px">*킹콩카트는 <b>ithemeso (<a href="http://www.ithemeso.com" target="_blank">www.ithemeso.com</a>)</b> 에서 제작되었습니다.</td>
        </tr>
        <tr>
            <td colspan="4">쇼핑몰 기술지원/개발/파트너쉽 문의 : <a href="mailto:ithemeso@naver.com">ithemeso@naver.com</a></td>
        </tr>
        <tr>
            <td colspan="4">기술장애 및 피드백 : <a href="http://www.ithemeso.com" target="_blank">www.ithemeso.com</a></td>
        </tr>
        <tr>
            <td colspan="4">
                <table>
                    <tr>
                        <td style="padding-right:10px;text-align:center;"><img src="<?php echo KINGKONGCART_PLUGINS_URL;?>/files/images/inicis_logo.png" style="width:110px; height:auto;"><br>
                            <a href="http://landing.inicis.com/landing/application/application01_2.php?cd=hostinglanding&product=kingkongcart" target="_blank"><input type="button" class="button button-primary" value="온라인 신청" style="margin-top:10px"></a><br>
                            <a href="http://www.inicis.com/service_application_02.jsp" target="_blank"><input type="button" class="button" value="이용안내" style="margin-top:5px"></a>
                        </td>
                        <td>
                            <table style="border:1px solid #e0e0e0;">
                                <tr>
                                    <td rowspan="2" style="width:80px; text-align:center; vertical-align:middle; border-right:1px solid #e0e0e0">수수료</td>
                                    <td style="width:80px; border-right:1px solid #e0e0e0">신용카드</td>
                                    <td>3.4%(부가세별도,업계최저수수료)</td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle; border-right:1px solid #e0e0e0; border-top:1px solid #e0e0e0">계좌이체</td>
                                    <td style="border-top:1px solid #e0e0e0">1.8%(최저200원, 부가세별도)</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="vertical-align:middle; text-align:center; border-right:1px solid #e0e0e0; border-top:1px solid #e0e0e0">초기등록비</td>
                                    <td style="border-top:1px solid #e0e0e0">20만원</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="vertical-align:middle; text-align:center; border-right:1px solid #e0e0e0; border-top:1px solid #e0e0e0">연관리비</td>
                                    <td style="border-top:1px solid #e0e0e0">면제</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="vertical-align:middle; text-align:center; border-right:1px solid #e0e0e0; border-top:1px solid #e0e0e0">보증보험</td>
                                    <td style="border-top:1px solid #e0e0e0">면제<br>
                                        (정확한 면제 가능여부는 온라인 신청 후 심사 후에 결정)
                                        면제관련 문의는 PG사로 해주세요
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
        


</div>

















<?php
}
?>