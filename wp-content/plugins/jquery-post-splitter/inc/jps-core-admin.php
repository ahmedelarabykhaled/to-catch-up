<?php if ( ! defined( 'ABSPATH' ) ) exit; 
	
	function jps_pro_feature(){
		global $jps_premium;
		
		if(!$jps_premium){
?>
<small class="jps-pro-feature"><i class="fas fa-flag"></i> <?php _e('Premium Feature','wpjps'); ?></small>
<?php		
		}
	}

	function sanitize_jps_data( $input ) {
		if(is_array($input)){		
			$new_input = array();	
			foreach ( $input as $key => $val ) {
				$new_input[ $key ] = (is_array($val)?sanitize_jps_data($val):stripslashes(sanitize_text_field( $val )));
			}			
		}else{
			$new_input = stripslashes(sanitize_text_field($input));			
			if(stripos($new_input, '@') && is_email($new_input)){
				$new_input = sanitize_email($new_input);
			}
			if(stripos($new_input, 'http') || wp_http_validate_url($new_input)){
				$new_input = esc_url($new_input);
			}			
		}	
		return $new_input;
	}
					
	function jps_theme_add_editor_styles() {
		add_editor_style( plugins_url( 'css/jps-admin.css?t='.time() , dirname(__FILE__)) );
	}
	add_action( 'admin_init', 'jps_theme_add_editor_styles' );
	
	function jps_add_meta_box() {
	
	
	
		global $jps_title;
	
		
	
		$screens = array( 'post' );
	
	
	
		foreach ( $screens as $screen ) {
	
	
	
			add_meta_box(
	
				'jps_metabox_id',
	
				$jps_title, 
	
				'jps_metabox_callback',
	
				$screen
	
			);
	
		}
	
	}
	
	add_action( 'add_meta_boxes', 'jps_add_meta_box', 1 );
	
	
	
	
	
	function jps_metabox_callback( $post ) {
	
		
	
		global $jps_premium;
	
		
	
		// Add a nonce field so we can check for it later.
	
		wp_nonce_field( 'jps_save_meta_box_data', 'jps_meta_box_nonce' );
	
	
	
		/*
	
		 * Use get_post_meta() to retrieve an existing value
	
		 * from the database and use the value for the form.
	
		 */
	
		$value = get_post_meta( $post->ID, 'jps_nav_type', true );
	
	
	
		$opt_name = 'jps_nav_type_'.$post->ID;
	
	
	
	
	
		echo '<p>'.__('You can select a different navigation type for each post.','wpjps').' '.__('This setting will override the default','wpjps').' <a href="options-general.php?page=jpsoptions" target="_blank">'.__('settings','wpjps').'</a>.</p>';
	
	
	
		jps_nav_method($value, $opt_name);
	
		
	
		jps_go_premium();
	
        ?>


        <?php
	
	}
	
	
	
	
	
	
	
	//Add Settings Link To Plugins Page
	
	
	
	function jpps_plugin_meta($links, $file) {
	
	
	
		$plugin = plugin_basename(__FILE__);
	
	
	
		// create link
	
	
	
		if ($file == $plugin) {
	
	
	
			return array_merge(
	
	
	
				$links,
	
	
	
				array( sprintf( '<a href="options-general.php?page=jpsoptions">'.__('Settings','wpjps').'</a>', $plugin, __('Settings','wpjps') ) )
	
	
	
			);
	
	
	
		}
	
	
	
		return $links;
	
	
	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	

	
	
	
	function jpps_options_page() {
	
	
	
		
	
	
	
		global $jps_title, $jps_title_v, $jps_premium_link, $jps_premium;
	
		$hidden_field_name = 'jpps_submit_hidden';
	
	
		if(isset($_POST[$hidden_field_name]) && $_POST[$hidden_field_name] == 'Y' ) {
	
			if ( 
				! isset( $_POST['jps_nonce_action_field'] ) 
				|| ! wp_verify_nonce( $_POST['jps_nonce_action_field'], 'jps_nonce_action' ) 
			) {
			
			   _e('Sorry, your nonce did not verify.','wpjps');
			   exit;
			
			} else {

			   // process form data
				if(isset($_POST['jps_options'])){				
					update_option('jps_options', sanitize_jps_data($_POST['jps_options']));
				}
				if(isset($_POST['jps_restrictions'])){				
					update_option('jps_restrictions', sanitize_jps_data($_POST['jps_restrictions']));
				}
				//pree($_POST);
				//pree(jps_get('jps_restrictions'));
				//pree(get_option('jps_restrictions'));
				//exit;
				
	
			
				if($jps_premium && function_exists('jpps_pro_options_page')){
					jpps_pro_options_page();
				}				   
			}	
	
	
		
	
	
		}
	

	
	
	
		//Options Form
	
		include_once('jps-settings.php');

	
	}



	function jps_go_premium(){

		global $jps_premium, $jps_premium_link, $jps_styles;



		if(!$jps_premium): ?>



   <a href="<?php echo esc_url($jps_premium_link); ?>" target="_blank" class="jps_premium_link"><?php _e('Go Premium','wpjps'); ?></a>
	<?php if(!empty($jps_styles)){ ?>
	<ul title="<?php _e('Click here to preview','wpjps'); ?>" class="jps_styles_preview small">
    <?php foreach($jps_styles as $style){ ?>
    <li><strong><?php echo ucwords(str_replace('_', ' ', $style)); ?></strong><img alt="<?php echo $style; ?>" src="<?php echo plugins_url( 'images/'.$style.'.png', dirname(__FILE__) ); ?>" />
</li>
    <?php } ?>
    </ul>
    <?php } ?>


   <?php endif; 

   

	}



	function jps_nav_method($opt_val='', $opt_name='nav_implementation'){
		global $jps_premium, $jps_post_type;
		$opt_val = ($opt_val!=''?$opt_val:jps_get($opt_name));
		$embed_videos = !in_array($jps_post_type, array('post', 'page'));
?>

<select name="jps_options[<?php echo $opt_name; ?>]">



<option value="" <?php echo ($opt_val == "") ? 'selected="selected"' : ''; ?> ><?php _e('Select','wpjps'); ?></option>





                            <option value="jQuery" <?php echo ($opt_val == "jQuery") ? 'selected="selected"' : ''; ?> >jQuery</option>



                            



                            <option value="Ajax" <?php echo ($opt_val == "Ajax") ? 'selected="selected"' : ''; ?> >Ajax<?php echo $jps_premium?'':' ('.__('Premium','wpjps').')'; ?></option>



                            <option value="Page Refresh" <?php echo ($opt_val == "Page Refresh") ? 'selected="selected"' : ''; ?> ><?php echo __('Page Refresh','wpjps'); ?><?php echo $jps_premium?'':' ('.__('Premium','wpjps').')'; ?></option>



							</select>


<?php if($embed_videos): ?>
<iframe data-val="jQuery" class="jquery" width="560" height="315" src="https://www.youtube.com/embed/C-ALIaOr7Zo" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

<iframe data-val="Ajax" class="ajax" width="560" height="315" src="https://www.youtube.com/embed/H3tt1wjDwbs" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

<iframe data-val="Page Refresh" class="page_refresh" width="560" height="315" src="https://www.youtube.com/embed/wpET7Kh717I" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
<?php else: ?>
<a data-val="jQuery" class="jquery" href="https://www.youtube.com/embed/C-ALIaOr7Zo" target="_blank"><?php echo __('Click here to watch video tutorial for this option','wpjps'); ?></a>
<a data-val="Ajax" class="Ajax" href="https://www.youtube.com/embed/H3tt1wjDwbs" target="_blank"><?php echo __('Click here to watch video tutorial for this option','wpjps'); ?></a>
<a data-val="Page Refresh" class="page_refresh" href="https://www.youtube.com/embed/wpET7Kh717I" target="_blank"><?php echo __('Click here to watch video tutorial for this option','wpjps'); ?></a>
<?php endif; ?>
<?php		

	}



	
	
	function paged_post_tinymce($mce_buttons) {
	
	
	
		$pos = array_search('wp_more', $mce_buttons, true);
	
	
	
		if ($pos !== false) {
	
	
	
			$buttons = array_slice($mce_buttons, 0, $pos + 1);
	
	
	
	
	
	
	
			$buttons[] = 'wp_page';
	
	
	
	
	
	
	
			$mce_buttons = array_merge($buttons, array_slice($mce_buttons, $pos + 1));
	
	
	
		}
	
	
	
		return $mce_buttons;
	
	
	
	}














	
	function jps_admin_scripts(){
		
		global $jps_premium;
		
		
		wp_enqueue_style('paged-post-style',plugins_url( 'css/jps-admin.css?t='.time() , dirname(__FILE__)), array(), date('Ymdhi'));
		wp_enqueue_script('jps-admin-scripts', plugins_url( 'js/admin-scripts.js?t='.time(), dirname(__FILE__)), array('jquery', 'jquery-ui-core'), '', true);
		
	
		if(isset($_GET['page']) && $_GET['page']=='jpsoptions'){
			
			wp_enqueue_style('paged-bs-style',plugins_url( 'css/bootstrap.min.css' , dirname(__FILE__)));			
			
			wp_enqueue_script('jps-bs-scripts', plugins_url( 'js/bootstrap.min.js', dirname(__FILE__)), array('jquery', 'jquery-ui-core'), '', true);

			wp_enqueue_script( 'jps-fontawesome', plugin_dir_url( dirname(__FILE__) ) . 'js/fontawesome.min.js', array('jquery'), time(), true );
			wp_enqueue_style( 'jps-fontawesome', plugins_url('css/fontawesome.min.css', dirname(__FILE__)), array(), time());		
			
			$translation_array = array(
				'nonce' => wp_create_nonce('jps_update_options_nonce'),
				'jps_tab' => (isset($_GET['t'])?esc_attr($_GET['t']):'0'),
				'this_url' => admin_url( 'options-general.php?page=jpsoptions' ),
				'clear_page_break_nonce' => wp_create_nonce('clear_page_break_nonce_action'),
				'confirm_clear_str' => __('Are you sure, you want to clear','wpjps'),' "<!--nextpage-->" '.__('or','wpjps').' "<!--nextpart-->" '.__('tags','wpjps').'. '.__('This action cannot be reverted.', 'wpjps'),
				'total_post_str' => __('No post has','wpjps').' "<!--nextpage-->" '.__('or','wpjps').' "<!--nextpart-->" '.__('tag.', 'wpjps'),
				'total_page_str' => __('No page has','wpjps').' "<!--nextpage-->" '.__('or','wpjps').' "<!--nextpart-->" '.__('tag.', 'wpjps'),
				'success_post' => __('All posts are cleared successfully.', 'wpjps'),
				'success_page' => __('All pages are cleared successfully.', 'wpjps'),
				'reset_confirm' => __('Are you sure, you want to reset styles?', 'wpjps'),
		
			);
		
			wp_localize_script('jps-admin-scripts', 'jps_obj', $translation_array);
			
			
		}
	}
	
	
	
		

	

	function jps_save_meta_box_data( $post_id ) {

	

		/*

		 * We need to verify this came from our screen and with proper authorization,

		 * because the save_post action can be triggered at other times.

		 */

	

		// Check if our nonce is set.

		if ( ! isset( $_POST['jps_meta_box_nonce'] ) ) {

			return;

		}

	

		// Verify that the nonce is valid.

		if ( ! wp_verify_nonce( $_POST['jps_meta_box_nonce'], 'jps_save_meta_box_data' ) ) {

			return;

		}

	

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {

			return;

		}

	







		if ( ! current_user_can( 'edit_post', $post_id ) ) {

			return;

		}

		

		/* OK, it's safe for us to save the data now. */

		

		// Make sure that it is set.

		if ( ! isset( $_POST['jps_nav_type_'.$post_id] ) ) {

			return;

		}

	

		// Sanitize user input.

		$my_data = sanitize_jps_data($_POST['jps_nav_type_'.$post_id]);

	

		// Update the meta field in the database.

		update_post_meta( $post_id, 'jps_nav_type', $my_data );

	}

	add_action( 'save_post', 'jps_save_meta_box_data' );
	
	