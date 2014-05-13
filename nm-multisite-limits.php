<?php

/**
 * Plugin Name: NM Custom MultiSite
 * Description: Limits dashboard and toolbar options to necessary functions. Creates and registers as primary 'Student Menu' for student sites.
 * Version: 1.1
 * Author: Adam Gregory
 * Author URI: http://agregory.net
 * License: GNU
 */


class stnt_admin_lmt {

	// Initialize the plugin
	function __construct() {

		// Create a student menu and set it to the primary menu location;
		add_action( 'init', 'register_my_menu');

		function register_my_menu() {

			if ( (get_current_blog_id() != 1) && !wp_get_nav_menu_object('Student Menu') ) {
				$menu_id = wp_create_nav_menu('Student Menu');
				$locations = get_theme_mod('nav_menu_locations');
				$locations['primary'] = $menu_id;
				set_theme_mod('nav_menu_locations', $locations);
			}

			// Switch the parent site, retrive the url,
			// and switch back to student site.
			switch_to_blog(1);
			$home = home_url('/');
			restore_current_blog();

			// Create a menu item on the student menu
			// More menu items can be created with more function calls
			wp_update_nav_menu_item($menu_id, 0, array(
				'menu-item-title' => __("Nick's Site"),
				'menu-item-url' => $home,
				'menu-item-status' => 'publish'));
		}


		// Remove items from Dashboard menu at left
		add_action('admin_menu', 'custom_admin_menu');

		function custom_admin_menu() {
			if ( !is_super_admin() ) {
				remove_menu_page('tools.php');
				remove_menu_page('themes.php');
 				remove_menu_page('edit.php?post_type=page');
 				remove_menu_page('edit.php?post_type=icf_features');
 				remove_menu_page('edit.php?post_type=icf_partners');
 				remove_menu_page('edit.php?post_type=icf_testimonial');
			}
		}


		// Remove submenu items from Dashboard at left.
		add_action('admin_menu', 'custom_admin_submenus');

		function custom_admin_submenus() {

			if ( !is_super_admin() ) {
				remove_submenu_page('users.php', 'user-new.php');
				remove_submenu_page('users.php', 'users.php');
				remove_submenu_page('options-general.php', 'options-permalink.php');
				remove_submenu_page('users.php', 'user-new.php');
				remove_submenu_page('users.php', 'users.php');
				remove_submenu_page('options-general.php', 'options-permalink.php');
			}
		}

		// Remove "+ New" options from toolbar at top
		add_action( 'wp_before_admin_bar_render', 'custom_bar_menu');

		function custom_bar_menu() {
			if ( !is_super_admin() ) {

				global $wp_admin_bar;

				$wp_admin_bar->remove_node( 'new-page' );
				$wp_admin_bar->remove_node( 'new-icf_partners');
				$wp_admin_bar->remove_node( 'new-icf_testimonial');
				$wp_admin_bar->remove_node( 'new-icf_features');
				$wp_admin_bar->remove_node( 'new-user');
			}
		}

	} //end constructor
}



// instantiate plugin's class
$GLOBALS['student admin limits'] = new stnt_admin_lmt()

?>