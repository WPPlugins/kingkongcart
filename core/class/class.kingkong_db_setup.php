<?php

	class kingkong_db_setup {

		function __construct(){
			register_activation_hook( KINGKONGCART_PLUGINS_URL."/kingkongcart.php", array($this,'kingkongcart_active_create_db') );
			register_activation_hook( KINGKONGCART_PLUGINS_URL."/kingkongcart.php", array($this,'kingkongcart_active_create_db_meta') );
			register_activation_hook( KINGKONGCART_PLUGINS_URL."/kingkongcart.php", array($this,'kingkongcart_create_board_db') );
			register_activation_hook( KINGKONGCART_PLUGINS_URL."/kingkongcart.php", array($this,'kingkongcart_create_board_meta') );
		}

/**

TITLE : 주문 리스트 DB 테이블 생성 (kingkong_order)

*/

		public function kingkongcart_active_create_db() {

		    global $wpdb;

		    //create the name of the table including the wordpress prefix (wp_ etc)
		    $order_table = $wpdb->prefix . "kingkong_order";
		    //$wpdb->show_errors(); 
		    
		    //check if there are any tables of that name already
		    if($wpdb->get_var("show tables like '$order_table'") !== $order_table) 
		    {
		        //create your sql
		        $sql =  "CREATE TABLE ". $order_table . " (
		                      ID mediumint(12) NOT NULL AUTO_INCREMENT,
		                      pid VARCHAR(20) NOT NULL,
		                      status mediumint(9),
		                      kind VARCHAR (20) NOT NULL, 
		                      pname text(20) NOT NULL,
		                      order_id VARCHAR(20) NOT NULL,
		                      order_price int NOT NULL,
		                      receive_name text NOT NULL,
		                      receive_contact VARCHAR(20) NOT NULL,
		                      address_doro TEXT NOT NULL,
		                      address_jibun TEXT NOT NULL,
		                      address_detail TEXT NOT NULL,
		                      mktime VARCHAR(2) NOT NULL,
		                      order_date VARCHAR(20) NOT NULL,
		                      UNIQUE KEY ID (ID));";
		    }
		    
		    //include the wordpress db functions
		    require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		    dbDelta($sql);
		    
		    //register the new table with the wpdb object
		    if (!isset($wpdb->stats)) 
		    {
		        $wpdb->stats = $order_table; 
		        //add the shortcut so you can use $wpdb->stats
		        $wpdb->tables[] = str_replace($wpdb->prefix, '', $order_table); 
		    }
		}


/**

TITLE : 주문 메타 DB 테이블 생성 (kingkong_order_meta)

*/

		public function kingkongcart_active_create_db_meta() {

		    global $wpdb;

		    //create the name of the table including the wordpress prefix (wp_ etc)
		    $meta_table = $wpdb->prefix . "kingkong_order_meta";
		    //$wpdb->show_errors(); 
		    
		    //check if there are any tables of that name already
		    if($wpdb->get_var("show tables like '$meta_table'") !== $meta_table) 
		    {
		        //create your sql
		        $sql =  "CREATE TABLE ". $meta_table . " (
		                      meta_id bigint(20) NOT NULL AUTO_INCREMENT,
		                      order_id bigint(20) NOT NULL,
		                      meta_key VARCHAR(255),
		                      meta_value longtext,
		                      UNIQUE KEY meta_id (meta_id));";
		    }
		    
		    //include the wordpress db functions
		    require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		    dbDelta($sql);
		    
		    //register the new table with the wpdb object
		    if (!isset($wpdb->stats)) 
		    {
		        $wpdb->stats = $meta_table; 
		        //add the shortcut so you can use $wpdb->stats
		        $wpdb->tables[] = str_replace($wpdb->prefix, '', $meta_table); 
		    }

		}


/**

TITLE : 이용후기/문의글 게시판 DB 테이블 생성 (kingkong_board)

*/

		public function kingkongcart_create_board_db() {

		    global $wpdb;

		    //create the name of the table including the wordpress prefix (wp_ etc)
		    $order_table = $wpdb->prefix . "kingkong_board";
		    //$wpdb->show_errors(); 
		    
		    //check if there are any tables of that name already
		    if($wpdb->get_var("show tables like '$order_table'") !== $order_table) 
		    {
		        //create your sql
		        $sql =  "CREATE TABLE ". $order_table . " (
		                      ID mediumint(12) NOT NULL AUTO_INCREMENT,
		                      pid mediumint(12) NOT NULL,
		                      kind VARCHAR (20) NOT NULL,
		                      type VARCHAR (20) NOT NULL,
		                      title VARCHAR (200) NOT NULL, 
		                      content text NOT NULL,
		                      writer text NOT NULL,
		                      user mediumint(12) NOT NULL,
		                      date VARCHAR(20) NOT NULL,
		                      UNIQUE KEY ID (ID));";
		    }
		    
		    //include the wordpress db functions
		    require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		    dbDelta($sql);
		    
		    //register the new table with the wpdb object
		    if (!isset($wpdb->stats)) 
		    {
		        $wpdb->stats = $order_table; 
		        //add the shortcut so you can use $wpdb->stats
		        $wpdb->tables[] = str_replace($wpdb->prefix, '', $order_table); 
		    }
		}


/**

TITLE : 이용후기/문의글 게시판 DB 메타 테이블 생성 (kingkong_board_meta)

*/

		public function kingkongcart_create_board_meta() {

		    global $wpdb;

		    //create the name of the table including the wordpress prefix (wp_ etc)
		    $meta_table = $wpdb->prefix . "kingkong_board_meta";
		    //$wpdb->show_errors(); 
		    
		    //check if there are any tables of that name already
		    if($wpdb->get_var("show tables like '$meta_table'") !== $meta_table) 
		    {
		        //create your sql
		        $sql =  "CREATE TABLE ". $meta_table . " (
		                      meta_id bigint(20) NOT NULL AUTO_INCREMENT,
		                      order_id bigint(20) NOT NULL,
		                      meta_key VARCHAR(255),
		                      meta_value longtext,
		                      UNIQUE KEY meta_id (meta_id));";
		    }
		    
		    //include the wordpress db functions
		    require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		    dbDelta($sql);
		    
		    //register the new table with the wpdb object
		    if (!isset($wpdb->stats)) 
		    {
		        $wpdb->stats = $meta_table; 
		        //add the shortcut so you can use $wpdb->stats
		        $wpdb->tables[] = str_replace($wpdb->prefix, '', $meta_table); 
		    }

		}

	}

	if(is_admin()){
		new kingkong_db_setup();
	}

?>