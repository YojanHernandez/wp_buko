<?php

/**
 * Enqueue theme styles and scripts.
 *
 * Enqueues the theme's main CSS file and JavaScript file.
 *
 * This function is hooked into the `wp_enqueue_scripts` action hook.
 *
 * @since WP Buko 1.0.0
 */

function wp_buko_enqueue_scripts()
{
    // Enqueue theme styles
    wp_enqueue_style(
        'wp-buko-main',
        WP_BUKO_URL . '/assets/css/main.css',
        array(),
        filemtime(WP_BUKO_DIR . '/assets/css/main.css')
    );

    // Enqueue theme scripts
    wp_enqueue_script(
        'wp-buko-script',
        WP_BUKO_URL . '/assets/js/main.js',
        array('jquery'),
        filemtime(WP_BUKO_DIR . '/assets/js/main.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'wp_buko_enqueue_scripts');

/**
 * Adds the theme's main CSS file to the editor.
 *
 * This function is hooked into the `after_setup_theme` action hook.
 *
 * @since WP Buko 1.0.0
 */
function wp_buko_editor_style()
{
    add_editor_style(WP_BUKO_URL . '/assets/css/main.css');
}
add_action('after_setup_theme', 'wp_buko_editor_style');


/**
 * Enqueues the theme's editor CSS file.
 *
 * This function is hooked into the `enqueue_block_editor_assets` action hook.
 *
 * @since WP Buko 1.0.0
 */
function wp_buko_editor_assets()
{
    wp_enqueue_style(
        'wp-buko-editor-style',
        WP_BUKO_URL . '/assets/css/editor.css',
        array(),
        filemtime(WP_BUKO_DIR . '/assets/css/editor.css')
    );
}

add_action('enqueue_block_editor_assets', 'wp_buko_editor_assets');
