<?php
/**
 * Page builder support
 *
 * @package GreatMag
 */


/**
 * Page builder defaults
 */
add_theme_support( 'siteorigin-panels', array( 
	'margin-bottom' 		=> 0,
	'recommended-widgets' 	=> false,
	'mobile-layout' => true,
	'mobile-width' => 991,
) );

/**
 * Register theme widgets in the page builder
 */
function greatmag_theme_widgets($widgets) {
	$theme_widgets = array(
		'Athemes_Posts_Carousel',
		'Athemes_Featured_Posts_Type_A',
		'Athemes_Single_Category_Posts_A',
		'Athemes_Sidebar_Widget',
		'Athemes_Multiple_Cats_Posts',
		'Athemes_Latest_Posts_Home',
		'Athemes_Most_Popular_Home',
		'Athemes_Video_Posts',
	);
	foreach($theme_widgets as $theme_widget) {
		if( isset( $widgets[$theme_widget] ) ) {
			$widgets[$theme_widget]['groups'] = array('greatmag-theme');
			$widgets[$theme_widget]['icon'] = 'dashicons dashicons-schedule greatmag-builder-color';
		}
	}
	return $widgets;
}
add_filter('siteorigin_panels_widgets', 'greatmag_theme_widgets');

/**
 * Create theme tab in the page builder
 */
function greatmag_theme_widgets_tab($tabs){
	$tabs[] = array(
		'title' => __('GreatMag Widgets', 'greatmag'),
		'filter' => array(
			'groups' => array('greatmag-theme')
		)
	);
	return $tabs;
}
add_filter('siteorigin_panels_widget_dialog_tabs', 'greatmag_theme_widgets_tab', 20);

/**
 * Page builder row options
 */
function greatmag_custom_row_style_fields($fields) {
	$fields['top-padding'] = array(
	    'name'        => __('Top padding', 'greatmag'),
	    'type'        => 'measurement',
	    'group'       => 'layout',
	    'default'		=> '30px',
	    'description' => __('Top padding for this row.', 'greatmag'),
	    'priority'    => 11,
	);
	$fields['bottom-padding'] = array(
	    'name'        => __('Bottom padding', 'greatmag'),
	    'type'        => 'measurement',
	    'group'       => 'layout',
	    'default'		=> '30px',
	    'description' => __('Bottom padding for this row.', 'greatmag'),
	    'priority'    => 12,
	);	
	/*
	$fields['mobile-padding'] = array(
	    'name'        => __('Mobile top/bottom padding', 'greatmag'),
	    'type'        => 'select',
	    'group'       => 'layout',
		'options' => array(
			'0px' 	=> __('0', 'greatmag'),
			'25px' 	=> __('25px', 'greatmag'),
			'50px' 	=> __('50px', 'greatmag'),
			'75px'  => __('75px', 'greatmag'),
			'100px' => __('100px', 'greatmag'),
		),
		'default'	  => '50px',
	    'description' => __('Top and bottom padding for this row on mobile on screen widths < 991px.', 'greatmag'),
	    'priority'    => 13,
	);	
	$fields['overlay'] = array(
	    'name'        => __('Enable row overlay?', 'greatmag'),
	    'type'        => 'checkbox',
	    'group'       => 'design',
	    'description' => __('This adds a semi-transparent overlay. Useful if you have a background image for your row.', 'greatmag'),
	    'priority'    => 14,
	);
	$fields['overlay_color'] = array(
	    'name'        => __('Row overlay color', 'greatmag'),
	    'type'        => 'color',
	    'default'	  => '#000000',
	    'group'       => 'design',
	    'priority'    => 15,
	);	
	$fields['fullscreen_mode'] = array(
	    'name'        => __('Enable fullscreen mode?', 'greatmag'),
	    'type'        => 'checkbox',
	    'group'       => 'layout',
	    'description' => __('This makes your row full browser height. Set Row Layout if you need it full width too.', 'greatmag'),
	    'priority'    => 16,
	);
	*/

  return $fields;
}
add_filter( 'siteorigin_panels_row_style_fields', 'greatmag_custom_row_style_fields');

/**
 * Page builder widget options
 */
