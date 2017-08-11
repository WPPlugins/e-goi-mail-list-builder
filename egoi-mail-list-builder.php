<?php
/**
Plugin Name: e-goi Mail List Builder
Description: Mail list database populator
Version: 1.0.8
Author: Indot
Author URI: http://indot.pt
Plugin URI: http://indot.pt/egoi-mail-list-builder.zip
License: GPLv2 or later
**/

/**  
	Copyright 2013  Indot  (email : info@indot.pt)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
**/

/**
 * Define some useful constants
**/
define('EGOI_MAIL_LIST_BUILDER_VERSION', '1.0.8');
define('EGOI_MAIL_LIST_BUILDER_DIR', plugin_dir_path(__FILE__));
define('EGOI_MAIL_LIST_BUILDER_URL', plugin_dir_url(__FILE__));
define('EGOI_MAIL_LIST_BUILDER_PLUGIN_KEY', 'ea5199d064c05237745156d5e4b82ef2');
define('EGOI_MAIL_LIST_BUILDER_API_KEY', '');
define('EGOI_MAIL_LIST_BUILDER_XMLRPC_URL', 'http://api.e-goi.com/v2/xmlrpc.php');
define('EGOI_MAIL_LIST_BUILDER_AFFILIATE',' http://bo.e-goi.com/?action=registo&cID=232&aff=267d5afc22');

/**
 * Load files
**/
function egoi_mail_list_builder_activation() {
	set_include_path(EGOI_MAIL_LIST_BUILDER_DIR.'library/'. PATH_SEPARATOR . get_include_path());
	require_once(EGOI_MAIL_LIST_BUILDER_DIR.'includes/class.xmlrpc.php');
	require_once(EGOI_MAIL_LIST_BUILDER_DIR.'library/Zend/XmlRpc/Client.php');
    if(is_admin()) {
        require_once(EGOI_MAIL_LIST_BUILDER_DIR.'includes/admin.php');
	}
	
	require_once(EGOI_MAIL_LIST_BUILDER_DIR.'includes/class.egoi_mail_list_builder.php');
	$EgoiMailListBuilder = get_option('EgoiMailListBuilderObject');
	if($EgoiMailListBuilder) {
		if($EgoiMailListBuilder->isAuthed())	{
			require_once(EGOI_MAIL_LIST_BUILDER_DIR.'egoi-widget.php');
		}
	}

}
egoi_mail_list_builder_activation();

/**
 * Activation, Deactivation and Uninstall Functions
**/
register_activation_hook(__FILE__, 'egoi_mail_list_builder_activation');
register_deactivation_hook(__FILE__, 'egoi_mail_list_builder_deactivation');


function egoi_mail_list_builder_register_scripts() {
    wp_enqueue_style( 'egoi-mail-list-builder-admin-css', EGOI_MAIL_LIST_BUILDER_URL . 'assets/css/admin.css' );
}
add_action( 'admin_enqueue_scripts', 'egoi_mail_list_builder_register_scripts' );


function egoi_mail_list_builder_settings_plugin_link( $links, $file)
{
	if($file == plugin_basename(EGOI_MAIL_LIST_BUILDER_DIR.'/egoi-mail-list-builder.php')){
		$in = '<a href="admin.php?page=egoi-mail-list-builder-info">Settings</a>';
        array_unshift($links, $in);
	}
    return $links;
}
add_filter( 'plugin_action_links', 'egoi_mail_list_builder_settings_plugin_link', 10, 2 );

/**
 * Plugin deactivation code
**/
function egoi_mail_list_builder_deactivation() {  
	//delete_option('EgoiMailListBuilderObject');
}

function egoi_mail_list_builder_fields_logged_in($fields) {
	$EgoiMailListBuilder = get_option('EgoiMailListBuilderObject');
	if($EgoiMailListBuilder->subscribe_enable){
		global $current_user;
		get_currentuserinfo();
		$status = $EgoiMailListBuilder->checkSubscriber($EgoiMailListBuilder->subscribe_list, $current_user->user_email);
		if($status == -1){
    		$fields .= "<input type='checkbox' name='egoi_mail_list_builder_subscribe' id='egoi_mail_list_builder_subscribe' value='subscribe' checked/> ".$EgoiMailListBuilder->subscribe_text;
    	}
	}
    return $fields;
}
add_filter('comment_form_logged_in','egoi_mail_list_builder_fields_logged_in');


function egoi_mail_list_builder_fields_logged_out($fields) {
	$EgoiMailListBuilder = get_option('EgoiMailListBuilderObject');
	if($EgoiMailListBuilder->subscribe_enable){
    	$fields["subscribe"] = "<input type='checkbox' name='egoi_mail_list_builder_subscribe' id='egoi_mail_list_builder_subscribe' value='subscribe' checked/> ".$EgoiMailListBuilder->subscribe_text;
	}
    return $fields;
}
add_filter('comment_form_default_fields','egoi_mail_list_builder_fields_logged_out');

function egoi_mail_list_builder_comment_process($commentdata) {
    if(isset($_POST['egoi_mail_list_builder_subscribe'])){
    	if($_POST['egoi_mail_list_builder_subscribe'] == "subscribe"){
    		//die();
    		$EgoiMailListBuilder = get_option('EgoiMailListBuilderObject');
			$result = $EgoiMailListBuilder->addSubscriber(
				$EgoiMailListBuilder->subscribe_list,
				$commentdata['comment_author'],
				'',
				$commentdata['comment_author_email']
			);
    	}
    }
    return $commentdata;
}
add_filter( 'preprocess_comment', 'egoi_mail_list_builder_comment_process' );

function egoi_mail_list_builder_register_user_scripts($hook) {
	wp_enqueue_script( 'jquery');
	wp_enqueue_script( 'jquery-ui-datepicker');
	wp_enqueue_style( 'indot-jquery-ui-css', EGOI_MAIL_LIST_BUILDER_URL . 'assets/css/jquery-ui.min.css');
	wp_enqueue_script( 'canvas-loader', EGOI_MAIL_LIST_BUILDER_URL . 'assets/js/heartcode-canvasloader-min.js');
}
add_action( 'wp_enqueue_scripts', 'egoi_mail_list_builder_register_user_scripts' );

function egoi_mail_list_builder_shortcode_widget_area() {
    register_sidebar( array(
        'name' => __( 'Egoi Widget Shortcode Area', 'egoi_mail_list_builder_shortcode_widget_area' ),
        'id' => 'header-sidebar',
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h1>',
        'after_title' => '</h1>',
    ) );
}
add_action( 'widgets_init', 'egoi_mail_list_builder_shortcode_widget_area' );

function egoi_mail_list_builder_shortcode($atts){
    extract(shortcode_atts(array(
        'widget_index' => FALSE
    ), $atts));

    $widget_index = wp_specialchars($widget_index);

    ob_start();
    $widgets = dynamic_sidebar("Egoi Widget Shortcode Area");
    if($widgets){
        $html = ob_get_contents();
        $widgets_array = explode("<div>",$html);
        $final_html = "<div>".$widgets_array[$widget_index];
    }
    else{
        $final_html = "";
    }
    ob_end_clean();
    return $final_html;
}
add_shortcode( 'egoi_subscribe', 'egoi_mail_list_builder_shortcode' );
?>