<?php if ( ! defined( 'ABSPATH' ) ) exit;  
	
	global $wpdb, $jps_url, $disable_style_sheet, $jps_premium;

if ( 
    ! isset( $_POST['jps_patch_field'] ) 
    || ! wp_verify_nonce( $_POST['jps_patch_field'], 'jps_patch' ) 
) {

   //print 'Sorry, your nonce did not verify.';
   //exit;

} else {

   // process form data
	$q = "SELECT ID, post_content FROM $wpdb->posts WHERE post_content LIKE '%<!--nextpart-->%' AND post_type IN ('post', 'page')";
	$fix_required = $wpdb->get_results($q);
	if(!empty($fix_required)){
		foreach($fix_required as $ids){
			$post_content = $ids->post_content;
			$post_content = str_replace('<!--nextpart-->', '<!--nextpage-->', $post_content);
			//$uq = "UPDATE $wpdb->posts SET post_content='$post_content' WHERE ID=$ids->ID LIMIT 1";
			//$wpdb->query($uq);
			  $my_post = array(
				  'ID'           => $ids->ID,
				  'post_content' => $post_content,
			  );
			  wp_update_post( $my_post );			
		}
	}
   
}

$q = "SELECT COUNT(*) AS nextpage FROM $wpdb->posts WHERE post_content LIKE '%<!--nextpart-->%' AND post_type IN ('post', 'page')";
$fix_required = $wpdb->get_row($q);
$fix_required = $fix_required->nextpage;//$fix_required = 0;//
$disable_style_sheet = jps_get('style_sheet');
?>
        
<style type="text/css">
<?php if($disable_style_sheet): ?>
.disable-use-stylesheet{
	display:none !important;
}
<?php endif; ?>
</style>

	<div class="wrap jps-wrapper <?php echo ($jps_premium?'jps-pro-wrapper':'jps-free-wrapper'); ?>">



		<h2><i class="fas fa-cut"></i> <?php echo $jps_title_v.' '.__( 'Settings','wpjps' ); ?></h2>

<?php if(!$jps_premium): ?>
<a title="<?php _e('Click here to download pro version','wpjps'); ?>" style="background-color: #25bcf0;    color: #fff !important;    padding: 2px 30px;    cursor: pointer;    text-decoration: none;    font-weight: bold;    right: 0;    position: absolute;    top: 0;    box-shadow: 1px 1px #ddd;" href="https://shop.androidbubbles.com/download/" target="_blank"><?php echo __('Already a Pro Member?','wpjps'); ?></a>
<?php endif; ?>

        <h2 class="nav-tab-wrapper">
            <a class="nav-tab nav-tab-active"><i class="fas fa-cogs"></i> <?php _e("General","wpjps"); ?></a>
            <a class="nav-tab"><i class="fas fa-code"></i> <?php _e("Developer Mode","wpjps"); ?></a>
            <a class="nav-tab"><i class="fas fa-palette"></i> <?php _e("Customization", 'wpjps'); ?></a>
            <a class="nav-tab"><i class="fas fa-exclamation-triangle"></i> <?php _e("Uninstall","wpjps"); ?></a>
            <a class="nav-tab" data-tab="help" data-type="free" style="float:right"><i class="far fa-question-circle"></i>&nbsp;<?php _e("Help", 'jquery-post-splitter'); ?></a>
        </h2>
        <div class="nav-tab-content mt-2">
