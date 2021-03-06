<?php  # -*- coding: utf-8 -*-
/**
 * Plugin Name: Custom Plugin With Top Level Menu
 * Description: Load scripts and styles on specific admin menu pages
 * Plugin URI:  https://github.com/tysonchamp/
 * Version:     1.0
 * Author:      Tyson
 * Author URI:  http://fb.com/tysonchampno1
 * Licence:     Open Source
 */
 
 
/* call our code on admin pages only, not on front end requests or during
 * AJAX calls.
 * Always wait for the last possible hook to start your code.
 */
add_action( 'admin_menu', array ( 'custom_plugin', 'admin_menu' ) );

/**
 * Register three admin pages and add a stylesheet and a javascript to two of
 * them only.
 *
 * @author toscho
 *
 */
class custom_plugin
{
	/**
	 * Register the pages and the style and script loader callbacks.
	 *
	 * @wp-hook admin_menu
	 * @return  void
	 */
	public static function admin_menu()
	{
		// $main is now a slug named "toplevel_page_custom-plugin"
		// built with get_plugin_page_hookname( $menu_slug, '' )
		$main = add_menu_page(
			'Add Custom Post',                         // page title
			'Add Custom Post',                         // menu title
			// Change the capability to make the pages visible for other users.
			// See http://codex.wordpress.org/Roles_and_Capabilities
			'manage_options',                  // capability
			'custom-plugin',                         // menu slug
			array ( __CLASS__, 'add_post' ) // callback function
		);

		// $sub is now a slug named "custom-plugin_page_custom-plugin-sub"
		// built with get_plugin_page_hookname( $menu_slug, $parent_slug)
		$sub = add_submenu_page(
			'custom-plugin',                         // parent slug
			'Edit Custom Posts',                     // page title
			'Edit Posts',                     // menu title
			'manage_options',                  // capability
			'edit-post',                     // menu slug
			array ( __CLASS__, 'edit_posts' ) // callback function, same as above
		);

		/* See http://wordpress.stackexchange.com/a/49994/73 for the difference
		 * to "'admin_enqueue_scripts', $hook_suffix"
		 */
		foreach ( array ( $main, $sub ) as $slug )
		{
			// make sure the style callback is used on our page only
			add_action(
				"admin_print_styles-$slug",
				array ( __CLASS__, 'enqueue_style' )
			);
			// make sure the script callback is used on our page only
			add_action(
				"admin_print_scripts-$slug",
				array ( __CLASS__, 'enqueue_script' )
			);
		}

		// $text is now a slug named "custom-plugin_page_t5-text-included"
		// built with get_plugin_page_hookname( $menu_slug, $parent_slug)
		$text = add_submenu_page(
			'custom-plugin',                         // parent slug
			'Help',                     // page title
			'Help',                     // menu title
			'manage_options',                  // capability
			'custom-plugin-help',                     // menu slug
			array ( __CLASS__, 'render_text_included' ) // callback function, same as above
		);
	}

	/**
	 * Print page output.
	 *
	 * @wp-hook toplevel_page_custom-plugin In wp-admin/admin.php do_action($page_hook).
	 * @wp-hook custom-plugin_page_custom-plugin-sub
	 * @return  void
	 */
	public static function add_post()
	{
		global $title;

		print '<div class="wrap">';
		print "<h1>$title</h1>";
		// Creat a page name add-post.php
		include('add-post.php');
		print '</div>';
	}

	public static function edit_posts()
	{
		global $title;

		print '<div class="wrap">';
		print "<h1>$title</h1>";
		// create a page name edit-post.php
		include('edit-posts.php');
		print '</div>';
	}

	/**
	 * Print included HTML file.
	 *
	 * @wp-hook custom-plugin_page_t5-text-included
	 * @return  void
	 */
	public static function render_text_included()
	{
		global $title;

		print '<div class="wrap">';
		print "<h1>$title</h1>";
		// create a page name readme.php
		include('readme.php');
		
		print '</div>';
	}

	/**
	 * Load stylesheet on our admin page only.
	 *
	 * @return void
	 */
	public static function enqueue_style()
	{
		wp_register_style(
			't5_demo_css',
			plugins_url( 'custom-plugin.css', __FILE__ )
		);
		wp_enqueue_style( 't5_demo_css' );
	}
	

	/**
	 * Load JavaScript on our admin page only.
	 *
	 * @return void
	 */
	public static function enqueue_script()
	{
		wp_register_script(
			't5_demo_js',
			plugins_url( 'custom-plugin.js', __FILE__ ),
			array(),
			FALSE,
			TRUE
		);
		wp_enqueue_script( 't5_demo_js' );
	}
	
}
