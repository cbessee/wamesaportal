<?php
//* include custom table scripts
// include_once( get_stylesheet_directory() . '/k12tables.php' );
// include_once( get_stylesheet_directory() . '/mccp-tables.php' );

//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Setup Theme
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'education', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'education' ) );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', __( 'Education Pro Theme', 'education' ) );
define( 'CHILD_THEME_URL', 'http://my.studiopress.com/themes/education/' );
define( 'CHILD_THEME_VERSION', '3.0.0' );

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Load main style sheet after Datatables */
remove_action( 'genesis_meta', 'genesis_load_stylesheet' );
add_action( 'wp_enqueue_scripts', 'genesis_enqueue_main_stylesheet', 30 ); //change load priority

//* Enqueue Scripts
add_action( 'wp_enqueue_scripts', 'education_load_scripts' );
function education_load_scripts() {

	wp_enqueue_script( 'education-responsive-menu', get_bloginfo( 'stylesheet_directory' ) . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0' );
	wp_enqueue_script( 'jquery-ui-tooltip' );
	// wp_enqueue_script( 'jquery-ui-autocomplete' );
	wp_enqueue_script( 'backbone' );
	wp_enqueue_script( 'underscore' );
    // wp_enqueue_script( 'datatables', get_bloginfo( 'stylesheet_directory' ) . '/DataTables/datatables.min.js', array( 'jquery' ), '1.0.0' );
    wp_enqueue_script( 'datatables', 'https://cdn.datatables.net/t/ju/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.11,af-2.1.1,b-1.1.2,b-colvis-1.1.2,b-flash-1.1.2,b-html5-1.1.2,b-print-1.1.2,cr-1.3.1,fc-3.2.1,fh-3.1.1,kt-2.1.1,r-2.0.2,rr-1.1.1,sc-1.4.1,se-1.1.2/datatables.min.js', array( 'jquery' ), '1.10.11' );
    wp_enqueue_script( 'jquery-matchHeights', get_bloginfo( 'stylesheet_directory' ) . '/js/jquery.matchHeight.js', array( 'jquery' ), '0.6.0' );

	wp_enqueue_style( 'dashicons' );
    // wp_enqueue_style( 'datatables', get_bloginfo( 'stylesheet_directory' ) . '/DataTables/datatables.min.css');
    wp_enqueue_style( 'datatables', 'https://cdn.datatables.net/t/ju/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.11,af-2.1.1,b-1.1.2,b-colvis-1.1.2,b-flash-1.1.2,b-html5-1.1.2,b-print-1.1.2,cr-1.3.1,fc-3.2.1,fh-3.1.1,kt-2.1.1,r-2.0.2,rr-1.1.1,sc-1.4.1,se-1.1.2/datatables.min.css');
    /* for performance purposes, removed Google Font load. Fonts are now stored locally and accessed through
    style.css - vmf*/
	/* wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,700', array(), CHILD_THEME_VERSION );  */

    /*
	if (method_exists('FrmFormsController','register_pro_scripts')) {
		wp_dequeue_script( 'jquery-chosen');
		wp_deregister_script( 'jquery-chosen');
		wp_enqueue_script( 'jquery-chosen', get_bloginfo( 'stylesheet_directory' ) . '/js/chosen.jquery.min.js', array( 'jquery' ), '1.5.1', true );
	}
    */
	// enqueue css for k12 Enrollment Thank You page
	if ( is_page('206') ) {
    	wp_enqueue_style( 'frmplus-forms' );
    	wp_enqueue_style( 'formidable' );
	}
}

//* Add new image sizes
add_image_size( 'slider', 1600, 800, TRUE );
add_image_size( 'sidebar', 280, 150, TRUE );

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'width'           => 300,
	'height'          => 140,
	'header-selector' => '.site-title a',
	'header-text'     => false,
) );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for additional color style options
add_theme_support( 'genesis-style-selector', array(
	'education-pro-blue'   => __( 'Education Pro Blue', 'education' ),
	'education-pro-green'  => __( 'Education Pro Green', 'education' ),
	'education-pro-red'    => __( 'Education Pro Red', 'education' ),
	'education-pro-purple' => __( 'Education Pro Purple', 'education' ),
) );

