<?php

if ( !is_admin() || ( defined( 'DOING_AJAX') && DOING_AJAX ) )
	require_once( __DIR__ . '/ajax.php' );

function ub_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'bootstrap', 'http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/js/bootstrap.min.js', array( 'jquery' ), '2.2.1', true );
	wp_enqueue_script( 'application', get_template_directory_uri() . '/application.js', array( 'jquery', 'bootstrap' ), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'ub_scripts' );

add_action( 'wp_title', function( $title, $sep, $seplocation ) {
	if ( !$title )
		$title = 'WordPress Utility Belt';
	return $title;
}, 99, 3 );


if ( isset( $_POST['full'] ) ) {
	$_POST = stripslashes_deep( $_POST );

	function ub_force_blank_template( &$wp_query ) {
		$wp_query->is_home = false;
		$wp_query->is_search = true;
	}
	add_action( 'parse_query', 'ub_force_blank_template' );

	function ub_load_blank_template() {
		return get_template_directory() . '/full.php';
	}
	add_filter( 'template_include', 'ub_load_blank_template' );

	if ( isset( $_POST['code'] ) ) {

		if ( isset( $_POST['action'] ) ) {
			add_action( $_POST['action'], function() {
				if ( false === eval( $_POST['code'] ) )
					echo 'PHP Error encountered, execution halted';
				exit;
			} );
		} else {
			if ( false === eval( $_POST['code'] ) )
				echo 'PHP Error encountered, execution halted';
			exit;
		}

	}
}