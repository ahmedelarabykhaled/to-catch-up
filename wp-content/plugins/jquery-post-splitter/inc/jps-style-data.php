<?php

    global $jps_custom_style, $jps_custom_style_default;

    $jps_custom_style_default = array(

        'jps_beach_bar' => array(

            'simple' => array(

                'background' => 'transparent',
                'color' => '#426fc5',
                'border' => '#426fc5',
                'font_size' => '16px',
                'font_family' => 'inherit',
            ),

            'hover' => array(

                'background' => 'transparent',
                'color' => '#426fc5',
                'border' => '#426fc5',
                'font_size' => '16px',
                'font_family' => 'inherit',
            ),


        ),

        'jps_berg' => array(

            'simple' => array(

                'background' => 'transparent',
                'color' => '#ffffff',
                'border' => '#3498db',
                'font_size' => '20px',
                'font_family' => 'Montserrat,sans-serif',
            ),

            'hover' => array(

                'background' => 'transparent',
                'color' => '#3498db',
                'border' => '#3498db',
                'font_size' => '20px',
                'font_family' => 'Montserrat,sans-serif',
            ),


        ),

        'jps_chinese_lantern' => array(

            'simple' => array(

                'background' => 'transparent',
                'color' => '#e74c3c',
                'border' => '#e74c3c',
                'font_size' => '20px',
                'font_family' => 'Montserrat,sans-serif',
            ),

            'hover' => array(

                'background' => 'transparent',
                'color' => '#ffffff',
                'border' => '#e74c3c',
                'font_size' => '20px',
                'font_family' => 'Montserrat,sans-serif',
            ),


        ),

        'jps_elegent' => array(

            'simple' => array(

                'background' => '#3e4347',
                'color' => '#feffff',
                'border' => '#32373b',
                'font_size' => '22px',
                'font_family' => 'inherit',
            ),

            'hover' => array(

                'background' => '#3d4f5d',
                'color' => '#feffff',
                'border' => '#32373b',
                'font_size' => '22px',
                'font_family' => 'inherit',
            ),


        ),

        'jps_milano' => array(

            'simple' => array(

                'background' => 'transparent',
                'color' => '#8e44ad',
                'border' => '#8e44ad',
                'font_size' => '14px',
                'font_family' => 'inherit',
            ),

            'hover' => array(

                'background' => '#8E44AD',
                'color' => '#ffffff',
                'border' => '#8e44ad',
                'font_size' => '14px',
                'font_family' => 'inherit',
            ),


        ),

        'jps_robust' => array(

            'simple' => array(

                'background' => 'transparent',
                'color' => '#65b37a',
                'border' => '#65b37a',
                'font_size' => '16px',
                'font_family' => 'inherit',
            ),

            'hover' => array(

                'background' => '#65b37a',
                'color' => '#ffffff',
                'border' => '#65b37a',
                'font_size' => '16px',
                'font_family' => 'inherit',
            ),


        ),

        'jps_rubber' => array(

            'simple' => array(

                'background' => 'transparent',
                'color' => '#f6774f',
                'border' => '#f6774f',
                'font_size' => '20px',
                'font_family' => 'inherit',
            ),

            'hover' => array(

                'background' => '#f6774f',
                'color' => '#ffffff',
                'border' => '#f6774f',
                'font_size' => '20px',
                'font_family' => 'inherit',
            ),
        ),

        'jps_spotlight' => array(

            'simple' => array(

                'background' => '#e3403a',
                'color' => '#ffffff',
                'border' => '#da251f',
                'font_size' => '16px',
                'font_family' => 'inherit',
            ),

            'hover' => array(

                'background' => '#e02c26',
                'color' => '#ec817d',
                'border' => '#da251f',
                'font_size' => '16px',
                'font_family' => 'inherit',
            ),


        ),

        'jps_tablet' => array(

            'simple' => array(

                'background' => '#2c3e50',
                'color' => '#2c3e50',
                'border' => '#2c3e50',
                'font_size' => '18px',
                'font_family' => 'inherit',
            ),

            'hover' => array(

                'background' => 'transparent',
                'color' => '#2c3e50',
                'border' => '#2c3e50',
                'font_size' => '18px',
                'font_family' => 'inherit',
            ),


        ),

        'jps_yellow_paper' => array(

            'simple' => array(

                'background' => '#fbe48b',
                'color' => '#000000',
                'border' => '#f7ca18',
                'font_size' => '20px',
                'font_family' => 'inherit',
            ),

            'hover' => array(

                'background' => '#f7ca18',
                'color' => '#000000',
                'border' => '#f7ca18',
                'font_size' => '20px',
                'font_family' => 'inherit',
            ),


        ),

        'jps_zipper' => array(

            'simple' => array(

                'background' => '#FFD700',
                'color' => '#000000',
                'border' => 'none',
                'font_size' => '18px',
                'font_family' => 'inherit',
            ),

            'hover' => array(

                'background' => '#e5bc00',
                'color' => '#333333',
                'border' => '#e5bc00',
                'font_size' => '18px',
                'font_family' => 'inherit',
            ),


        ),

    );
	
	if(jps_get('style_buttons')){
    	$jps_custom_style = get_option('jps_custom_style', $jps_custom_style_default);
	}else{
		$jps_custom_style = $jps_custom_style_default;
	}

