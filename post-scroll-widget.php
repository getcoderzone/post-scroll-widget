<?php
/**
 * Plugin Name: Post Scroll Widget
 * Plugin URI: http://shafiqul.info
 * Description: This is a Simple Post Scroll Widget. Easily Manage This widget. Latest Post and Category post you can show scroll item.
 * Author: Shafiqul Islam
 * Author URI: http://shafiqul.info
 * Version: 1.0
 */
 
function gcz_post_scroll_widget_scripts(){
	// Load the Scroll CSS
	wp_enqueue_style( 'post-scroll-style', plugin_dir_url(__FILE__) . 'assets/css/post-scroll-style.css');
	
	//Load our custom Javascript file
	wp_enqueue_script( 'jquery.marquee.min.js', plugin_dir_url(__FILE__) . 'assets/js/jquery.marquee.min.js' );

}
add_action( 'wp_footer', 'gcz_post_scroll_widget_scripts' );

function gcz_add_custom_script(){
	?>
		<script type="text/javascript"> 
			jQuery(document).ready(function($) {
				$('#horizontal-scroll').marquee({
					duration: 13000,
					pauseOnHover: true
				});

				$('#vertical-scroll').marquee({
					duration: 12000,
					direction: 'up',
					duplicated: false,
					pauseOnHover: true
				});
			});
		</script>
	<?php
}
add_action('wp_footer','gcz_add_custom_script');



if(file_exists(dirname( __FILE__ ) . '/inc/Class-post-scroll-widget.php')){
	require_once dirname( __FILE__ ) . '/inc/Class-post-scroll-widget.php';
};

function gcz_post_scroll_widget_register() {
    register_widget( 'gcz_post_scroll_widget' );
}
add_action( 'widgets_init', 'gcz_post_scroll_widget_register' );