/*
function greatmag_custom_widget_style_fields($fields) {
	$fields['content_alignment'] = array(
	    'name'        => __('Content alignment', 'greatmag'),
		'type' 		  => 'select',
	    'group'       => 'design',
		'options' => array(
			'left' => __('Left', 'greatmag'),
			'center' => __('Center', 'greatmag'),
			'right' => __('Right', 'greatmag'),
		),
		'default'	  => 'left',
	    'description' => __('This setting depends on the content, it may or may not work', 'greatmag'),
	    'priority'    => 10,
	);	
	$fields['title_color'] = array(
	    'name'        => __('Widget title color', 'greatmag'),
	    'type'        => 'color',
	    'default'	  => '#2d3142',
	    'group'       => 'design',
	    'priority'    => 11,
	);	
	$fields['headings_color'] = array(
	    'name'        => __('Headings color', 'greatmag'),
	    'type'        => 'color',
	    'default'	  => '#2d3142',
	    'group'       => 'design',
	    'description' => __('This applies to all headings in the widget, except the widget title', 'greatmag'),
	    'priority'    => 12,
	);

  return $fields;
}
add_filter( 'siteorigin_panels_widget_style_fields', 'greatmag_custom_widget_style_fields');
*/

/**
 * Output page builder row options
 */
function greatmag_custom_row_style_attributes( $attributes, $args ) {

	if ( !empty($args['top-padding']) ) {
		$attributes['style'] .= 'padding-top: ' . esc_attr($args['top-padding']) . '; ';
	} else {
		$attributes['style'] .= 'padding-top: 30px; ';
	}
	if ( !empty($args['bottom-padding']) ) {
		$attributes['style'] .= 'padding-bottom: ' . esc_attr($args['bottom-padding']) . '; ';
	} else {
		$attributes['style'] .= 'padding-bottom: 30px; ';
	}	
	/*
	if ( !empty($args['mobile-padding']) ) {
		$attributes['data-mobile-padding'] = esc_attr($args['mobile-padding']);
	} else {
		$attributes['data-mobile-padding'] = '50px';
	}
	if ( !empty($args['overlay']) ) {
    	$attributes['data-overlay'] = 'true';
	}
	if ( !empty($args['fullscreen_mode']) ) {
    	$attributes['data-fullscreen'] = 'true';
	}	
	if ( !empty($args['overlay_color']) ) {
    	$attributes['data-overlay-color'] = esc_attr($args['overlay_color']);		
	}
	*/
    return $attributes;
}
add_filter('siteorigin_panels_row_style_attributes', 'greatmag_custom_row_style_attributes', 10, 2);

/**
 * Output page builder widget options
 */
/*
function greatmag_custom_widget_style_attributes( $attributes, $args ) {

	if ( !empty($args['title_color']) ) {
    	$attributes['data-title-color'] = esc_attr($args['title_color']);		
	}
	if ( !empty($args['headings_color']) ) {
    	$attributes['data-headings-color'] = esc_attr($args['headings_color']);		
	}
	if ( !empty($args['content_alignment']) ) {
		$attributes['style'] .= 'text-align: ' . esc_attr($args['content_alignment']) . ';';
	}	
    return $attributes;
}
add_filter('siteorigin_panels_widget_style_attributes', 'greatmag_custom_widget_style_attributes', 10, 2);
*/


/**
 * Remove defaults
 */
function greatmag_remove_default_so_row_styles( $fields ) {
	unset( $fields['padding'] );
	return $fields;
}
add_filter('siteorigin_panels_row_style_fields', 'greatmag_remove_default_so_row_styles' );
add_filter( 'siteorigin_premium_upgrade_teaser', '__return_false' );

/**
 * Backend styles for the page builder
 */
/*
function greatmag_enqueue_builder_styles($hook) {
    
    if ( 'post.php' != $hook ) {
        return;
    }

    wp_enqueue_script( 'greatmag-builder-scripts', get_template_directory_uri() . '/inc/framework/builder/assets/js/scripts.js', array('jquery'), '', true );
    
    wp_enqueue_style( 'greatmag-builder-styles', get_template_directory_uri() . '/inc/framework/builder/assets/css/styles.css' );

	wp_localize_script('greatmag-builder-scripts', 'greatmag_pb_vars', array(
			'greatmag_pb_help' => __('<em>Tips: 1. click the buttons below to start building your page. 2. To edit the row options, hover over the wrench icon you will find on the right hand side of each row. 3. To edit widget options, hover the desired widget and click the edit button.</em>', 'greatmag')
		)
	);

    wp_enqueue_script('greatmag-media-upload', get_template_directory_uri()  . '/inc/framework/builder/assets/js/media-upload.js', array('jquery'), '', true );

}
add_action( 'admin_enqueue_scripts', 'greatmag_enqueue_builder_styles' );
*/