<?php 
$this_url = str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] );
if($fix_required>0): ?>
<div class="jps-patch-confirm">
<form action="<?php echo $this_url; ?>" method="post">
<?php wp_nonce_field( 'jps_patch', 'jps_patch_field' ); ?>
<?php _e("You're using", 'wpjps'); ?> <strong>&lt;!--nextpart--&gt;</strong> instead of <strong>&lt;!--nextpage--&gt;</strong> <?php _e('in','wpjps'); ?> <?php echo $fix_required; ?> <?php _e('posts', 'wpjps'); ?> <?php _e('and','wpjps'); ?>/<?php _e('or','wpjps'); ?> pages. <?php _e('Do you want to implement the patch now?','wpjps'); ?> <?php echo __('It will convert','wpjps').' nextpart '.__('to','wpjps').' nextpage '.__('and this plugin will work fine for these','wpjps').' '.__('posts','wpjps').' '.__('and','wpjps').' pages '.__('as well','wpjps').'.'; ?><br />
<input class="button button-secondary" name="jps-patch" type="submit" value="<?php _e("Click here to Apply Patch", 'wpjps'); ?>" />
</form>
</div>
<?php endif; ?>


		<form name="jpps_img_options" method="post" action="<?php echo $this_url; ?>">
        	
            <input type="hidden" name="jps_tn" value="<?php echo isset($_GET['t'])?esc_attr($_GET['t']):'0'; ?>" />

			<?php wp_nonce_field( 'jps_nonce_action', 'jps_nonce_action_field' ); ?>

			<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y" />







			<table class="form-table jps_settings_table">



				<tbody>



                



              	  <tr valign="top">



						<th scope="row ">



							<label for="nav_implementation"><?php _e('Implementation','wpjps'); ?>:</label>



						</th>



						<td class="nav_implementation_td">



							<?php jps_nav_method(); ?>

                            

                            <?php jps_go_premium(); ?>



						</td>



					</tr>
                    
            	  <tr valign="top" class="jps_row jquery">



						<th scope="row ">



							<label for="nav_numbered"><?php _e('Numbers','wpjps'); ?>:</label>



						</th>



						<td class="nav_numbered_td">

							<input id="nav_numbered" name="jps_options[nav_numbered]" type="checkbox" value="1" <?php checked( '1', jps_get('nav_numbered') ); ?> /> <?php _e("Numeric pagination will appear as well like 1, 2, 3...",'wpjps'); ?>


						</td>



					</tr>                    

				<tr class="jps_row">
                <th>
                </th>
                	<td>
                    <div style="margin-top: 10px" class="ajax jquery"><?php _e('Use this tag', 'wpjps') ?> <strong style="color: #004085"> "&lt;!--nextpart--&gt;" </strong> <?php _e('to split the post in parts', 'wpjps') ?>.<br />
<small><a href="https://ps.w.org/jquery-post-splitter/assets/sample.html" target="_blank"><?php _e('Click here', 'wpjps') ?></a> <?php _e('to download the sample post', 'wpjps') ?>.</small></div>
                    </td>
                </tr>

                  <tr class="jps_row ajax page_refresh" style=" <?php echo in_array(jps_get('nav_implementation'), array('Ajax', 'Page Refresh')) ? '' : 'display:none'; ?>">
                      <th scope="row">
                        <label for="jps_default_navigation"><?php _e('Disable default post navigation','wpjps'); ?>:</label>
                      </th>
                      <td>
                          <input id="jps_default_navigation" name="jps_options[default_navigation]" type="checkbox" value="1" <?php checked( '1', jps_get('default_navigation') ); ?> /> <?php _e("If unchecked, theme's default post navigation will be used, instead of Next , Previous",'wpjps'); ?>
                      </td>
                  </tr>



                <tr class="jps_row ajax">
                    <th scope="row">
                        <label for="nav_load_function"><?php _e('JavaScript Function on Slide','wpjps'); ?>:</label>
                    </th>
                    <td>
                        <input id="nav_load_function" name="jps_options[nav_load_function]" type="text" placeholder="foo_bar(); foo_func(); foo_results();" value="<?php echo jps_get('nav_load_function'); ?>" /><br />
                       
                        <div style="padding:10px 0;">
                        <strong><?php _e('Try this','wpjps'); ?>:</strong> <span style="color:#900;">jps_test_func();</span><br />
                        <strong><?php _e('Results','wpjps'); ?>:</strong> <a style="color:#063;" target="_blank" href="https://youtu.be/CBI2T88KBow">Ajax navigation worked!</a>

                        </div>

                    </td>
                </tr>
                
                
                
