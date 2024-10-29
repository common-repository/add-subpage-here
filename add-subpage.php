<?php
/*
Plugin Name: Add Subpage Here
Description: Adds simple widget and admin menu item for adding a subpage to the current page.
Author: mitcho (Michael Yoshitaka Erlewine)
Version: 0.4
Author URI: http://mitcho.com
Plugin URI: http://ecs.mit.edu
*/

add_filter( 'wp_insert_post_data', 'add_subpage_set_page_parent', 10, 2 );
function add_subpage_set_page_parent( $data, $postarr ) {
	if ( $data['post_status'] == 'auto-draft' && isset( $_GET['post_parent'] ) && !$data['post_parent'] )
		$data['post_parent'] = (int) $_GET['post_parent'];
	return $data;
}

// @since 0.2
add_action( 'wp_before_admin_bar_render', 'add_subpage_menu_item' );
function add_subpage_menu_item() {
	global $wp_admin_bar, $post, $wp_version;
	
	if (!is_singular() || !is_page() || is_home())
		return;
	
	$args = array(
		'parent' => 'new-content',
		'id' => 'new-subpage',
		'title' => __('Subpage'),
		'href' => admin_url( "post-new.php?post_type=page&post_parent={$post->ID}")
	);
	
	$wp_admin_bar->add_node($args);
}

class Add_Subpage_Widget extends WP_Widget {
	function Add_Subpage_Widget() {
		$this->WP_Widget('add_subpage_widget', 'Add Subpage');
	}

	function form($instance) {
		// outputs the options form on admin
	}

	function update($new_instance, $old_instance) {
		// processes widget options to be saved
	}

	function widget($args, $instance) {
		wp_reset_query();
		global $post;
		
		if (!is_singular() || !is_page() || is_home())
			return;

		$url = admin_url( "post-new.php?post_type=page&post_parent={$post->ID}");
		echo $before_widget;
		echo '<p><a class="button" href="' . $url . '">Add Subpage</a></p>';
		echo $after_widget;
	}
}

add_action('widgets_init','register_add_subpage_widget');
function register_add_subpage_widget() {
	register_widget('Add_Subpage_Widget');
}
