<?php
/*This is the child theme's PHP Functions file. This file is loaded in addition to the original theme's functions.php file*/

include_once( get_stylesheet_directory() . '/k12tables.php' );
include_once( get_stylesheet_directory() . '/mccp-tables.php' );

function twentyfourteen_child_scripts() {
    // scripts for sortable tables
    wp_enqueue_style( 'sortable-theme', get_stylesheet_directory_uri() . '/sortable-theme-finder.css', array(), '0.6.0' );
    wp_enqueue_script( 'sortable', get_stylesheet_directory_uri() . '/js/sortable.min.js', array( 'jquery' ), '0.6.0', true );
    wp_enqueue_style( 'datatables', 'https://depts.washington.edu/mportal/wordpress/wp-content/datatables/media/css/jquery.dataTables.css');
    wp_enqueue_style( 'tabletools', 'https://depts.washington.edu/mportal/wordpress/wp-content/datatables/extensions/TableTools/css/dataTables.tableTools.css');
    wp_enqueue_script( 'datatables', 'https://depts.washington.edu/mportal/wordpress/wp-content/datatables/media/js/jquery.dataTables.js');
    wp_enqueue_script( 'tabletools', 'https://depts.washington.edu/mportal/wordpress/wp-content/datatables/extensions/TableTools/js/dataTables.tableTools.js');
}
add_action( 'wp_enqueue_scripts', 'twentyfourteen_child_scripts' );

/*  this block of code customizes the search form data from entries field
    so that it display all of the identifying information for a student's
    enrollment form
*/
add_filter('frm_setup_new_fields_vars', 'customize_dfe', 25, 2);
function customize_dfe($values, $field){
if($field->id == 4324){//Replace 125 with the ID of your data from entries field
  global $frm_field;
  $values['form_select'] = 3441;
  $field4_opts = FrmProFieldsHelper::get_linked_options($values, $field);
  $values['form_select'] = 3428;
  $field3_opts = FrmProFieldsHelper::get_linked_options($values, $field);
  $values['form_select'] = 3430;
  $field2_opts = FrmProFieldsHelper::get_linked_options($values, $field);
  foreach($values['options'] as $id => $v){
    $sep2 = ($field2_opts[$id] !== null && $field2_opts[$id] !== "") ? ", " : "";
    $sep3 = ($field3_opts[$id] !== null && $field3_opts[$id] !== "") ? " | SSN: " : "";
    $sep4 = ($field4_opts[$id] !== null && $field4_opts[$id] !== "") ? " | State ID: " : "";
    $values['options'][$id] .= $sep2 . $field2_opts[$id] . $sep3 . $field3_opts[$id] . $sep4 . $field4_opts[$id];
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
return $values;
}

// Disable the admin bar for non admin users
add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
  show_admin_bar(false);
}
}

// Disable select items from the admin bar
function remove_admin_bar_links() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('my-account');
	$wp_admin_bar->remove_menu('search');
}
add_action( 'wp_before_admin_bar_render', 'remove_admin_bar_links' );

// Customize the meta widget
function remove_meta_widget() {
    unregister_widget('WP_Widget_Meta');
    register_widget('WP_Widget_Meta_Mod');
}

add_action( 'widgets_init', 'remove_meta_widget' );

/**
 * Meta widget class
 *
 * Displays log in/out, RSS feed links, etc.
 *
 * @since 2.8.0
 */
class WP_Widget_Meta_Mod extends WP_Widget {

function __construct() {
$widget_ops = array('classname' => 'widget_meta', 'description' => __( "Log in/out, admin, feed and WordPress links") );
parent::__construct('meta', __('Meta'), $widget_ops);
}

function widget( $args, $instance ) {
extract($args);
$title = apply_filters('widget_title', empty($instance['title']) ? __('Meta') : $instance['title'], $instance, $this->id_base);

echo $before_widget;
if ( $title )
echo $before_title . $title . $after_title;
?>
<ul>
<?php wp_register(); ?>
<li><?php wp_loginout(); ?></li>
<?php wp_meta(); ?>
</ul>
<?php
echo $after_widget;
}

function update( $new_instance, $old_instance ) {
$instance = $old_instance;
$instance['title'] = strip_tags($new_instance['title']);

return $instance;
}

function form( $instance ) {
$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
$title = strip_tags($instance['title']);
?>
<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<?php
}
}

?>