<tr class="jps_row page_refresh" style=" <?php echo in_array(jps_get('nav_implementation'), array(''.__('Page Refresh','wpjps').'')) ? '' : 'display:none'; ?>">
<th scope="row">
<label for="jps_prerender"><?php _e('Prerender Next Page/Content','wpjps'); ?>:</label>
</th>
<td>
<input id="jps_prerender" name="jps_options[prerender]" type="checkbox" value="1" <?php checked( '1', jps_get('prerender') ); ?> /><?php _e("If checked, Next Page will be rendered behind the current page.",'wpjps'); ?>
</td>
</tr>

<tr class="jps_row page_refresh" style=" <?php echo in_array(jps_get('nav_implementation'), array('Page Refresh')) ? '' : 'display:none'; ?>">
<th scope="row">
<label for="jps_prefetch"><?php _e('Prefetch Next Page/Content','wpjps'); ?>:</label>
</th>
<td>
<input id="jps_prefetch" name="jps_options[prefetch]" type="checkbox" value="1" <?php checked( '1', jps_get('prefetch') ); ?> /><?php _e("If checked, Next Page Resources will be prefetched on behind the current page.",'wpjps'); ?>
</td>
</tr>                



					<tr valign="top">



						<th scope="row">



							<label for="nav_position"><?php _e('Navigation Position','wpjps'); ?>:</label>



						</th>



						<td>



							<select name="jps_options[nav_position]">



								<option value="top" <?php echo (jps_get('nav_position') == "top") ? 'selected="selected"' : ''; ?> ><?php _e('Top','wpjps'); ?></option>



								<option value="bottom" <?php echo (jps_get('nav_position') == "bottom") ? 'selected="selected"' : ''; ?> ><?php _e('Bottom','wpjps'); ?></option>



								<option value="both" <?php echo (jps_get('nav_position') == "both") ? 'selected="selected"' : ''; ?> ><?php _e('Both','wpjps'); ?></option>



							</select>



						</td>



					</tr>


<tr valign="top">
						<th scope="row">

							<label for="style_sheet"><?php _e('Disable styles?','wpjps'); ?></label>
						</th>

						<td>

								 <label for="style_sheet"><input id="style_sheet" name="jps_options[style_sheet]" type="checkbox" value="1" <?php checked( '1', $disable_style_sheet ); ?> /> <?php _e('If checked, no styles will be applied.','wpjps'); ?></label>
						</td>

					</tr>
                    



<?php if($jps_premium): ?>                    



<?php jps_nav_styles(); ?>



<?php endif; ?>                                        







					<tr valign="top">



						<th scope="row">



							<label for="count_position"><?php _e('Count','wpjps'); ?> (e.g. "2 <?php _e('of','wpjps'); ?> 4") <?php _e('Position','wpjps'); ?>:</label>



						</th>



						<td>



							<select name="jps_options[count_position]">



								<option value="top" <?php echo ((jps_get('count_position') == "top") ? 'selected="selected"' : ''); ?>> <?php _e('Top','wpjps'); ?></option>



								<option value="bottom" <?php echo (jps_get('count_position') == "bottom") ? 'selected="selected"' : ''; ?> ><?php _e('Bottom','wpjps'); ?></option>



								<option value="both" <?php echo (jps_get('count_position') == "both") ? 'selected="selected"' : ''; ?> ><?php _e('Both','wpjps'); ?></option>



								<option value="none" <?php echo (jps_get('count_position') == "none") ? 'selected="selected"' : ''; ?> ><?php _e('Do Not Display','wpjps'); ?></option>



							</select>

							<i class="fas fa-ellipsis-h"></i>


						</td>



					</tr>





<?php if($jps_premium): ?>  