//* Add support for 5-column footer widgets
add_theme_support( 'genesis-footer-widgets', 5 );

//* Add support for after entry widget
add_theme_support( 'genesis-after-entry-widget-area' );

//* Add support for accessibility
add_theme_support( 'genesis-accessibility', array( 'headings', 'drop-down-menu', 'search-form', 'skip-links', 'rems' ) );

//* Reposition the primary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_before_header', 'genesis_do_nav' );

//* Reposition the secondary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 7 );

//* Reduce the secondary navigation menu to one level depth
add_filter( 'wp_nav_menu_args', 'education_secondary_menu_args' );
function education_secondary_menu_args( $args ){

	if( 'secondary' != $args['theme_location'] )
	return $args;

	$args['depth'] = 1;
	return $args;

}

//* Reposition the entry meta in the entry header
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
add_action( 'genesis_entry_header', 'genesis_post_info', 5 );

//* Customize the entry meta in the entry header
add_filter( 'genesis_post_info', 'education_post_info_filter' );
function education_post_info_filter($post_info) {
	$post_info = '[post_date]';
	return $post_info;
}

//* Customize the entry meta in the entry footer
add_filter( 'genesis_post_meta', 'education_post_meta_filter' );
function education_post_meta_filter($post_meta) {
	$post_meta = 'Article by [post_author_posts_link] [post_categories before=" &#47; "] [post_tags before=" &#47; "] [post_comments] [post_edit]';
	return $post_meta;
}

//* Relocate after post widget
remove_action( 'genesis_after_entry', 'genesis_after_entry_widget_area' );
add_action( 'genesis_after_entry', 'genesis_after_entry_widget_area', 5 );

//* Modify the size of the Gravatar in the author box
add_filter( 'genesis_author_box_gravatar_size', 'parallax_author_box_gravatar' );
function parallax_author_box_gravatar( $size ) {

	return 96;

}

//* Remove comment form allowed tags
add_filter( 'comment_form_defaults', 'education_remove_comment_form_allowed_tags' );
function education_remove_comment_form_allowed_tags( $defaults ) {

	$defaults['comment_notes_after'] = '';
	return $defaults;

}

