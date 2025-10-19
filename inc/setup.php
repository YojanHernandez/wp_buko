
<?php

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @since WP Buko 1.0.0
 */
function wp_buko_setup()
{
    // Support for automatic title
    add_theme_support('title-tag');

    // Support for featured image
    add_theme_support('post-thumbnails');

    // Support for Gutenberg blocks
    add_theme_support('wp-block-styles');
    add_theme_support('responsive-embeds');
    add_theme_support('editor-styles');
    add_theme_support('align-wide');
}
add_action('after_setup_theme', 'wp_buko_setup');