<?php if(function_exists('jps_headings')): ?>
<tr valign="top"><th scope="row"><label for="jps_headings_display"><?php _e('Display Post Headings','wpjps'); ?>:</label>
</th><td>
<input name="jps_options[jps_headings_display]" type="checkbox" value="1" <?php checked( '1', jps_get('headings_display') ); ?> /> <?php _e('It will display post sub-heading instead of','wpjps'); ?> Prev/Next.<br />
<small>&lt;h2&gt;&lt;/h2&gt; <?php _e('will be considered as sub-heading inside your content','wpjps'); ?>.</small></td></tr>
<?php endif; ?>
<?php if(function_exists('jps_leftnav')): ?>
<tr valign="top"><th scope="row"><label for="jps_bullets_display"><?php _e('Display Left Navigation','wpjps'); ?>:</label>
</th><td>
<input name="jps_options[jps_bullets_display]" type="checkbox" value="1" <?php checked( '1', jps_get('bullets_display') ); ?> /> <?php _e('It will display post sub-heading in bullets.','wpjps'); ?></td></tr>
<?php endif; ?>
<?php endif; ?> 


<?php if($jps_premium): ?>  
<tr valign="top" class="jps_additional">



						<th scope="row">



							<label for="style_sheet"><?php _e('Custom HTML and/or Styles','wpjps'); ?> / <?php _e('Above Post','wpjps'); ?>:</label>



						</th>



						<td class="jps_css_area">



								<textarea name="jps_custom_section" placeholder="&lt;style type=&quot;text/css&quot;&gt;
  /*YOUR CSS STYLE GOES HERE*/
&lt;/style&gt;

&lt;div class=&quot;jps_custom_section&quot;&gt;
  &lt;!-- YOUR HTML CAN BE HERE AS WELL --&gt;
&lt;/div&gt;
"><?php echo esc_html(get_option('jps_custom_section', true)); ?></textarea>



						</td>



					</tr>
                   
                   
<tr valign="top" class="jps_additional">



						<th scope="row">



							<label for="style_sheet"><?php _e('Custom HTML and/or Styles','wpjps'); ?> / <?php _e('Below Post','wpjps'); ?>:</label>



						</th>



						<td class="jps_css_area">



								<textarea name="jps_custom_section_below" placeholder="&lt;style type=&quot;text/css&quot;&gt;
  /*YOUR CSS STYLE GOES HERE*/
&lt;/style&gt;

&lt;div class=&quot;jps_custom_section&quot;&gt;
  &lt;!-- YOUR HTML CAN BE HERE AS WELL --&gt;
&lt;/div&gt;
"><?php echo esc_html(get_option('jps_custom_section_below', true)); ?></textarea>



						</td>



					</tr>
                    
<?php endif; ?>                                        

<tr valign="top" style="display:none">
						<th scope="row">
							<label for="style_sheet">Adsense <?php _e('code','wpjps'); ?>? (<?php _e('Beta','wpjps'); ?>)</label>
						</th>
						<td class="jps_ads_area">
                        	<b><?php _e('Before','wpjps'); ?> Nav Top?</b><br />
                        	<textarea name="jps_options[jps_before_nav_top]"><?php echo (jps_get('jps_before_nav_top')); ?></textarea><br />
                            <b><?php _e('After','wpjps'); ?> Nav Top?</b><br />
                            <textarea name="jps_options[jps_after_nav_top]"><?php echo (jps_get('jps_after_nav_top')); ?></textarea><br />
                            <b><?php _e('Before','wpjps'); ?> Nav Bottom?</b><br />
                            <textarea name="jps_options[jps_before_nav_bottom]"><?php echo (jps_get('jps_before_nav_bottom')); ?></textarea><br />
                            <b><?php _e('After','wpjps'); ?> Nav Bottom?</b><br />
                            <textarea name="jps_options[jps_after_nav_bottom]"><?php echo (jps_get('jps_after_nav_bottom')); ?></textarea>
                            
							<pre><?php _e('Example','wpjps'); ?>: class="adsbygoogle" style="display:block" data-ad-client="ca-pub-2886921500000019" data-ad-slot="6700000018" data-ad-format="auto"<br /><br /><?php _e('Note: No tags, just attributes.','wpjps'); ?></pre>
						</td>
					</tr>
                    
					<tr valign="top">
						<th scope="row">
                        <div class="jps_additional_link">+<?php _e('Additional Settings','wpjps'); ?> <a href="https://www.youtube.com/embed/NuJYjlrYzFo" target="_blank"><?php _e('Video Tutorial','wpjps'); ?></a></div>
                        <div class="jps_additional_link_revert">- <?php _e('Hide Settings','wpjps'); ?></div>
                        </th>
                        <td>
                        </td>
					</tr>

					



