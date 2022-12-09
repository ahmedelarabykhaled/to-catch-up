<?php if ( ! defined( 'ABSPATH' ) ) exit; 

	global $jps_ads;
	$jps_ads = array();

	function jps_paginator_dash($num=0, $current=0){
		
		$nav_numbered = jps_get( 'nav_numbered' );
		$nav_implementation = jps_get( 'nav_implementation' );
		$ret = '';
		
		if($nav_numbered && in_array($nav_implementation, array('jQuery'))){//Page Refresh
			if($num){
				$ret .= '<div class="jps-numbered">';
			}
			
			for($n=1; $n<=$num; $n++){
				$ret .= '<a data-num="'.$n.'" '.($current==$n?'class="jps-num-current"':'').'>'.$n.'</a>';
			}
			if($num){
				$ret .= '</div>';
			}
		}
		
		return $ret;
	}
	
	function paged_parts_logic(){
		
		
		if((is_single() || is_page()) && function_exists('jps_premium_options') && jps_get( 'nav_implementation' )!='jQuery'){
			global $post;
			
			if(!empty($post) && isset($post->post_content))
			$post->post_content = str_replace('<!--nextpart-->', '<!--nextpage-->', $post->post_content);
		}
		
	}
	
	function jps_ads_makeup($key){
		
		global $jps_ads;
		$jps_ads[$key] = (isset($jps_ads[$key])?$jps_ads[$key]+1:0);
		
		$data = ($jps_ads[$key]<1?jps_get($key):'');
		if($data!=''){
			$data = '<ins '.($data).'></ins>';
			$data .= '<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';
		}
        return $data;
		
	}
	
	function jps_get_post_nav_type(){
		
		$jpps_nav_implementation = 'jQuery';//default
		
		$post_nav_type = get_post_meta( get_the_ID(), 'jps_nav_type', true );



		if($post_nav_type!=''){

            $jpps_nav_implementation = $post_nav_type;

		}else{

            $jpps_nav_implementation = jps_get( 'nav_implementation' );


        }	
		
		return $jpps_nav_implementation;	
	}
	
	function paged_post_the_content_filter($content) {


        global $multipage, $numpages, $page, $post, $jps_premium, $is_single, $is_page;



        //return $content;
		$ignore = (!is_single() && !is_page());

		if($ignore)
		return $content;

		$jpps_nav_implementation = jps_get_post_nav_type();





		$js = (($jpps_nav_implementation=='jQuery' || $jpps_nav_implementation=='' || !$jps_premium)?true:false);//($jps_premium?false:true)
//		pree($js);


		$page_refresh = ($jpps_nav_implementation==''.__('Page Refresh', 'wpjps').'' && $jps_premium);
//		pree($page_refresh);


		//pree($jps_premium);

		//Show Full Post If Full Post Option

        $ppscontent = '';

		if(isset($_GET['jps']) && $_GET['jps'] == 'full_post'){



			global $post;



			$ppscontent .= wpautop($post->post_content);



			if(jps_get( 'show_all_link')){



				$ppscontent .=  '<p class="jps-fullpost-link"><a href="'.get_permalink().'" title="'.__('View as Slideshow','wpjps').'">'.__('View as Slideshow','wpjps').'</a></p>';



			}







		//Else Show Slideshow



		} elseif($js){


				//pree($content);
				
				$ppscontent = jps_free_options($content);
				
				//pree($ppscontent);



		}else {







			if(function_exists('jps_premium_options')){


				$ppscontent = jps_premium_options($content);



			}







		}



		// Returns the content.



		return $ppscontent;



	}
	
	
	
	function jps_header_scripts(){
		global $jps_premium;
	
		$jpps_nav_implementation = jps_get_post_nav_type();
		
		$js = (($jpps_nav_implementation=='jQuery' || $jpps_nav_implementation=='' || !$jps_premium)?true:false);	
		?>
        
        <script type="text/javascript" language="javascript">
			function jps_custom_functions_set_1(){
				<?php echo jps_get('nav_load_function'); ?>
			}
		</script>
	
		<style type="text/css">
		<?php if($js || !jps_get('default_navigation')): ?>
		div.page-links{
			display:none !important;
		}
		<?php endif; ?>
	
		<?php if(!jps_get( 'show_all_link')){ ?>
	
		body .jps-fullpost-link{
	
			display:none;
	
		}
	
		<?php } ?>
	
		</style>
	
		<?php
	
	}
	
	
	
	add_action('wp_head', 'jps_header_scripts'); 
	add_action('wp_footer', 'jps_footer_scripts'); 


	

	
	
	
	
	
	
	
	function paged_post_scripts() {
	
	
	
		if(is_single() || is_page() ){
	
	
	
			
	
	
	
			global $jps_premium, $is_ajax, $wp, $post, $page;
	
	
	
			
	
	
	
			wp_enqueue_script('jquery');
	
	
	
			$post_nav_type = get_post_meta( get_the_ID(), 'jps_nav_type', true );
	
	
	
			if($post_nav_type!=''){
	
				$jpps_nav_implementation = $post_nav_type;
	
			}else{
	
				$jpps_nav_implementation = jps_get( 'nav_implementation' );	
	
			}
	
	
	
			$js = ($jpps_nav_implementation=='jQuery'?true:false);//($jps_premium?false:true)
	
			
	
	
	
			$page_refresh = ($jpps_nav_implementation==''.__('Page Refresh', 'wpjps').'');
	
	
	
			$ajax = ($jpps_nav_implementation=='Ajax');


			$is_ajax = $ajax;


	
	
			$scroll_number = jps_get('scroll_number');
			$scroll_number = (($scroll_number && is_numeric($scroll_number))?$scroll_number*1:0);
			$delay = jps_get('delay');
			$jpps_options_array = array( 'scroll_up' => jps_get( 'scroll_up'), 'scroll_number' => $scroll_number, 'premium'=>$jps_premium, 'delay' => ($delay?$delay:0), 'the_post'=>jps_get_post_obj($post, $page) );
	
			
			
			$jps_restrictions = jps_get('jps_restrictions', true);
			$jps_restrictions = ($jps_restrictions!=''?nl2br($jps_restrictions):'');
			$jps_restrictions = explode('<br />', $jps_restrictions);
			//pree(wp_guess_url());
			//pree(get_queried_object());
			$request = explode( '/', $wp->request );
			//pree($request);
			$matched = 0;
			if(!empty($request)){
				//pree($jps_restrictions);
				foreach($request as $index){
					if(in_array('/'.$index, $jps_restrictions)){
						$matched++;
					}
				}
			}
			
			
			wp_enqueue_style('jps-common-front', plugins_url( 'css/common-front.css', dirname(__FILE__)), '', time());
			
			if(!$matched){
	
				if($js){
					
					wp_enqueue_script('paged-post-jquery', plugins_url( 'js/paged-post-jquery.js', dirname(__FILE__)), 'jquery', time(), true);
		
		
					wp_enqueue_style('paged-post-jquery', plugins_url( 'css/paged-post-jquery.css', dirname(__FILE__)), '', time());
					
					
					if(!$jps_premium)
					wp_localize_script( 'paged-post-jquery', 'jpps_options_object', $jpps_options_array );
		
					
		
		
		
				}elseif($jps_premium){
		
		
		
					if(function_exists('jps_premium_scripts')){
		
		
		
						jps_premium_scripts();
						
						if(!$jps_premium)
						wp_localize_script( 'jquery-post-splitter', 'jpps_options_object', $jpps_options_array );
		
					}
		
		
		
				}
	
				if(!jps_get( 'style_sheet') && !$jps_premium){
		
		
		
						wp_enqueue_style('paged-post-style', plugins_url( 'css/paged-post.css?j='.date('Ymhi') , dirname(__FILE__)));
		
		
		
				}
				
			}
	
			

	
	
			
	
	
	
		}
	
	
	
	}
	
	function jps_get_post_obj($post, $page){
		
		
		$post_nav_type = get_post_meta( $post->ID, 'jps_nav_type', true );



		if($post_nav_type!=''){

			$jpps_nav_implementation = $post_nav_type;

		}else{

			$jpps_nav_implementation = jps_get( 'nav_implementation' );	

		}	
		
		$ajax = ($jpps_nav_implementation=='Ajax');	
		
		return array(

			'post_id' => $post->ID,
			'is_single' => is_single(),
			'is_page' => (in_array($post->post_type, array('page'))),
			'is_post' => (in_array($post->post_type, array('post'))),
			'is_product' => (in_array($post->post_type, array('product'))),	
			'is_order' => (in_array($post->post_type, array('shop_order'))),						
			'is_ajax' => $ajax,
			'page' => $page,
			'loop_slides' => jps_get('loop_slides'),
		);
	}

	
	function jps_free_options($ppscontent){
			
			global $multipage, $numpages, $page, $jps_headings, $jps_premium;
			
			$nav_implementation = jps_get( 'nav_implementation' );
			
			$jps_headings_display = ((jps_get('headings_display') && function_exists('jps_headings'))?true:false);
	
			$jps_bullets_display = ((jps_get('bullets_display') && function_exists('jps_leftnav'))?true:false);
	
	
			$ppscontent_arr = array();
	
			
	
			if ( (is_single() || is_page()) ){
	
	
	
				
	
	
	
				global $post;
				
				$ppscontent = $post->post_content;
				
				$jpps_nav_implementation = jps_get_post_nav_type();
				
				$js = (($jpps_nav_implementation=='jQuery' || $jpps_nav_implementation=='' || !$jps_premium)?true:false);
	
	
				$ppscontent = str_replace( array('<!-- wp:nextpage -->', '<!-- /wp:nextpage -->'), '', $ppscontent );
				$ppscontent = str_replace( array('<!--nextpage-->'), '<!--nextpart-->', $ppscontent );
				$ppscontent = str_replace( "<!--nextpart-->", PHP_EOL.'<!--nextpart-->'.PHP_EOL, $ppscontent );
				$post_parts = explode('<!--nextpart-->', $ppscontent);
				$post_parts = array_map('trim', $post_parts);

				//pree($post_parts);exit;
	
				
	
				if(!empty($post_parts) && count($post_parts)>1){
	
				
					$arr = next_prev_arr();
					//pree($arr);
					$next_text = $arr['next_text'];
					$prev_text = $arr['prev_text'];
					$sep_text = $arr['sep_text'];
					$nav_style = $arr['nav_style'];
					
					//pree($arr);
	
					$page = 0; $numpages = count($post_parts);


					if(empty($jps_headings)){
		
						foreach($post_parts as $content){
							
							$get_heading = explode('</h2>', $content);
							
							if(!empty($get_heading) && count($get_heading)>1){
								$get_heading = current($get_heading);
								$get_heading = explode('<h2>', $get_heading);
								if(!empty($get_heading)){
									$heading = end($get_heading);
									//if(!in_array($heading, $jps_headings))
									$jps_headings[] = ($heading!=''?$heading:__('Next', 'wpjps'));
								}
							}
							
						}
					
					}
					
//					pre($jps_headings);

					
					
					foreach($post_parts as $content){ $page++;
	
	
						$jps_numbers = (function_exists('jps_paginator_dash')?jps_paginator_dash($numpages, $page):'');
	
						$sliderclass = 'pagination-slider';
	
	
	
						$slidecount = ($numpages>1?'<span class="jps-slide-count"><span class="jps_cc">'.$page.'</span> <span class="jps_oo">'.$sep_text.'</span> <span class="jps_tt">'.$numpages.'</span></span>'.$jps_numbers:'');
	
	
						//pree($page);pree($numpages);
						if($page == $numpages){	
	
	
							$slideclass = 'jps-last-slide';
	
	
	
						} elseif ($page == 1){
	
	
	
							$slideclass = 'jps-first-slide';
	
	
	
						} else{
	
	
	
							$slideclass = 'jps-middle-slide';
	
	
	
						}
	
	
	
				
	
	
	
						//What to Display For Content
						
						
	
	
	
						$ppscontent = '<div id="post-part-'.$page.'" class="jps-wrap-content jps-parent-div jps-'.strtolower($nav_implementation).'"><div class="jps-the-content '.$slideclass.'">';
						//Top Slider Navigation
	
						if((jps_get( 'nav_position' ) == 'top' || jps_get( 'nav_position' ) == '') || (jps_get( 'nav_position' ) == 'both')){
	
	
	
							
	
	
	
				
	
							$wp_link_pages = $js?'':wp_link_pages(array('echo'=>0));
							//pree(jps_pages_links($page, $numpages));
							$wp_link_pages = ($wp_link_pages!=''?$wp_link_pages:jps_pages_links($page, $numpages));
							//pree($wp_link_pages);
	
							$ppscontent_nav = ($jps_headings_display?jps_headings($page, get_permalink($post->ID)):$wp_link_pages);
							
							
				
							$ppscontent .= jps_ads_makeup('jps_before_nav_top');
							
							//pree($numpages);pree($ppscontent_nav);
	
							$ppscontent .= (($numpages>1 && $ppscontent_nav!='')?'<nav data-func="free_options_top" class="jps-slider-nav jps-clearfix '.($jps_headings_display?'jps_heading_display':'').' '.$nav_style.'">'.$ppscontent_nav:'');
				
	
	
	
							// If Loop Option Selected, Loop back to Beginning
	
	
	
							if(jps_get( 'loop_slides')){
	
	
	
								if($page == $numpages && !$jps_headings_display){
	
									$ppscontent .= '<a class="jps-next-link"><span class="jps-next '.$nav_style.'">'.$next_text.'</span></a>';
	
	
								}
	
	
							}
	
							// Top Slide Counter
	
	
	
							if((jps_get( 'count_position' ) == 'top' || jps_get( 'count_position' ) == '') || (jps_get( 'count_position' ) == 'both')){
	
								$ppscontent .= $slidecount;
							}
	
	
							$ppscontent .= ($numpages>1?'</nav>':'');
	
							$ppscontent .= jps_ads_makeup('jps_after_nav_top');
	
						}
	
	
	
				
	
	
	
						//Top Slide Counter Without Top Nav
	
	
	
						if(((jps_get( 'count_position' ) == 'top') || (jps_get( 'count_position' ) == 'both')) && ((jps_get( 'nav_position' ) != 'top')&&(jps_get( 'nav_position' ) != 'both'))){
	
	
	
								$ppscontent .= $slidecount;
	
	
	
						}
				
	
	
						
						
	
						// Slide Content
	
						if($jps_bullets_display){
							
							$ppscontent .= ($numpages>1?'<div class="jps-content jps-clearfix">'.jps_leftnav($page).'<div class="content-extact">'.(jps_get( 'br_status')?nl2br($content):$content).'</div></div>':'');
						
						}else{
							
							$ppscontent .= ($numpages>1?'<div class="jps-content jps-clearfix">'.(jps_get( 'br_status')?nl2br($content):$content).'</div>':'');
							
						}
	
	
	
				
	
	
	
						// Bottom Slider Navigation
	
	
	
						if((jps_get( 'nav_position' ) == 'bottom') || (jps_get( 'nav_position' ) == 'both')){
	
	
	
							
	
							$wp_link_pages = $js?'':wp_link_pages(array('echo'=>0));
							//pree(jps_pages_links($page, $numpages));exit;
							$wp_link_pages = ($wp_link_pages!=''?$wp_link_pages:jps_pages_links($page, $numpages));
	
							$ppscontent_nav = ($jps_headings_display?jps_headings($page, get_permalink($post->ID)):$wp_link_pages);
							
	
							$ppscontent_nav = ($jps_headings_display?jps_headings($page, get_permalink($post->ID)):$wp_link_pages);
							
							$ppscontent .= jps_ads_makeup('jps_before_nav_bottom');
	
	 
							$ppscontent .= (($numpages>1 && $ppscontent_nav!='')?'<nav data-func="free_options_bottom" class="jps-slider-nav jps-bottom-nav jps-clearfix '.($jps_headings_display?'jps_heading_display':'').' '.$nav_style.'">'.$ppscontent_nav:'');
				
	
	
	
							// If Loop Option Selected, Loop back to Beginning
	
	
	
							if(jps_get( 'loop_slides')){
	
	
	
								if($page == $numpages && !$jps_headings_display){
	
	
	
									$ppscontent .= '<a class="jps-next-link"><span class="jps-next '.$nav_style.'">'.$next_text.'</span></a>';
	
	
	
								}
	
	
	
							}
	
	
	
				
	
	
	
							// Bottom Slide Counter
	
	
	
							if((jps_get( 'count_position' ) == 'bottom')||(jps_get( 'count_position' ) == 'both')){
	
	
	
								$ppscontent .= $slidecount;
	
	
	
							}
	
	
	
				
	
	
	
							$ppscontent .= ($numpages>1?'</nav>':'');
	
	
							$ppscontent .= jps_ads_makeup('jps_after_nav_bottom');
	
						}
	
	
	
				
	
	
	
						// Bottom Slide Counter Without Bottom Nav
	
	
	
						if(((jps_get( 'count_position' ) == 'bottom')||(jps_get( 'count_position' ) == 'both')) && ((jps_get( 'nav_position' ) != 'bottom')&&(jps_get( 'nav_position' ) != 'both'))){
	
	
	
								$ppscontent .= $slidecount;
	
	
	
							}
	
	
	
				
	
	
	
						// End Slider Div
	
	
	
						$ppscontent .= ($numpages>1?'</div></div>':'');
	
	
	
				
	
	
	
						// Show Full Post Link
	
	
	
						if(jps_get( 'show_all_link')){
	
	
	
							$ppscontent .=  '<p class="jps-fullpost-link vf-'.$page.' '.($page==1?'':'hide').'"><a href="'.add_query_arg( 'jps', 'full_post', get_permalink() ).'" title="'.__('View Full Post','wpjps').'">'.__('View Full Post','wpjps').'</a></p>';
	
	
	
						}
	
	
	
				
	
	
	
					// Else It Isn't Pagintated, Don't Show Slider
	
	
	
					
	
	
	
					$ppscontent_arr[] = $ppscontent;
	
	
	
					}
	
					//pre(count($ppscontent_arr));
					$ppscontent = implode(' ', $ppscontent_arr);

					if($jps_premium){
						$ppscontent = html_entity_decode((jps_get('jps_custom_section', true))).$ppscontent;
						$ppscontent .= html_entity_decode((jps_get('jps_custom_section_below', true)));
					}
					
					return $ppscontent;
	
				}else{ 
	
					if(is_single() || is_page()){
						
						//return (jps_get( 'br_status')?nl2br($post->post_content):$post->post_content);	
						return (jps_get( 'br_status')?nl2br($ppscontent):$ppscontent);	
						
					}else{
						if($jps_premium){
							$ppscontent = html_entity_decode((jps_get('jps_custom_section', true))).$ppscontent;
							$ppscontent .= html_entity_decode((jps_get('jps_custom_section_below', true)));
						}
						return $ppscontent;
					}
	
				}
	
	
	
				//exit;
	
	
	
				
	
	
	
			}		
	
	
	
			
	
	
	
	}
	function jps_pages_links($start, $end){
		$ret = '';
		$loop_slides = jps_get('loop_slides');
		$arr = next_prev_arr();
		$next_text = $arr['next_text'];
		$prev_text = $arr['prev_text'];
		$nav_style = $arr['nav_style'];
				
		if($start>1 || $loop_slides)
		$ret .= '<a><span class="jps-prev '.$nav_style.'">'.$prev_text.'</span></a>';
		
		if($start!=$end)
		$ret .= '<a><span class="jps-next '.$nav_style.'">'.$next_text.'</span></a>';
		
		return $ret;
	}

	function jps_footer_scripts(){
		
		global $jps_headings;
		
		$next_prev_arr = next_prev_arr();
		//pree($next_prev_arr);
?>
	<script type="text/javascript" language="javascript">
		//<?php echo implode(', ', $jps_headings); ?>
		
	jQuery(document).ready(function($) {
		
		<?php if(!jps_get( 'loop_slides') && function_exists('epn_prev_post_link') && function_exists('epn_next_post_link')){ ?>
		if($(".jps-slider-nav").length>0){
			$.each($(".jps-slider-nav"), function(){
				
				if($(this).find("a").length==1){
					//$(this).prepend($(this).find('a').clone());
					//$(this).find('a').eq(0).css('visibility', 'hidden');
					if($(this).find('a > span').hasClass('jps-next'))
					$('<?php echo epn_prev_post_link(false, '<span class="'.$next_prev_arr['nav_style'].' jps-prev">'.$next_prev_arr['prev_text'].'</span>'); ?>').insertBefore($(this).find('a'));
					else
					$('<?php echo epn_next_post_link(false, '<span class="'.$next_prev_arr['nav_style'].' jps-next">'.$next_prev_arr['next_text'].'</span>'); ?>').insertAfter($(this).find('a'));
				}
			});
			
		}
		<?php } ?>
	});	
	</script>
<?php		
	}
	
	add_action('init', 'spnp_js');


	function spnp_js() {		
		if (!empty($_REQUEST['spnp-action']) && $_REQUEST['spnp-action'] == 'spnp-admin-js') {
			header('content-type: text/javascript');?>
            var wp, hasWpautop;
            ( function( tinymce ) {
tinymce.PluginManager.add( 'wordpress', function( editor ) {
	var wpAdvButton, style,
		DOM = tinymce.DOM,
		each = tinymce.each,
		__ = editor.editorManager.i18n.translate,
		$ = window.jQuery,
		wp = window.wp,
		hasWpautop = ( wp && wp.editor && wp.editor.autop && editor.getParam( 'wpautop', true ) );
});
});
            (function() {
           	
        	
	tinymce.create("tinymce.plugins.spnpnextpage", {
		init : function(ed, url) {			
			ed.addButton("jpsPPBtnBtn", {
                title: "<?php echo __('Insert Next Page Tag','wpjps'); ?>",
				cmd: "cmd_jpsPPBtnBtn",
				onclick : function() {
                         ed.selection.setContent("<!--nextpart-->");
                    }
            });
			ed.addCommand( "cmd_jpsPPBtnBtn", function() {
				var selected_text = ed.selection.getContent();
				var return_text = "";
				return_text = "<!--nextpart-->";
				ed.execCommand("mceInsertContent", 0, return_text);
			});
						
			                    
            ed.on( 'BeforeSetContent', function( event ) {
                var title;
        
                if ( event.content ) {
                            
                    if ( event.content.indexOf( '<!--nextpart-->' ) !== -1 ) {
                        title = 'Page Splitter';
        
                        event.content = event.content.replace( /<!--nextpart-->/g,
                            '<img src="' + tinymce.Env.transparentSrc + '" data-wp-more="nextpart" class="wp-more-tag mce-wp-nextpart" ' +
                                'alt="" title="' + title + '" data-mce-resize="false" data-mce-placeholder="1" />' );
                    }
        
                    if ( event.load && event.format !== 'raw' && hasWpautop ) {
                        event.content = wp.editor.autop( event.content );
                    }
        
                    // Remove spaces from empty paragraphs.
                    // Avoid backtracking, can freeze the editor. See #35890.
                    // (This is also quite faster than using only one regex.)
                    event.content = event.content.replace( /<p>([^<>]+)<\/p>/gi, function( tag, text ) {
                        if ( /^(&nbsp;|\s|\u00a0|\ufeff)+$/i.test( text ) ) {
                            return '<p><br /></p>';
                        }
        
                        return tag;
                    });
                }
            });            
			// Replace image with tag
            ed.on( 'PostProcess', function( e ) {
                if ( e.get ) {
                    e.content = e.content.replace(/<img[^>]+>/g, function( image ) {
                        var match, moretext = '';
        
                        if ( image.indexOf( 'data-wp-more="nextpart"' ) !== -1 ) {
                            image = '<!--nextpart-->';
                        }
        
                        return image;
                    });
                }
            });	
            
            
		},
		getInfo : function() {
            return {
                longname: "jpsPPBtn",
                author: "Fahad Mahmood",
                authorurl: "https://profiles.wordpress.org/fahadmahmood/#content-plugins",
                infourl: "https://profiles.wordpress.org/fahadmahmood/",
                version: "2.3.5"
            };
        }
	});
	tinymce.PluginManager.add("spnpnextpage", tinymce.plugins.spnpnextpage);
})();<?php exit;
		}
	}	