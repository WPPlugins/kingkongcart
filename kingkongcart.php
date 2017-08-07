<?php
 /*
 Plugin Name: kingkongcart
 Plugin URI: http://www.kingkongcart.com
 Description: 대한민국 대표 워드프레스 쇼핑몰 플러그인 킹콩카트 입니다.
 Version: 0.8.0
 Author: Bryan Lee
 Author URI: http://www.kingkongcart.com
 License: GPL2

 /* Copyright 2014 ithemeso (email : ithemeso@naver.com)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/
/**
 *
 * @package kingkongcart
 * @category Core
 * @author ithemeso 
 */

define(KINGKONG_CART_ID, get_option("kingkongcart_mypage_cart"));           // 장바구니 아이디
define(KINGKONGCART_ORDER, get_option("kingkongcart_order_page"));          // 주문하기 페이지 아이디
define(KINGKONG_MYPAGE, get_option("kingkongcart_mypage_mypage"));          // 마이페이지 아이디
define(KINGKONG_JOIN, get_option("kingkongcart_join_member"));              // 회원가입 아이디
define(KINGKONG_WISH, get_option("kingkongcart_mypage_wish"));              // 위시리스트 아이디
define(KINGKONG_BROKE, get_option("kingkongcart_mypage_broke"));            // 회원탈퇴 페이지 ID
define(KINGKONG_PROFILE, get_option("kingkongcart_mypage_profile"));        // 내정보수정 아이디
define(KINGKONG_ORDER, get_option("kingkongcart_mypage_order"));            // 주문내역 아이디
define(KINGKONG_POLICY, get_option("kingkongcart_policy"));                 // 이용약관 아이디
define(KINGKONG_PRIVACY, get_option("kingkongcart_privacy"));               // 개인정보취급방침 아이디
define(KINGKONG_MAIN, get_option("kingkongcart_main"));                     // 메인페이지 아이디
define(KINGKONG_LOGIN, get_option("kingkongcart_login"));                   // 킹콩카트 로그인 아이디 
define(KINGKONG_KEY, get_option("kingkongcart_update_key"));                // 킹콩카트 업데이트 키
define(KINGKONG_ORDER_RESULT, get_option("kingkongcart_order_result"));     // 주문완료 페이지 아이디
define(KINGKONG_NOTUSER_REGIST, get_option("kingkongcart_notuser_regist")); // 비회원 주문확인 아이디
define(MILEAGE_TEXT, "적립금");
define(CURRENCY_TEXT, "원");
define(KINGKONGCART_ABSPATH, plugin_dir_path(__FILE__));            // 플러그인 절대경로
define(KINGKONGCART_PLUGINS_URL, plugins_url('', __FILE__));            // 플러그인 상대경로
define(KINGKONGCART_PRODUCT_PREFIX, get_option("kingkongcart_product_prefix")); //제품번호 프리픽스
define(KINGKONGCART_ORDER_PREFIX, get_option("kingkongcart_order_prefix"));     //주문번호 프리픽스
define(KINGKONGCART_BOARD_PATH, plugins_url('', __FILE__)."/includes/board/skin/"); // board 경로

require_once 'ajax/kingkongcart-ajax.php';									                  // AJAX
require_once 'core/admin/kingkong-core.php';       							              // Core Setting Functions
require_once 	'core/admin/meta-functions/kkcart_product-thumbnail.php';	      // 썸네일 이미지 등록 메타필드
require_once	'core/admin/meta-functions/kkcart_product_meta_price.php';	    // 판매가격 설정 메타필드
require_once	'core/admin/meta-functions/kkcart_post-save-action.php';	      // 포스트 세이브 할때 메타 정보 저장
require_once  'core/class/class.kingkong_setup.php';                          // kingkongcart setup
require_once  'core/class/class.kingkong_db_setup.php';                       // kingkongcart db setup
require_once  'core/class/class.kingkong_create_page.php';                    // kingkongcart create page
require_once  'core/class/class.menuWalker.php';                              // 메뉴 Custom Walker
require_once  'core/class/class.kingkongcart.php';                            // 장바구니 Class
require_once  'core/class/class.kingkong_product.php';                        // 상품정보 Class
require_once  'core/class/class.Mobile_Detect.php';                           // Mobile Detect Class
require_once  'core/class/class.order_insert.php';                            // 주문정보 저장 class
require_once 'includes/admin/dashboard.php';								                  // Admin Dashboard 
require_once 'includes/admin/balance.php';                                    // Admin 정산관리
require_once 'includes/admin/quantity.php';                                   // Admin 재고관리
require_once 'includes/admin/board-manage.php';                               // Admin 문의관리
require_once 'includes/admin/admin-order.php';                                // Admin Order List
require_once 'functions/functions.php';										                    // kingkongcart functions
require_once 'functions/ajax-functions.php';                                  // AJAX
require_once 'includes/product-loop.php';									                    // Product Loop
require_once 'includes/mypage.php';											                      // 마이페이지(장바구니,프로필,위시리스트 등등)
require_once 'includes/join-member.php';                                      // 회원가입
require_once 'includes/order.php';                                            // 주문하기 페이지
require_once 'includes/board/qna.php';                                        // 게시판:Q&A 숏코드
require_once 'includes/board/afternote.php';                                  // 게시판:이용후기 숏코드
require_once 'includes/coupon.php';                                           // 쿠폰 리스트 숏코드