<tr valign="top"style="display:none">

						<th scope="row">


							<label for="post_titles">Post <?php _e('titles as navigation','wpjps'); ?>?</label>


						</th>

						<td>

								<input name="jps_options[post_titles]" type="checkbox" value="1" <?php checked( '1', jps_get('post_titles') ); ?> id="post_titles" /> <?php _e('Want to use heading tag','wpjps'); ?> &lt;h2&gt; <?php _e('as titles to navigate instead of','wpjps'); ?> next, previous?



						</td>
					</tr>



					<tr valign="top" class="jps_additional">



						<th scope="row">



							<label for="show_all_link"><?php _e('Display link to','wpjps'); ?> <em><?php _e('View','wpjps'); ?> Full Post</em>?</label>



						</th>



						<td>



								<input name="jps_options[show_all_link]" type="checkbox" value="1" <?php checked( '1', jps_get('show_all_link') ); ?> /> <?php _e('If unchecked, the','wpjps'); ?> <em><?php _e('View','wpjps'); ?> Full Post</em><?php _e('link will not be displayed','wpjps'); ?>.



						</td>



					</tr>

<?php if($jps_premium): ?>  

				<tr valign="top" class="jps_row ajax page_refresh">



						<th scope="row">



							<label for="show_all_first">SEO <?php _e('Trick','wpjps'); ?>:</label>



						</th>



						<td>



								<input name="jps_options[show_all_first]" type="checkbox" value="1" <?php checked( '1', jps_get('show_all_first') ); ?> /> <?php _e('Display full content on first page as hidden?','wpjps'); ?> <i class="fas fa-clipboard-check"></i>


						</td>



					</tr>

<?php endif; ?>

					<tr valign="top" class="jps_row page_refresh jquery">
						<th scope="row">
							<label for="loop_slides"><?php _e('Loop slides?','wpjps'); ?></label>
						</th>
						<td>
                        	<input name="jps_options[loop_slides]" type="checkbox" value="1" <?php checked( '1', jps_get('loop_slides') ); ?> /> <?php _e('Creates an infinite loop of the slides.','wpjps'); ?> <i class="fas fa-sync"></i>
						</td>
					</tr>
					<tr valign="top" class="jps_row page_refresh">
						<th scope="row">
							<label for="frog_jump"><?php _e('Frog Jump?','wpjps'); ?> <small>(<?php _e('Optional','wpjps'); ?>)</small></label>
						</th>
						<td>
                        	<input name="jps_options[frog_jump]" <?php disabled(!$jps_premium); ?> type="checkbox" value="1" <?php checked( '1', jps_get('frog_jump') ); ?> /> <?php _e('Jump to another post on next button of the last slide/page.','wpjps'); ?> <i class="fas fa-frog"></i> <?php jps_pro_feature(); ?>
						</td>
					</tr>




