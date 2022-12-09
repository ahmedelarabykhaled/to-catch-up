<?php if ( ! defined( 'ABSPATH' ) ) exit; 
/*
	Plugin Name: jQuery Post Splitter
	Plugin URI: http://androidbubble.com/blog/wordpress/plugins/jquery-post-splitter
	Description: This plugin will split your post and pages into multiple pages with a tag. A button to split the pages and posts is vailable in text editor icons.
	Version: 2.9.5
	Author: Fahad Mahmood	
	Author URI: https://shop.androidbubbles.com/go
	Text Domain: wpjps
	Domain Path: /languages/	
	License: GPL3
*/
	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	

	
	global $jps_dir, $jps_premium_link, $jps_premium, $jps_title, $jps_title_v, $jps_headings, $jps_styles, $jps_data, $jps_url, $post, $jps_post_type;
	
	
	$jps_styles = array('elegent', 'robust', 'zipper', 'tablet', 'beach_bar', 'rubber', 'berg', 'chinese_lantern', 'spotlight', 'milano', 'yellow_paper');	
	asort($jps_styles);
	
	$post_id = (isset($_GET['post']) && isset($_GET['action']) && $_GET['action']=='edit' && is_numeric($_GET['post']) && $_GET['post']>0)?intval($_GET['post']):0;
	if(empty($post) && $post_id){
		$post = get_post($post_id);
	}
	
	$jps_post_type = ((is_object($post) && property_exists($post, 'post_type'))?$post->post_type:'');
	
	$is_related_page = (isset($_GET['page']) && $_GET['page']=='jpsoptions');

	$jps_dir = plugin_dir_path( __FILE__ );
    $jps_url = plugin_dir_url( __FILE__ );
	$jps_data = get_plugin_data(__FILE__);

	
	$jps_premium_scripts = realpath($jps_dir.'pro/jps-core-premium.php');
	$jps_premium = file_exists($jps_premium_scripts);

	$jps_title = ($jps_data['Name'].''.($jps_premium?' Pro':''));
	$jps_title_v = $jps_title.' ('.$jps_data['Version'].')';
	

	include_once(realpath($jps_dir.'inc/functions-essentials.php'));
	
	$plugin = plugin_basename(__FILE__); 
	add_filter("plugin_action_links_$plugin", 'jps_plugin_links' );			
	
	if(
			(is_admin() && !$is_related_page && !in_array($jps_post_type, array('post', 'page')))		
		&&
			!wp_doing_ajax()
	){
		//echo 'NOT ALLOWED';exit;
		return;
	}else{
		//echo 'ALLOWED';exit;
	}
	
		
	function jps_activate() {	

	}
	register_activation_hook( __FILE__, 'jps_activate' );
	

	$jps_headings = array();








	include_once(realpath($jps_dir.'inc/jps-style-data.php'));	



	if($jps_premium){


		include_once($jps_premium_scripts);



	}





	function jps_get($key, $direct = false){

		if(!$direct){
			$options = get_option('jps_options');
			//pre($options);
			$ret = (isset($options[$key])?$options[$key]:'');			
		}else{
			$ret = get_option($key);
			//pree($ret);
		}
		
		return $ret;
	}
	

	function next_prev_arr(){

		global $jps_premium;
		
		$arr = array();
	
	
		$next_text_default = __('Next', 'wpjps');
		$prev_text_default = __('Prev', 'wpjps');
		$sep_text_default = __('of', 'wpjps');
	
	
	

	
		
	
		$nav_style = $next_text = $prev_text = $sep_text = '';
	
		if($jps_premium){
	
	
			$nav_style = jps_get( 'nav_style' );
	
			$next_text = jps_get( 'next_text' );
	
			$prev_text = jps_get( 'prev_text' );	
			
			$sep_text = jps_get( 'sep_text' );	
	
		}
	
	
	
		$next_text = $next_text?$next_text:$next_text_default;
		$prev_text = $prev_text?$prev_text:$prev_text_default;	
		$sep_text = $sep_text?$sep_text:$sep_text_default;	
		
		$arr['next_text'] = $next_text;
		$arr['prev_text'] = $prev_text;
		$arr['sep_text'] = $sep_text;
		$arr['nav_style'] = $nav_style;
		
		
		return $arr;
		
			
	}

    function paged_post_link_pages($r) {
			//return;


            $arr = next_prev_arr();
            $next_text = $arr['next_text'];
            $prev_text = $arr['prev_text'];
            $nav_style = $arr['nav_style'];
			//pree($arr);
            $r = array(
                'next_or_number'	=> 'next',
                'nextpagelink'		=> '<span class="jps-next '.$nav_style.'">'.$next_text.'</span>','wpjps',
                'previouspagelink'	=> '<span class="jps-prev '.$nav_style.'">'.$prev_text.'</span>','wpjps',
                'echo' => 0,
                'before' => '',
                'separator'        => ' ',
                'link_before' => '',
                'link_after' => '',
                'after' => '',
            );

            return $r;

    }

	if(is_admin()){	
		

		$jps_premium_link = 'https://shop.androidbubbles.com/product/jquery-post-splitter-premium';//https://shop.androidbubble.com/products/wordpress-plugin?variant=36439507959963';//
		include_once(realpath($jps_dir.'inc/split-buttons.php'));
		include_once(realpath($jps_dir.'inc/jps-core-admin.php'));
		include_once(realpath($jps_dir.'inc/jps-functions-inner.php'));

		add_filter('mce_buttons', 'paged_post_tinymce');
		
		add_filter( 'plugin_row_meta', 'jpps_plugin_meta', 10, 2 );
		add_action('admin_enqueue_scripts', 'jps_admin_scripts');
		
		
		


	}else{

		include_once(realpath($jps_dir.'inc/jps-core-front.php'));

		if(jps_get('default_navigation') && $jps_premium){

            add_filter('wp_link_pages_args', 'paged_post_link_pages');

        }


		add_filter( 'the_content', 'paged_post_the_content_filter', 1);

		

		add_action( 'wp_enqueue_scripts', 'paged_post_scripts' );
		
		add_action( 'wp', 'paged_parts_logic');
		
		
	}
	