<?php

	if(!function_exists('pre')){
		function pre($data){
			if(isset($_GET['debug'])){
				pree($data);
			}
		}	 
	} 
		
	if(!function_exists('pree')){
	function pree($data){
				echo '<pre>';
				print_r($data);
				echo '</pre>';	
		
		}	 
	} 
	
	if(!function_exists('jpps_add_options')){
		function jpps_add_options() {
			global $jps_title;			
			add_options_page($jps_title.' '.__('Settings','wpjps'), $jps_title, 'manage_options', 'jpsoptions', 'jpps_options_page');	
			$jps_title = trim(str_replace('jQuery', '', $jps_title));
			add_submenu_page('edit.php', $jps_title, $jps_title, 'manage_options', 'jpsoptions', 'jpps_options_page' );
			$jps_title = trim(str_replace('Post', 'Page', $jps_title));
			add_submenu_page('edit.php?post_type=page', $jps_title, $jps_title, 'manage_options', 'jpsoptions', 'jpps_options_page' );
		}	
		add_action('admin_menu', 'jpps_add_options');
	}
	if(!function_exists('jps_plugin_links')){
		function jps_plugin_links($links) { 	
			global $jps_premium_link, $jps_premium;	
			$settings_link = '<a href="options-general.php?page=jpsoptions">'.__('Settings','wpjps').'</a>';	
			if($jps_premium){
				array_unshift($links, $settings_link); 
			}else{	
				$jps_premium_link = '<a href="'.esc_url($jps_premium_link).'" title="'.__('Go Premium','wpjps').'" target="_blank">'.__('Go Premium','wpjps').'</a>'; 
				array_unshift($links, $settings_link, $jps_premium_link); 	
			}	
			return $links; 	
		}		
	}
	if(!function_exists('jps_underscore')){
		function jps_underscore($str) { 	
			$str = str_replace(array(' ', '-'), '_', strtolower($str));
			return $str;
		}
	}
	