<tr valign="top" class="jps_additional">



						<th scope="row">



							<label for="br_status"><?php _e('Insert','wpjps'); ?> &lt;br /&gt; <?php _e('with each','wpjps'); ?> return key?</label>



						</th>



						<td>



								<input name="jps_options[br_status]" type="checkbox" value="1" <?php checked( '1', jps_get('br_status') ); ?> /> <?php _e('Insert','wpjps'); ?> line break <?php _e('for each','wpjps'); ?> "enter key".



						</td>



				  </tr>


					<tr valign="top" class="jps_row jquery ajax">



						<th scope="row">



							<label for="scroll_up"><?php _e('Scroll to top of page after','wpjps'); ?> slide <?php _e('load','wpjps'); ?>? (jQuery/Ajax)</label>
							


						</th>



						<td>



								<input name="jps_options[scroll_up]" type="checkbox" value="1" <?php checked( '1', jps_get('scroll_up') ); ?> /><?php _e('Scrolls up to the top of the page after each','wpjps'); ?> slide <?php _e('loads','wpjps'); ?>.

								<input type="text" name="jps_options[scroll_number]" value="<?php echo jps_get('scroll_number'); ?>" placeholder="<?php _e('e.g.','wpjps'); ?> 1" />
                                <span><?php echo __('e.g.','wpjps').' '.__('Fastest','wpjps').' = 0 & '.__('Slow','wpjps').' = 5 '.__('or greater value.', 'wpjps'); ?> <a href="https://www.youtube.com/embed/HYAgiLhql54" target="_blank"><?php _e('Video Tutorial','wpjps'); ?></a></span>

						</td>



					</tr>







				</tbody>



			</table>







			<p>



				<input type="submit" class="button button-primary" value="<?php echo __('Save Settings','wpjps'); ?>">



			</p>







		</form>
        
        
        
        <div class="jps_shortcodes">
            <p><?php _e('Want to combine different','wpjps'); ?> pages/sub-pages <?php _e('or','wpjps'); ?> <?php _e('posts','wpjps'); ?> <?php _e('into one','wpjps'); ?> ? <?php _e('Use','wpjps'); ?> <?php echo __('shortcodes','wpjps'); ?> <?php _e('with','wpjps'); ?> <?php _e('page/post','wpjps'); ?> ID <?php _e('as follows','wpjps'); ?>.</p>
            <pre>[JPS_CHUNK id="62" type="title"]</pre>
            <pre>[JPS_CHUNK id="62" type="content"]</pre>
            <pre>[JPS_CHUNK id="62" type="excerpt"]</pre>
            <p><?php echo __('Instructions: Replace','wpjps').' id="62" '.__('with page/post IDs you want to use as a chunk','wpjps'); ?>.</p>
            <p><?php _e('Note','wpjps'); ?>: Shortcodes <?php _e('are included in premium version','wpjps'); ?>.</p>
            <p><a href="https://www.youtube.com/embed/8AAvtaRwhxo" target="_blank"><?php _e('Video Tutorial','wpjps'); ?></a> / <a href="https://www.youtube.com/embed/RDd6sXctp_U" target="_blank"><?php _e('Video Tutorial','wpjps'); ?></a></p>
        </div>  
	<?php if(!$jps_premium): ?>
	<a target="_blank" href="<?php echo $jps_premium_link; ?>"><img src="<?php echo $jps_url; ?>/images/features-banner.gif" style="display:block; margin:60px auto 0 auto; max-width:90%" /></a>
    <?php endif; ?>
    
    
    </div>
    
    <div class="nav-tab-content hide mt-2">

 
 		<form name="jpps_developer_mode" method="post" action="<?php echo $this_url; ?>">
        	
            <input type="hidden" name="jps_tn" value="<?php echo isset($_GET['t'])?esc_attr($_GET['t']):'0'; ?>" />

			<?php wp_nonce_field( 'jps_nonce_action', 'jps_nonce_action_field' ); ?>

			<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y" />
            
            <label><?php echo __('You may restrict asset (css/js) files loading by entering query strings on separate lines:','wpjps'); ?></label>
            
            <textarea name="jps_restrictions"><?php echo jps_get('jps_restrictions', true); ?></textarea>      
            
			<p><input type="submit" class="button button-primary" value="<?php echo __('Save Settings','wpjps'); ?>" /></p>      
            
            <div class="jps_restriction_examples">
            	<strong><?php echo __('Example URL:','wpjps'); ?></strong> <?php echo home_url(); ?>/2020/02/12/voluptatum-velit-veniam-quasi-veritatis-fuga/<br /><br />
				<strong><?php echo __('Pick a query string from the following and enter in textarea above:','wpjps'); ?></strong> <br />
                <ul>
                	<li>/2020</li>
                    <li>/02</li>
                    <li>/12</li>
                    <li>/voluptatum-velit-veniam-quasi-veritatis-fuga</li>
                </ul>
                <strong><?php echo __('Note:','wpjps'); ?></strong> <?php echo __('One query string per line and beginning with a forward slash will make it unique.','wpjps'); ?> 
            </div>
        </form>
	</div>
    
    
    
    <div class="nav-tab-content hide mt-2">
    
        <div class="jps_style_form_wrapper">
    
            <?php
    
                if(function_exists('jps_navigation_styling_html')){
    
                    $load_style = jps_get('nav_style');
                    jps_navigation_styling_html($load_style);
    
                }else{
    
                    ?>
    
                    <div class="row mt-5">
                        <div class="col-md-12">
                            <div class="alert alert-danger text-center">
                                <?php echo __('This is a premium feature.','wpjps','wpjps'); ?>
                            </div>
                        </div>
                    </div>
    
    
                    <?php
    
    
                }
    
            ?>
    
        </div>
    
    </div>    
            
    <div class="nav-tab-content hide">
        <?php if(function_exists('jps_uninstall_tab_content')){ jps_uninstall_tab_content(); } ?>
    </div>
    
    
    <div class="nav-tab-content container-fluid hide mt-2" data-content="help">
			
            <div class="row mt-3">
        
        	<ul class="position-relative">
            	<li><a class="btn btn-sm btn-info" href="https://wordpress.org/support/plugin/jquery-post-splitter/" target="_blank"><?php _e('Open a Ticket on Support Forums', 'jquery-post-splitter'); ?></a></li>
                <li><a class="btn btn-sm btn-warning" href="http://demo.androidbubble.com/contact/" target="_blank"><?php _e('Contact Developer', 'jquery-post-splitter'); ?></a><i class="fas fa-headset"></i></li>
                <li><a class="btn btn-sm btn-secondary" href="<?php echo $jps_premium_link; ?>/?help" target="_blank"><?php _e('Need Urgent Help?', 'jquery-post-splitter'); ?> &nbsp;<i class="fas fa-phone"></i></i></a></li>
                <li><iframe width="560" height="315" src="https://www.youtube.com/embed/C-ALIaOr7Zo" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></li>
			</ul>                

			</div>
    </div>
	    
              
