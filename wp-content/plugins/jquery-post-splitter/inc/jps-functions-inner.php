<?php

	if(!function_exists("jps_get_page_break_posts")){

		function jps_get_page_break_posts(){

			global $wpdb;
			$q_post = "SELECT COUNT(*) AS post FROM $wpdb->posts WHERE (post_content LIKE '%<!--nextpart-->%' OR post_content LIKE '%<!--nextpage-->%') AND post_type IN ('post')";
			$q_page = "SELECT COUNT(*) AS page FROM $wpdb->posts WHERE (post_content LIKE '%<!--nextpart-->%' OR post_content LIKE '%<!--nextpage-->%') AND post_type IN ('page')";

			//$result = array_merge($wpdb->get_row($q_post, ARRAY_A), $wpdb->get_row($q_page, ARRAY_A));
			
			$result = array('post'=>'0', 'page'=>'0');;

			return $result;

		}
	}

	if(!function_exists("jps_find_and_replace_page_break")){

		function jps_find_and_replace_page_break($type){

			global $wpdb;

			$type_array = array('post', 'page');

			if(!in_array($type, $type_array)) return false;

			$result = array();

			$q_post_part = "UPDATE $wpdb->posts SET post_content = REPLACE(post_content, '<!--nextpart-->', '') WHERE post_type = '$type';";
			$q_post_page_start = "UPDATE $wpdb->posts SET post_content = REPLACE(post_content, '<!-- wp:nextpage -->', '') WHERE post_type = '$type';";
			$q_post_page = "UPDATE $wpdb->posts SET post_content = REPLACE(post_content, '<!--nextpage-->', '') WHERE post_type = '$type';";
			$q_post_page_end = "UPDATE $wpdb->posts SET post_content = REPLACE(post_content, '<!-- /wp:nextpage -->', '') WHERE post_type = '$type';";

			$result[] = (bool) $wpdb->query($q_post_part);
			$result[] = (bool) $wpdb->query($q_post_page_start);
			$result[] = (bool) $wpdb->query($q_post_page);
			$result[] = (bool) $wpdb->query($q_post_page_end);

			return $result;

		}
	}


	if(!function_exists('jps_clear_block_html')){
	    function jps_clear_block_html(){

		    $jps_get_page_break_posts = jps_get_page_break_posts();
			

		    ob_start();

		    ?>

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="h5">
					    <?php _e('Posts', 'wpjps') ?>:
                        <button class="float-right btn btn-sm btn-danger clear_page_break" data-type="post" data-total="<?php echo $jps_get_page_break_posts['post']?$jps_get_page_break_posts['post']:1; ?>"><?php _e('Clean Posts', 'wpjps') ?></button>
                    </div>
                </div>
            </div>


            <div class="row mt-3">
                <div class="col-md-12">
                    <ul class="list-group">

                        <li class="list-group-item"><?php echo ($jps_get_page_break_posts['post']?__('Total ', 'wpjps').$jps_get_page_break_posts['post']._n(' post has', ' posts have', $jps_get_page_break_posts['post'], 'wpjps'):__('All posts having these', 'wpjps')).' &lt;!--nextpart--&gt; / &lt;!--nextpage--&gt; '.__('tags', 'wpjps').'.'; ?></li>

                    </ul>

                </div>
            </div>

            <div class="row mt-5">
                <div class="col-md-12">
                    <div class="h5">
					    <?php _e('Pages', 'wpjps') ?>:
                        <button class="float-right btn btn-sm btn-danger clear_page_break" data-type="page" data-total="<?php echo $jps_get_page_break_posts['page']?$jps_get_page_break_posts['page']:1; ?>"><?php _e('Clean Pages', 'wpjps') ?></button>
                    </div>
                </div>
            </div>


            <div class="row mt-3">
                <div class="col-md-12">
                    <ul class="list-group">

                        <li class="list-group-item"><?php echo ($jps_get_page_break_posts['page']?__('Total ', 'wpjps').$jps_get_page_break_posts['page']._n(' page has', ' pages have', (int) $jps_get_page_break_posts['page'], 'wpjps'):__('All pages having these', 'wpjps')).' &lt;!--nextpart--&gt; / &lt;!--nextpage--&gt; '.__('tags', 'wpjps').'.'; ?></li>

                    </ul>

                </div>
            </div>

            <?php

            $content = ob_get_contents();
            ob_end_clean();

            return $content;
        }
    }



	if(!function_exists("jps_uninstall_tab_content")){

		function jps_uninstall_tab_content(){

		    global $jps_url;

		    $kbd_url = admin_url('/plugin-install.php?s=Keep+backup+daily&tab=search&type=term');

			?>

				<div class="container-fluid jps-uninstall-wrapper">

					<div class="alert alert-warning mt-3 text-center clearfix">
                    
						<?php echo __('Do you want to uninstall this plugin?', 'wpjps').' '.__('You may clean your posts from', 'wpjps').' &lt;!--nextpage--&gt; '.__('and', 'wpjps').'/'.__('or', 'wpjps').' &lt;!--nextpart--&gt; '.__('tags.', 'wpjps') ?>
					</div>

                    <div class="alert alert-success job_clear_success mt-3" style="display: none">
	                    <span class="alert_text"></span>
                    </div>
                    
                    <div class="jps_kbd_block">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card  border-warning mt-3 mb-5" style="max-width: 100%">

                                    <div class="card-body p-0">
                                        <div class="alert alert-info clearfix">
                                        <p class="card-text"><?php _e("Before you proceed, take a backup of current database version.", 'wpjps') ?>
                                            <a href="<?php echo $kbd_url ?>" target="_blank">KEEP BACKUP DAILY</a></p>
                                        </div>
                                    </div>

                                    <a href="<?php echo $kbd_url ?>" target="_blank" style="text-align:center;">
                                        <img src="<?php echo $jps_url ?>images/kbd_banner.png" alt="Card image cap">
                                    </a>
                                </div>
                        </div>

                    </div>



				</div>

                    <div class="jps_clear_block">

                        <?php echo jps_clear_block_html() ?>

                    </div>

				</div>                    

			<?php

		}
	}

	add_action('wp_ajax_jps_clear_page_break', 'jps_clear_page_break');
	if(!function_exists("jps_clear_page_break")){

		function jps_clear_page_break(){

			if(isset($_POST['jps_clear_page_break_type'])){


				if (
					! isset( $_POST['nonce'] )
					|| ! wp_verify_nonce( $_POST['nonce'], 'clear_page_break_nonce_action' )
				) {

					print  _e( 'Sorry, your nonce did not verify.', 'wpjps' );


				} else {


				    $result_array = array();
					$type = sanitize_jps_data($_POST['jps_clear_page_break_type']);
					$result = jps_find_and_replace_page_break($type);


					if(in_array(true, $result)){

						$new_clear_block = jps_clear_block_html();
						$result_array['status'] = 'true';
						$result_array['new_clear_block'] = $new_clear_block;
                    }


					header('Content-type:application/json');
					echo json_encode($result_array);


				}


			}

			wp_die();

		}
	}
	
   function jps_navigation_styling_html($load_style){

		global $jps_styles, $jps_custom_style, $jps_premium;

		$load_style = isset($_POST['jps_load_style']) ? sanitize_jps_data($_POST['jps_load_style']) : $load_style;
		

		$load_style = $load_style ? $load_style : 'jps_beach_bar';



		if(!$load_style || !is_string($load_style)){





			?>

			<div class="alert alert-warning text-center mt-3 ">

				<?php _e('Invalid Style', 'wpjps'); ?>

			</div>

			<?php

			return;
		}

		$current_custom_style = array_key_exists($load_style, $jps_custom_style) ? $jps_custom_style[$load_style] : array();
		$simple_style = array_key_exists('simple', $current_custom_style) ? $current_custom_style['simple'] : array();
		$hover_style = array_key_exists('hover', $current_custom_style) ? $current_custom_style['hover'] : array();
		//pree(jps_get('style_buttons'));
		?>


		<form action="" method="post">


			<?php wp_nonce_field( 'jps_nonce_action', 'jps_nonce_action_field' ); ?>
			<input type="hidden" name="jps_tn"
				   value="<?php echo isset($_GET['t']) ? esc_attr($_GET['t']) : '0'; ?>"/>


<?php if(jps_get( 'style_sheet')): ?>
    <div class="alert alert-warning fade in alert-dismissible show">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true" style="font-size:20px">×</span>
        </button>    <strong><?php _e('Warning!', 'wpjps'); ?></strong> <?php _e('Styles are disabled from General Settings tab. Please enable to make customization work.', 'wpjps'); ?>
    </div>
<?php endif; ?>		


			<div class="row mt-3">
				<div class="col-md-12">

					<div class="row mt-3">
						<div class="col-md-2">

							<?php _e('Customization', 'wpjps'); ?> <small>(<?php _e('Enable', 'wpjps'); ?>/<?php _e('Disable', 'wpjps'); ?>)</small>

						</div>
						<div class="col-md-4 col-10">

							<input id="style_buttons" name="style_buttons" type="checkbox" value="1" <?php checked(jps_get('style_buttons')); ?> /> <label for="style_buttons"><small><?php _e('If unchecked, customization will be ignored.','wpjps'); ?></small></label>

						</div>

						
					</div>

				</div>
			</div>

			<div class="row mt-3 jps_nav_style_row" <?php echo jps_get('style_buttons')?'':'style="display:none;"'; ?>>
				<div class="col-md-12">

					
                    <?php if(!jps_get( 'style_sheet')): ?>
					<div class="row mt-3">
						<div class="col-md-2">

							<?php _e('Styles', 'wpjps'); ?>

						</div>
						<div class="col-md-4 col-10">

							<select class="form-control styles" name="jps_load_style">
								<?php if(!empty($jps_styles)){ foreach($jps_styles as $style){ ?>
									<option value="jps_<?php echo ($style); ?>" <?php echo ($load_style == "jps_".$style) ? 'selected="selected"' : ''; ?> ><?php echo ucwords(str_replace('_', ' ', $style)); ?></option>
								<?php } } ?>
							</select>

						</div>

						<div class="col-2 jps_spinner_col" style="display: none">
							<div class="spinner-border text-primary" role="status">
								<span class="sr-only"><?php _e('Loading', 'wpjps'); ?></span>
							</div>
						</div>
					</div>
                    <?php endif; ?>

					<?php

					if($jps_premium):

						?>

						<div class="row mt-3 jps_navigation_premium_row">
							<div class="col-md-2">

								<?php _e('Preview', 'wpjps'); ?>

							</div>
							<div class="col-md-6 text-center">

								<nav data-func="styling_html" class="jps-slider-nav jps-clearfix  <?php echo $load_style; ?>">

									<a class="jps-prev-wrapper">
										<span class="jps-prev <?php echo $load_style; ?>"><?php echo jps_get('prev_text') ?  jps_get('prev_text') : ''.__('Prev', 'wpjps').''; ?></span>
									</a>
									<a class="jps-next-wrapper">
										<span class="jps-next <?php echo $load_style; ?>"><?php echo jps_get('next_text') ? jps_get('next_text') : ''.__('Next', 'wpjps').''; ?></span>
									</a>
									<span class="jps-slide-count">
												<span class="jps_cc">2</span> <span class="jps_oo"><?php _e('Of', 'wpjps') ?></span> <span class="jps_tt">3</span>
											</span>

								</nav>

							</div>
						</div>

					<?php endif;?>

					<div class="row mt-3">
                    	<div class="col-md-2"></div>
						<div class="col-md-6">
							<button type="submit" class="btn btn-info jps_reset_style" data-reset="selected"><?php _e('Reset Selected', 'wpjps'); ?></button>
							<button type="submit" class="btn btn-info jps_reset_style" data-reset="all"><?php _e('Reset All', 'wpjps'); ?></button>
							<button type="submit" class="btn btn-info d-none" name="jps_reset_selected" data-reset="selected"><?php _e('Reset Selected', 'wpjps'); ?></button>
							<button type="submit" class="btn btn-info d-none" name="jps_reset_all" data-reset="all"><?php _e('Reset All', 'wpjps'); ?></button>

						</div>
					</div>


					<hr>


					<div class="row mt-5">
						<div class="col-md-2">

						</div>

						<div class="col-md-3 col-6 text-center">
							<strong>
								<?php _e('Link', 'wpjps'); ?>
							</strong>
						</div>

						<div class="col-md-3 col-6 text-center">
							<strong>
								<?php _e('Hover', 'wpjps'); ?>
							</strong>

						</div>
					</div>


					<div class="row mt-4">
						<div class="col-md-2">
							<label for="">
								<?php _e('Background', 'wpjps'); ?>
							</label>
						</div>

						<div class="col-md-3 col-6 text-center">

							<div class="form-group">
								<input type="color" name="" id="" class="jps_input jps_color_input" value="<?php echo array_key_exists('background', $simple_style) ? $simple_style['background'] : '' ; ?>">
								<input type="text" name="jps_custom_style[<?php echo $load_style; ?>][simple][background]" id="" class="jps_input jps_color_text" value="<?php echo array_key_exists('background', $simple_style) ? $simple_style['background'] : '' ; ?>">                            </div>

						</div>

						<div class="col-md-3 col-6 text-center">

							<div class="form-group">
								<input type="color" name="" id="" class="jps_input jps_color_input" value="<?php echo array_key_exists('background', $hover_style) ? $hover_style['background'] : '' ; ?>">
								<input type="text" name="jps_custom_style[<?php echo $load_style; ?>][hover][background]" id="" class="jps_input jps_color_text" value="<?php echo array_key_exists('background', $hover_style) ? $hover_style['background'] : '' ; ?>">                            </div>

						</div>
					</div>

					<div class="row mt-4">
						<div class="col-md-2">
							<label for="">
								<?php _e('Color', 'wpjps'); ?>
							</label>
						</div>

						<div class="col-md-3 col-6 text-center">

							<div class="form-group">
								<input type="color" name="" id="" class="jps_input jps_color_input" value="<?php echo $simple_style['color']; ?>">
								<input type="text" name="jps_custom_style[<?php echo $load_style; ?>][simple][color]" id="" class="jps_input jps_color_text" value="<?php echo $simple_style['color']; ?>">
							</div>

						</div>

						<div class="col-md-3 col-6 text-center">

							<div class="form-group">
								<input type="color" name="" id="" class="jps_input jps_color_input" value="<?php echo array_key_exists('color', $hover_style) ? $hover_style['color'] : '' ; ?>">
								<input type="text" name="jps_custom_style[<?php echo $load_style; ?>][hover][color]" id="" class="jps_input jps_color_text" value="<?php echo array_key_exists('color', $hover_style) ? $hover_style['color'] : '' ; ?>">
							</div>

						</div>
					</div>


					<div class="row mt-4">
						<div class="col-md-2">
							<label for="">
								<?php _e('Border Color', 'wpjps'); ?>
							</label>
						</div>

						<div class="col-md-3 col-6 text-center">

							<div class="form-group">
								<input type="color" name="" id="" class="jps_input jps_color_input" value="<?php echo array_key_exists('border', $simple_style) ? $simple_style['border'] : ''; ?>">
								<input type="text" name="jps_custom_style[<?php echo $load_style; ?>][simple][border]" id="" class="jps_input jps_color_text" value="<?php echo array_key_exists('border', $simple_style) ? $simple_style['border'] : '' ; ?>">
							</div>

						</div>

						<div class="col-md-3 col-6 text-center">

							<div class="form-group">
								<input type="color" name="" id="" class="jps_input jps_color_input" value="<?php echo array_key_exists('border', $hover_style) ? $hover_style['border'] : '' ; ?>">
								<input type="text" name="jps_custom_style[<?php echo $load_style; ?>][hover][border]" id="" class="jps_input jps_color_text" value="<?php echo array_key_exists('border', $hover_style) ? $hover_style['border'] : '' ; ?>">                            </div>

						</div>
					</div>

					<div class="row mt-4">
						<div class="col-md-2">
							<label for="">
								<?php _e('Font Size', 'wpjps'); ?>
							</label>
						</div>

						<div class="col-md-3 col-6 text-center">

							<div class="form-group">
								<input type="text" name="jps_custom_style[<?php echo $load_style; ?>][simple][font_size]" id="" class="form-control" value="<?php echo array_key_exists('font_size', $simple_style) ? $simple_style['font_size'] : '' ; ?>">
							</div>

						</div>

						<div class="col-md-3 col-6 text-center">

							<div class="form-group">
								<input type="text" name="jps_custom_style[<?php echo $load_style; ?>][hover][font_size]" id="" class="form-control" value="<?php echo array_key_exists('font_size', $hover_style) ? $hover_style['font_size'] : '' ; ?>">
							</div>

						</div>
					</div>

					<div class="row mt-4">
						<div class="col-md-2">
							<label for="">
								<?php _e('Font Family', 'wpjps'); ?>
							</label>
						</div>

						<div class="col-md-3 col-6 text-center">

							<div class="form-group">
								<input type="text" name="jps_custom_style[<?php echo $load_style; ?>][simple][font_family]" id="" class="form-control" value="<?php echo array_key_exists('font_family', $simple_style) ? $simple_style['font_family'] : '' ; ?>">
							</div>

						</div>

						<div class="col-md-3 col-6 text-center">

							<div class="form-group">
								<input type="text" name="jps_custom_style[<?php echo $load_style; ?>][hover][font_family]" id="" class="form-control" value="<?php echo array_key_exists('font_family', $hover_style) ? $hover_style['font_family'] : '' ; ?>">
							</div>

						</div>
					</div>


					



				</div>

			</div>



			<div class="row mt-3">
				<div class="col-md-12">
                                
                    <div class="row mt-4">
        
                            <div class="col-md-2">
                            </div>
        
        
                            <div class="col-md-3">
                                <input type="submit" value="<?php _e('Save Changes', 'wpjps') ?>" class="btn btn-primary">
                            </div>
                        </div>
				</div>
			</div>                        
		</form>




		<?php


	}

	add_action('wp_ajax_jps_get_navigation_style_html', 'jps_get_navigation_style_html');

	function jps_get_navigation_style_html(){

		$result = array(

			'status' => false,
		);


		if(!empty($_POST) && isset($_POST['jps_load_style'])){


			if(!isset($_POST['jps_nonce']) || !wp_verify_nonce($_POST['jps_nonce'], 'jps_update_options_nonce')){


				wp_die('Sorry, your nonce did not verify.', 'wpjps');


			}else{

				$result['status'] = true;
				$jps_load_style = sanitize_jps_data($_POST['jps_load_style']);

				ob_start();

				if(function_exists('jps_get_navigation_style_html')){

					jps_navigation_styling_html($jps_load_style);

				}

				$result['html_code'] = ob_get_clean();

			}

		}

		wp_send_json($result);

		wp_die();


	}

	add_action('init', 'jps_reset_custom_styles');

	function jps_reset_custom_styles(){

		global $jps_custom_style, $jps_custom_style_default;




		if(isset($_POST['jps_reset_selected']) || isset($_POST['jps_reset_all'])){


			if(!isset($_POST['jps_nonce_action_field']) || !wp_verify_nonce($_POST['jps_nonce_action_field'], 'jps_nonce_action')){


				wp_die('Sorry, your nonce did not verify.', 'wpjps');


			}else{

				$jps_load_style = sanitize_jps_data($_POST['jps_load_style']);

				if(isset($_POST['jps_reset_selected'])){

					if(array_key_exists($jps_load_style, $jps_custom_style_default)){

						$default_style = $jps_custom_style_default[$jps_load_style];
						$jps_custom_style[$jps_load_style] = $default_style;


					}


				}elseif(isset($_POST['jps_reset_all'])){

					delete_option('jps_custom_style');
					$jps_custom_style = $jps_custom_style_default;


				}

				update_option('jps_custom_style', $jps_custom_style);


			}

		}


	}

	add_action('init', 'jps_save_custom_styling', 9);

	function jps_save_custom_styling(){

		global $jps_custom_style;

		if(!empty($_POST) && isset($_POST['jps_custom_style'])){

			if(!isset($_POST['jps_nonce_action_field']) || !wp_verify_nonce($_POST['jps_nonce_action_field'], 'jps_nonce_action')){

				wp_die('Sorry, your nonce did not verify.', 'wpjps');


			}else{

				$jps_load_style = isset($_POST['jps_load_style']) ? sanitize_jps_data($_POST['jps_load_style']) : '';
				$jps_custom_style_post = isset($_POST['jps_custom_style']) ? sanitize_jps_data($_POST['jps_custom_style']) : array();
				$style_buttons = isset($_POST['style_buttons']) ? sanitize_jps_data($_POST['style_buttons']) : '';
				
				

				if($jps_load_style || $jps_custom_style){

					$current_style = $jps_custom_style_post[$jps_load_style];

					$jps_custom_style_option = get_option('jps_custom_style', $jps_custom_style);

					if(array_key_exists($jps_load_style, $jps_custom_style_option)){

						$jps_custom_style_option[$jps_load_style] = $current_style;

					}

					update_option('jps_custom_style', $jps_custom_style_option);
					$jps_custom_style = $jps_custom_style_option;
					
					$jps_options = get_option('jps_options');
					$jps_options['nav_style'] = $jps_load_style;
					$jps_options['style_buttons'] = $style_buttons;
					update_option('jps_options', $jps_options);
					

				}


			}




		}


	}	