// JavaScript Document
jQuery(document).ready(function($){
	
	$('body').on('click', '#style_sheet', function(){
		if($(this).is(':checked')){
			$('.use-stylesheet').addClass('disable-use-stylesheet');
		}else{
			$('.use-stylesheet').removeClass('disable-use-stylesheet');
		}
	});
	
	
	$('.mce-ico.mce-i-jpsPPBtnBtn').on('click', function(){
		
	});
	
	
	if($('select[name^="jps_options[jps_nav_type_"]').length>0){
		$('select[name^="jps_options[jps_nav_type_').on('change keyup', function(){
			if($('div#jps_metabox_id iframe').length>0){
				$('div#jps_metabox_id iframe').hide();
				$('div#jps_metabox_id iframe[data-val="'+$(this).val()+'"]').show();	
			}else{
				$('div#jps_metabox_id div.inside > a').hide();
				$('div#jps_metabox_id div.inside > a[data-val="'+$(this).val()+'"]').show();	
			}
			
			
		});
		$('select[name^="jps_options[jps_nav_type_"]').trigger('change');
		
	}
		
	if($('select[name="jps_options[nav_style]"]').length>0){
		$('select[name="jps_options[nav_style]"]').on('change keyup', function(){
			var obj = $(this);
			var jps_preview_url = jps_url+obj.val().replace('jps_', '')+'.png';
			$('.jps_preview img').attr('src', jps_preview_url).show();
			$('.jps_preview a').attr({'href': jps_preview_url, 'title': $(this).find('option:selected').text()});
			//console.log(jps_preview_url);
		});
		
		
		$('select[name="jps_options[nav_style]"]').trigger('change');
		$('.jps_settings_table .jps_preview').show();
	}
	
	$('.jps_styles_preview').on('click', function(){
		
		$(this).toggleClass( "small" );
	});	
	$('.jps-wrapper.wrap a.nav-tab').click(function(){
		$(this).siblings().removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');
		$('.nav-tab-content').hide();
		$('.nav-tab-content').eq($(this).index()).show();
		window.history.replaceState('', '', jps_obj.this_url+'&t='+$(this).index());
		$('form input[name="jps_tn"]').val($(this).index());
		jps_obj.jps_tab = $(this).index();
		$('.wrap.jps-wrapper').attr('class', function(i, c){
    		var cstr = c.replace(/(^|\s)tab-\S+/g, '');
			console.log(cstr);
			cstr += ' tab-'+jps_obj.jps_tab;
			console.log(cstr);
			return cstr;
		});
	});
	
	


	$('body').on('click','.jps-wrapper .clear_page_break', function(e){

		e.preventDefault();
		var type = $(this).data('type');
		var total = $(this).data('total');
		var alert_success = $('.job_clear_success');
		var alert_text = $('.job_clear_success .alert_text');
		var total_alert_text;
		var load_modal = $('#jps_load_modal');

		switch (type) {

			case "post":
				total_alert_text = jps_obj.total_post_str;
				alert_text.text(jps_obj.success_post);
				break;

			case "page":
				total_alert_text = jps_obj.total_page_str;
				alert_text.text(jps_obj.success_page);
				break;

		}


		if( total > 0){

			var confirm_clear = confirm(jps_obj.confirm_clear_str);

			if(confirm_clear){

				load_modal.show();
				var data = {

					action : 'jps_clear_page_break',
					jps_clear_page_break_type : type,
					nonce : jps_obj.clear_page_break_nonce,
				};


				$.post(ajaxurl, data, function(response, code){

					if(code == 'success'){

						load_modal.hide();
						if(response.status == 'true'){


							alert_success.show();
							$('.jps_clear_block').html(response.new_clear_block);
							setTimeout(function () {
								alert_success.hide();

							}, 5000);
						}

					}

				});

			}

		}else{

			alert(total_alert_text);
		}



	});

	$('body').on('change','.jps_nav_style_row select.styles', function(){
	
		var jps_spinner = $('.jps_spinner_col');
	
		jps_spinner.show();
		var request_data = {
	
			action : 'jps_get_navigation_style_html',
			jps_load_style : $(this).val(),
			jps_nonce : jps_obj.nonce
	
		}
	
	
		var this_url = new URL(window.location.href);
		var search = this_url.searchParams;
		var tab = search.get('t');
	
	
	
		$.post(ajaxurl, request_data, function(resp){
	
			if(resp.status){
	
				$('.jps_style_form_wrapper').html(resp.html_code);
	
			}
	
			$('body').find('input[name="jps_tn"]').val(tab);
	
		});
	
	});
	
	$('body').on('change', '.jps_color_input', function(){
	
		var this_parent = $(this).parents('.form-group:first');
		this_parent.find('.jps_color_text').val($(this).val());
	
	});
	
	$('body').on('change', '.jps_color_text', function(){
	
		var this_parent = $(this).parents('.form-group:first');
		this_parent.find('.jps_color_input').val($(this).val());
	
	});
	
	$('body').on('click','.jps_reset_style', function(e){
		e.preventDefault();
		var reset = $(this).data('reset');
		var confirm_reset = confirm(jps_obj.reset_confirm);
		if(confirm_reset){
			if(reset == 'selected'){
				$('body').find('[name="jps_reset_selected"]').click();
			}else if(reset == 'all'){
				$('body').find('[name="jps_reset_all"]').click();
			}
		}
	});
	
	$('body').on('click', '#style_buttons', function(){
		if($(this).is(':checked')){
			$('.jps_nav_style_row').show();
		}else{
			$('.jps_nav_style_row').hide();
		}
	});
});