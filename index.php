<?php

/**
 * Plugin Name: Adjacent Posts
 * Plugin URI: https://github.com/PiotrPress/wordpress-adjacent-posts
 * Description: This WordPress plugin adds Previous/Next posts Query Loop block Variations.
 * Version: 0.2.1
 * Requires at least: 6.4
 * Requires PHP: 7.4
 * Author: Piotr Niewiadomski
 * Author URI: https://piotr.press
 * License: GPL v3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: piotrpress-adjacent-posts
 * Domain Path: /languages
 * Update URI: false
 */

defined( 'ABSPATH' ) or exit;

add_action( 'init', function() {
    $asset = include plugin_dir_path( __FILE__ ) . 'build/index.asset.php';
    wp_register_script('piotrpress-adjacent-posts',
        plugins_url( 'build/index.js', __FILE__ ),
        $asset[ 'dependencies' ],
        $asset[ 'version' ],
        true
    );
} );

add_action( 'enqueue_block_editor_assets', function() {
    wp_enqueue_script( 'piotrpress-adjacent-posts' );
    wp_add_inline_script( 'piotrpress-adjacent-posts',
        'const prevPostID=' . ( ( $post = get_previous_post() ) ? $post->ID : 0 ) . ';' .
        'const nextPostID=' . ( ( $post = get_next_post() ) ? $post->ID : 0 ) . ';',
        'before'
    );
} );

add_filter( 'pre_render_block', function( ?string $render, array $block ) {
    switch( $block[ 'attrs' ][ 'namespace' ] ?? '' ) {
        case 'piotrpress/prev-post-query' :
            add_filter( 'query_loop_block_query_vars', fn() => ( ( $post = get_previous_post() ) ? [ 'post__in' => [ $post->ID ] ] : [ 'post__in' => [0] ] ) );
            break;
        case 'piotrpress/next-post-query' :
            add_filter( 'query_loop_block_query_vars', fn() => ( ( $post = get_next_post() ) ? [ 'post__in' => [ $post->ID ] ] : [ 'post__in' => [0] ] ) );
            break;
    }
    return $render;
}, 10, 2 );