//* Register widget areas
genesis_register_sidebar( array(
	'id'          => 'home-featured',
	'name'        => __( 'Home - Featured', 'education' ),
	'description' => __( 'This is the featured section of the Home page.', 'education' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-top',
	'name'        => __( 'Home - Top', 'education' ),
	'description' => __( 'This is the top section of the Home page.', 'education' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-middle',
	'name'        => __( 'Home - Middle', 'education' ),
	'description' => __( 'This is the middle section of the Home page.', 'education' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-bottom',
	'name'        => __( 'Home - Bottom', 'education' ),
	'description' => __( 'This is the bottom section of the Home page.', 'education' ),
) );

/* this is custom code required for Formidable Pro 2.0.9 and higher */
/* if you use the Squelch Tabs and Accordions plugin */
/* to create a tabbed interface for your forms */
/* add this to the bottom of your theme's functions.php file. */

add_filter( 'frm_filter_final_form', 'filter_squelch_tabs' );

function filter_squelch_tabs( $form ) {
	global $shortcode_tags;
	$original_shortcodes = $shortcode_tags;
	$limited_shortcodes = array(
  		'tabs' => $shortcode_tags['tabs'],
  		'subtabs' => $shortcode_tags['subtabs'],
  		'subsubtabs' => $shortcode_tags['subsubtabs'],
  		'tab' => $shortcode_tags['tab'],
  		'subtab' => $shortcode_tags['subtab'],
  		'subsubtab' => $shortcode_tags['subsubtab'],
  		'toggles' => $shortcode_tags['toggles'],
  		'subtoggles' => $shortcode_tags['subtoggles'],
  		'subsubtoggles' => $shortcode_tags['subsubtoggles'],
  		'toggle' => $shortcode_tags['toggle'],
  		'subtoggle' => $shortcode_tags['subtoggle'],
  		'subsubtoggle' => $shortcode_tags['subsubtoggle'],
  		'accordions' => $shortcode_tags['accordions'],
  		'subaccordions' => $shortcode_tags['subaccordions'],
  		'subsubaccordions' => $shortcode_tags['subsubaccordions'],
  		'accordion' => $shortcode_tags['accordion'],
  		'subaccordion' => $shortcode_tags['subaccordion'],
  		'subsubaccordion' => $shortcode_tags['subsubaccordion'],
  		'haccordions' => $shortcode_tags['haccordions'],
  		'subhaccordions' => $shortcode_tags['subhaccordions'],
  		'subsubhaccordions' => $shortcode_tags['subsubhaccordions'],
  		'haccordion' => $shortcode_tags['haccordion'],
  		'subhaccordion' => $shortcode_tags['subhaccordion'],
  		'subsubhaccordion' => $shortcode_tags['subsubhaccordion'] );
	$shortcode_tags = $limited_shortcodes;
	$form = do_shortcode( $form );
	$shortcode_tags = $original_shortcodes;
	return $form;
}
add_filter( 'frm_do_html_shortcodes', '__return_false' );

/*  this block of code customizes the search form data from entries field
    so that it display all of the identifying information for a student's
    enrollment form - added by vmf
*/
add_filter('frm_setup_new_fields_vars', 'customize_dfe', 25, 2);
function customize_dfe($values, $field){
    // K12 Enrollment (185) and Senior Survey (352) District Name/District Code Display Value
	if($field->id == 185 || $field->id == 352){
		$temp_values = $values;
		$temp_values['form_select'] = 127;
		$field2_opts = FrmProFieldsHelper::get_linked_options($temp_values, $field);
	  	foreach($values['options'] as $id => $v){
			$values['options'][$id] .= '/' . $field2_opts[$id];
	  	}
	}
	// WAMESA K12 Enrollment Search Filter
	// this creates the Lastname, Firstname | District Student ID drop down
	if( $field->id == 320 && !empty( $values['options'] ) ){
		$temp_values = $values;
		$temp_values['form_select'] = 190;
		$field3_opts = FrmProFieldsHelper::get_linked_options($temp_values, $field);
		$temp_values['form_select'] = 193;
		$field2_opts = FrmProFieldsHelper::get_linked_options($temp_values, $field);
	  	foreach($values['options'] as $id => $v){
			$sep2 = ($field2_opts[$id] !== null && $field2_opts[$id] !== "") ? ", " : "";
			$sep3 = ($field3_opts[$id] !== null && $field3_opts[$id] !== "") ? " | District Student ID: " : "";
			$values['options'][$id] .= $sep2 . $field2_opts[$id] . $sep3 . $field3_opts[$id];
	  	}
	}
	/* Center K12 Enrollment Search Filter
	 * this creates the Lastname, Firstname | District Student ID drop down
	 * and filters the drop down by Center Name where enrollment center name = user meta center name
    */
    //TODO test for [user_meta_key=center] vs. [frm-field-value field_id=170 user_id=current]
	if( $field->id == 331 && !empty( $values['options'] ) ){
    	$temp_center = do_shortcode('[frm-field-value field_id=170 user_id=current]');
    	// set this to Spokane MESA for testing purposes
    	$user_center = (empty($temp_center) ? 'Spokane MESA' : $temp_center);
		$temp_values = $values;
		$temp_values['form_select'] = 190;
		$field3_opts = FrmProFieldsHelper::get_linked_options($temp_values, $field);
		$temp_values['form_select'] = 193;
		$field2_opts = FrmProFieldsHelper::get_linked_options($temp_values, $field);
        $temp_values['form_select'] = 183;
		$center_filter = FrmProFieldsHelper::get_linked_options($temp_values, $field);
	  	foreach($values['options'] as $id => $v){
    	  	if ( empty($v) || $center_filter[$id] == $user_center ) {
    			$sep2 = ($field2_opts[$id] !== null && $field2_opts[$id] !== "") ? ", " : "";
    			$sep3 = ($field3_opts[$id] !== null && $field3_opts[$id] !== "") ? " | District Student ID: " : "";
    			$values['options'][$id] .= $sep2 . $field2_opts[$id] . $sep3 . $field3_opts[$id];
            } else {
                unset( $values['options'][$id] );
            }
	  	}
	}
	if($field->id == 4358){//Replace 125 with the ID of your data from entries field
		global $frm_field;
		$values['form_select'] = 3664;
		$field4_opts = FrmProFieldsHelper::get_linked_options($values, $field);
		$values['form_select'] = 3663;
		$field3_opts = FrmProFieldsHelper::get_linked_options($values, $field);
		$values['form_select'] = 3665;
		$field2_opts = FrmProFieldsHelper::get_linked_options($values, $field);
		foreach($values['options'] as $id => $v){
			$sep2 = ($field2_opts[$id] !== null && $field2_opts[$id] !== "") ? ", " : "";
			$sep3 = ($field3_opts[$id] !== null && $field3_opts[$id] !== "") ? " | SSN: " : "";
			$sep4 = ($field4_opts[$id] !== null && $field4_opts[$id] !== "") ? " | Student ID: " : "";
			$values['options'][$id] .= $sep2 . $field2_opts[$id] . $sep3 . $field3_opts[$id] . $sep4 . $field4_opts[$id];
	  	}
	}
	// k-12 faculty search form
	if($field->id == 4379){//Replace 125 with the ID of your data from entries field
		global $frm_field;
		$values['form_select'] = 3592;
		$field4_opts = FrmProFieldsHelper::get_linked_options($values, $field);
		$values['form_select'] = 3590;
		$field3_opts = FrmProFieldsHelper::get_linked_options($values, $field);
		$values['form_select'] = 3582;
		$field2_opts = FrmProFieldsHelper::get_linked_options($values, $field);
		foreach($values['options'] as $id => $v){
			$sep2 = ($field2_opts[$id] !== null && $field2_opts[$id] !== "") ? ", " : "";
			$sep3 = ($field3_opts[$id] !== null && $field3_opts[$id] !== "") ? " | Daytime Phone: " : "";
			$sep4 = ($field4_opts[$id] !== null && $field4_opts[$id] !== "") ? " | Email: " : "";
			$values['options'][$id] .= $sep2 . $field2_opts[$id] . $sep3 . $field3_opts[$id] . $sep4 . $field4_opts[$id];
	  	}
	}
	// MCCP faculty search form
	if($field->id == 4391){//Replace 125 with the ID of your data from entries field
		global $frm_field;
		$values['form_select'] = 3942;
		$field4_opts = FrmProFieldsHelper::get_linked_options($values, $field);
		$values['form_select'] = 3940;
		$field3_opts = FrmProFieldsHelper::get_linked_options($values, $field);
		$values['form_select'] = 3933;
		$field2_opts = FrmProFieldsHelper::get_linked_options($values, $field);
		foreach($values['options'] as $id => $v){
			$sep2 = ($field2_opts[$id] !== null && $field2_opts[$id] !== "") ? ", " : "";
			$sep3 = ($field3_opts[$id] !== null && $field3_opts[$id] !== "") ? " | Daytime Phone: " : "";
			$sep4 = ($field4_opts[$id] !== null && $field4_opts[$id] !== "") ? " | Email: " : "";
			$values['options'][$id] .= $sep2 . $field2_opts[$id] . $sep3 . $field3_opts[$id] . $sep4 . $field4_opts[$id];
	  	}
	}
    
    /* remove duplicates from K12 Teacher Profile District Name/Code dropdown */
    if($field->id == 455 and is_array($values['options'])){ //change 125 to the ID of your data from entries field
        $values['options'] = array_unique($values['options']);
    }
    
	return $values;
}

/* Allow admins to see the data for K12-Center View/Edit */
add_filter('frm_where_filter', 'stop_filter_for_admin', 10, 2);
function stop_filter_for_admin( $where, $args ) {
    if ( $args['display']->ID == 182 && $args['where_opt'] == 183 ) {
        if ( current_user_can('administrator') ) {
            // print_r($args);
            $search_val = $args['where_val'];
            if ( empty($search_val) ) {
	            // $where = "(meta_value = 'Spokane MESA' and fi.id = 183)";
            }
        }
    }
    return $where;
}

/* allow excerpts to retain their HTML tags */
add_filter( 'get_the_content_limit_allowedtags', 'get_the_content_limit_custom_allowedtags' );
/**
* @author Brad Dalton
* @example http://wp.me/p1lTu0-a5w
*/
function get_the_content_limit_custom_allowedtags() {
	// Add custom tags to this string
	return '<script>,<style>,<br>,<em>,<i>,<ul>,<ol>,<li>,<a>';
}

/**
 * URL Shortcode
 * @author Bill Erickson
 * @link http://www.billerickson.net/wordpress-shortcode-sidebar-widget/
 * @return Site URL.
 */
function url_shortcode() {
	return get_bloginfo('url');
}
add_shortcode('url','url_shortcode');

// Shortcodes in Widgets
add_filter('widget_text', 'do_shortcode');

/* replace howdy with welcome */
add_filter( 'admin_bar_menu', 'replace_howdy', 25 );
function replace_howdy( $wp_admin_top_bar ) {
    $wp_my_account=$wp_admin_top_bar->get_node('my-account');
    /* change 'Welcome' below to your choice of word */
    $replacetitle = str_replace( 'Howdy', 'Welcome', $wp_my_account->title );
    $wp_admin_top_bar->add_node( array(
        'id' => 'my-account',
        'title' => $replacetitle,
    ) );
}

//* Remove page title for a specific page (requires HTML5 theme support)
//* Change '28' to your page id
add_action( 'get_header', 'child_remove_page_titles' );
function child_remove_page_titles() {

	//* 62 = Login/Logout

	$pages = array( 62 );
	if ( is_page( $pages ) ) {
		remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
	}
}

//* Add user to correct security group when registration is activated
add_action('frm_after_create_entry', 'vmf_assign_security_group', 40, 2);
function vmf_assign_security_group ( $entry_id, $form_id ) {
	if( $form_id == 12 ) {
		$user = get_user_by( 'login', $_POST['item_meta'][160] );
		$user_id = $user->ID;
		echo "user_id: $user_id";
		if ($user_id != null) {
			$key = 'security_group';
			$single = true;
			$security_group = $_POST['item_meta'][171];
			if ( $group = Groups_Group::read_by_name( $security_group ) ) {
				$result = Groups_User_Group::create( array( "user_id"=>$user_id, "group_id"=>$group->group_id ) );
			}
		}
	}
}

add_filter('frmreg_new_role', 'frmreg_new_role', 10, 2);
function frmreg_new_role($role, $atts) {

	if ( $atts['form']->id == 12 && isset( $atts['entry'] ) ) {
		$user_role = FrmProEntriesController::get_field_value_shortcode( array( 'field_id' => 176, 'entry_id' => $atts['entry']->id, 'show' => 'value' ) );
		return $user_role;
	}
}

add_shortcode('groups_users_list_group', 'groups_users_list_group');
function groups_users_list_group( $atts, $content = null ) {
	$output = "";
	$options = shortcode_atts(
			array(
					'group_id' => null
			),
			$atts
	);
	if ($options['group_id']) {
		$group = new Groups_Group($options['group_id']);
		if ($group) {
			$users = $group->__get("users");
			if (count($users)>0) {
				foreach ($users as $group_user) {
					$user = $group_user->user;
					$user_info = get_userdata($user->ID);

					$output .= $user_info->ID . "-" . $user_info-> user_lastname .  ", " . $user_info-> user_firstname . "<br>";
      			}
			}
		}
	}
	echo $output;
}

add_filter('genesis_edit_post_link', 'restrict_edit_link', 10, 2);
function restrict_edit_link() {
    $user = wp_get_current_user();
    $allowed_roles = array('administrator');
    if(array_intersect($allowed_roles, $user->roles)) {
        return true;
    } else {
        return false;
    }
}
