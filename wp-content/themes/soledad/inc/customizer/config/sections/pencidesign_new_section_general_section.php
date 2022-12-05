<?php
$options   = [];
$options[] = array(
	'id'       => 'penci_favicon',
	'sanitize' => 'esc_url_raw',
	'type'     => 'soledad-fw-image',
	'label'    => __( 'Upload Favicon', 'soledad' ),
);
$options[] = array(
	'default'     => 'horizontal',
	'label'       => __( 'Featured Images Type:', 'soledad' ),
	'id'          => 'penci_featured_image_size',
	'description' => __( 'This feature does not apply for Featured Sliders and some special places. For featured images on category mega menu items, please select option for it via <strong>Customize > Logo & Header</strong>', 'soledad' ),
	'type'        => 'soledad-fw-radio',
	'sanitize'    => 'penci_sanitize_choices_field',
	'choices'     => array(
		'horizontal' => 'Horizontal Size',
		'square'     => 'Square Size',
		'vertical'   => 'Vertical Size',
		'custom'     => 'Custom',
	)
);
$options[] = array(
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field',
	'label'       => __( 'Auto Get Featured Image from Post Content', 'soledad' ),
	'id'          => 'penci_enable_auto_featured_image',
	'type'        => 'soledad-fw-toggle',
	'description' => __( 'If you\'ve not uploaded the features images for your posts, this option will auto set the first image from the post content OR the Youtube/Vimeo Thumbnail as featured image.', 'soledad' ),
);
$options[] = array(
	'default'     => '',
	'label'       => __( 'Custom Container Width', 'soledad' ),
	'id'          => 'penci_custom_container_w',
	'description' => __( 'Default is 1170px. Minimum is 800px', 'soledad' ),
	'type'        => 'soledad-fw-size',
	'sanitize'    => 'absint',
	'ids'         => array(
		'desktop' => 'penci_custom_container_w',
	),
	'choices'     => array(
		'desktop' => array(
			'min'  => 800,
			'max'  => 10000,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'default'     => '',
	'label'       => __( 'Custom Container Width for Two Sidebars', 'soledad' ),
	'id'          => 'penci_custom_container2_w',
	'description' => __( 'Default is 1400px. Minimum is 800px', 'soledad' ),
	'type'        => 'soledad-fw-size',
	'sanitize'    => 'absint',
	'ids'         => array(
		'desktop' => 'penci_custom_container2_w',
	),
	'choices'     => array(
		'desktop' => array(
			'min'  => 800,
			'max'  => 10000,
			'step' => 1,
			'edit' => true,
			'unit' => 'px',
		),
	),
);
$options[] = array(
	'type'        => 'soledad-fw-text',
	'label'       => __( 'Custom Aspect Ratio for Featured Image', 'soledad' ),
	'id'          => 'penci_general_featured_image_ratio',
	'sanitize'    => 'sanitize_text_field',
	'description' => __( 'The aspect ratio of an element describes the proportional relationship between its width and its height. E.g: <strong>3:2</strong>. Default is 3:2 . This option apply  for <strong>Featured Images Type is Custom</strong>', 'soledad' ),
);
$options[] = array(
	'type'        => 'soledad-fw-text',
	'label'       => __( 'Custom Border Radius for Featured Images', 'soledad' ),
	'id'          => 'penci_image_border_radius',
	'sanitize'    => 'sanitize_text_field',
	'description' => __( 'Fill value for border radius you want here - You can use pixel or percent. E.g:  <strong>10px</strong>  or  <strong>10%</strong>', 'soledad' ),
);

$options[] = array(
	'label'    => __( 'Get Post Views Data From?', 'soledad' ),
	'id'       => 'penci_general_views_meta',
	'type'     => 'soledad-fw-select',
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'choices'  => array(
		''       => 'Default - from The Theme',
		'custom' => 'Custom Post Meta Field',
	)
);
$options[] = array(
	'type'        => 'soledad-fw-text',
	'label'       => __( 'Post Views Meta Key', 'soledad' ),
	'id'          => 'penci_general_views_key',
	'sanitize'    => 'penci_sanitize_choices_field',
	'description' => __( 'Fill the Post Views Meta Key you want to get post views for your posts here. This option applies when you selected "Get Post Views Data From" is "Custom Post Meta Field"', 'soledad' ),
);
$options[] = array(
	'type'        => 'soledad-fw-text',
	'label'       => __( 'Set A Default Reading Time Value', 'soledad' ),
	'id'          => 'penci_readtime_default',
	'description' => __( 'If you want to set a default reading time value, you can put it here. E.g: 3 mins', 'soledad' ),
);
$options[] = array(
	'type'        => 'soledad-fw-toggle',
	'label'       => __( 'Estimate Reading Time base Post Content?', 'soledad' ),
	'id'          => 'penci_readtime_auto',
	'description' => __( 'If you enable this options, it will ignore the value you\'ve added on "Set A Default Reading Time Value"', 'soledad' ),
);
$options[] = array(
	'type'        => 'soledad-fw-number',
	'label'       => __( 'Reading Time: Words Per Minute', 'soledad' ),
	'id'          => 'penci_readtime_wpm',
	'default'     => 200,
	'sanitize'    => 'absint',
	'description' => __( 'This option apply when you enable "Estimate Reading Time base Post Content"', 'soledad' ),
);
$options[] = array(
	'sanitize' => 'penci_sanitize_choices_field',
	'type'     => 'soledad-fw-select',
	'default'  => 'date',
	'label'    => __( 'Sort Posts By', 'soledad' ),
	'id'       => 'penci_general_post_orderby',
	'choices'  => array(
		'date'          => 'Published Date',
		'ID'            => 'Post ID',
		'modified'      => 'Modified Date',
		'title'         => 'Post Title',
		'rand'          => 'Random Posts',
		'comment_count' => 'Comment Count'
	)
);
$options[] = array(
	'sanitize' => 'penci_sanitize_choices_field',
	'default'  => 'DESC',
	'label'    => __( 'Select Posts Order', 'soledad' ),
	'id'       => 'penci_general_post_order',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'DESC' => 'Descending',
		'ASC'  => 'Ascending '
	)
);
$options[] = array(
	'label'       => __( 'Use Outline Social Icons Instead of Filled Social Icons for Your Social Media?', 'soledad' ),
	'description' => __( 'You can check <a href="https://imgresources.s3.amazonaws.com/outline-social.png" target="_blank">this image</a> to understand what difference between outline social icons & filled social icons. Note: some icons doesn\'t support for outline', 'soledad' ),
	'id'          => 'penci_outline_social_icon',
	'type'        => 'soledad-fw-toggle',
);
$options[] = array(
	'label' => __( 'Use Outline Social Icons for Social Sharing?', 'soledad' ),
	'id'    => 'penci_outline_social_share',
	'type'  => 'soledad-fw-toggle',
);
$options[] = array(
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Select Separator Icon Between Posts Datas', 'soledad' ),
	'description' => __( 'You can check <a href="https://imgresources.s3.amazonaws.com/separator-pmeta.png" target="_blank">this image</a> to understand what is "Separator Icon Between Posts Datas".', 'soledad' ),
	'id'          => 'penci_separator_post_meta',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		''         => 'Default ( Vertical Line )',
		'horiline' => 'Horizontal Line',
		'circle'   => 'Circle Filled',
		'bcricle'  => 'Circle Bordered',
		'square'   => 'Square Filled',
		'bsquare'  => 'Square Bordered',
		'diamond'  => 'Diamond Square Filled',
		'bdiamond' => 'Diamond Square Bordered',
	)
);
$options[] = array(
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Style for Post Categories Listing', 'soledad' ),
	'description' => __( 'You can check <a href="https://imgresources.s3.amazonaws.com/cat_design.png" target="_blank">this image</a> to understand Styles for Post Categories Listing. You can change general color for it via General > Colors > Filled Categories Styles.', 'soledad' ),
	'id'          => 'penci_catdesign',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		''      => 'Default',
		'fill'  => 'Filled Rectangle',
		'fillr' => 'Filled Round',
		'fillc' => 'Filled Circle',
	)
);
$options[] = array(
	'sanitize'    => 'penci_sanitize_choices_field',
	'label'       => __( 'Select Separator Icon Between Categories', 'soledad' ),
	'description' => __( 'You can check <a href="https://imgresources.s3.amazonaws.com/separator-cat.png" target="_blank">this image</a> to understand what is "Separator Icon Between Categories". It does not apply to Filled styles above.', 'soledad' ),
	'id'          => 'penci_separator_cat',
	'type'        => 'soledad-fw-select',
	'choices'     => array(
		''         => 'Default ( Diamond Square Bordered )',
		'verline'  => 'Vertical Line',
		'horiline' => 'Horizontal Line',
		'circle'   => 'Circle Filled',
		'bcricle'  => 'Circle Bordered',
		'square'   => 'Square Filled',
		'bsquare'  => 'Square Bordered',
		'diamond'  => 'Diamond Square Filled',
	)
);
$options[] = array(
	'default'  => false,
	'label'    => __( 'Disable Breadcrumb', 'soledad' ),
	'id'       => 'penci_disable_breadcrumb',
	'type'     => 'soledad-fw-toggle',
	'sanitize' => 'penci_sanitize_checkbox_field',
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Display Modified Date Replace with Published Date', 'soledad' ),
	'id'       => 'penci_show_modified_date',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'default'  => '',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Select Date Format', 'soledad' ),
	'id'       => 'penci_date_format',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		''        => 'Default ( By Day, Month & Year )',
		'timeago' => 'Time Ago Format',
	)
);
$options[] = array(
	'default'  => 's9',
	'sanitize' => 'penci_sanitize_choices_field',
	'label'    => __( 'Style for Blocks Loading Ajax', 'soledad' ),
	'id'       => 'penci_block_lajax',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		's9' => 'Style 1',
		's2' => 'Style 2',
		's3' => 'Style 3',
		's4' => 'Style 4',
		's5' => 'Style 5',
		's6' => 'Style 6',
		's1' => 'Style 7',
	)
);
$options[] = array(
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field',
	'label'    => __( 'Enable Smooth Scroll', 'soledad' ),
	'id'       => 'penci_enable_smooth_scroll',
	'type'     => 'soledad-fw-toggle',
);
$options[] = array(
	'label'    => __( 'Enable Page Navigation Numbers', 'soledad' ),
	'id'       => 'penci_page_navigation_numbers',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Page Navigation Numbers Alignment', 'soledad' ),
	'id'       => 'penci_page_navigation_align',
	'type'     => 'soledad-fw-select',
	'choices'  => array(
		'align-left'   => 'Left',
		'align-right'  => 'Right',
		'align-center' => 'Center',
	),
	'default'  => 'align-left',
	'sanitize' => 'penci_sanitize_choices_field'
);
$options[] = array(
	'label'    => __( 'Enable Sticky Sidebar', 'soledad' ),
	'id'       => 'penci_sidebar_sticky',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Disable Sidebar for BBPress Forums', 'soledad' ),
	'id'       => 'penci_dis_sidebar_bbforums',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Disable Sidebar for BBPress Forum', 'soledad' ),
	'id'       => 'penci_dis_sidebar_bbforum',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Disable Sidebar for BBPress Topic', 'soledad' ),
	'id'       => 'penci_dis_sidebar_bbtoppic',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Show Only Primary Category from "Yoast SEO" or "Rank Math" plugin for Breadcrumb', 'soledad' ),
	'id'       => 'enable_pri_cat_yoast_seo',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'       => __( 'Show Primary Category from "Yoast SEO" or "Rank Math" only', 'soledad' ),
	'description' => __( 'If you don\'t use "Yoast SEO" or "Rank Math" plugin, it will be display the first category on the list categories of the posts.', 'soledad' ),
	'id'          => 'penci_show_pricat_yoast_only',
	'type'        => 'soledad-fw-toggle',
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'    => __( 'Show Primary Category from "Yoast SEO" or "Rank Math" at First ( When you display full categories )', 'soledad' ),
	'id'       => 'penci_show_pricat_first_yoast',
	'type'     => 'soledad-fw-toggle',
	'default'  => false,
	'sanitize' => 'penci_sanitize_checkbox_field'
);
$options[] = array(
	'label'       => __( 'Get the Post Excerpt Length based on Number of Letters', 'soledad' ),
	'description' => __( 'It can be helps you fix the problems with the excerpt length on Chinese or Japanese languages.', 'soledad' ),
	'id'          => 'penci_excerptcharac',
	'type'        => 'soledad-fw-toggle',
	'default'     => false,
	'sanitize'    => 'penci_sanitize_checkbox_field'
);

return $options;