</div>
<script type="text/javascript" language="javascript">
	function jps_replaceAll(str, find, replace) {
	  return str.replace(new RegExp(find, 'g'), replace);
	}
	jQuery(document).ready(function($){


		<?php if(isset($_POST['jps_tn'])): ?>
			$('.jps-wrapper.wrap .nav-tab:nth-child(<?php echo esc_attr($_POST['jps_tn'])+1; ?>)').click();		
		<?php endif; ?>
		
		if(jps_obj.jps_tab>0){
			setTimeout(function(){
				$('.jps-wrapper.wrap a.nav-tab').eq(jps_obj.jps_tab).click();
			}, 100);
		}
			
		var implementation = 'select[name="jps_options[nav_implementation]"]';
		$(implementation).change(function(){
			var obj = $(implementation).find('option[value="'+$(this).val()+'"]');



			if(obj.length>0){
				
				var val = jps_replaceAll($(this).val().toLowerCase(), ' ', '_');
				var main = '.jps_row';
				var iframe = '.jps_settings_table iframe';				
				if(val!=''){				

					var selector = main+'.'+val;
					var iframe_selector = iframe+'.'+val;

					if($(main).length>0){
						$(main).hide();				
					}
					if($(iframe).length>0){
						$(iframe).hide();				
					}					
					if($(selector).length>0 && !$(selector).is(':visible')){
						$(selector).show();
						
					}
					if($(iframe_selector).length>0 && !$(iframe_selector).is(':visible')){
						$(iframe_selector).show();
					}
					
					

				}else{
					
					if($(main).length>0){
						$(main).hide();				
					}
					if($(iframe).length>0){
						$(iframe).hide();				
					}							
				}
			}
			
		});
		
		if($(implementation).length>0)
		$(implementation).trigger('change');
		
		$('.jps_additional_link').click(function(){
			$(this).hide();
			$('.jps_additional_link_revert').show();
			$('.jps_additional').slideDown();
		});
		$('.jps_additional_link_revert').click(function(){
			$(this).hide();
			$('.jps_additional_link').show();
			$('.jps_additional').hide();
		});		
		$('.jps_premium_link').fadeIn();
		
	});
</script>                
<style type="text/css">
.woocommerce-message, div.error{ display:none; }
</style>