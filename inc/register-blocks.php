<?php

/**
 * Registers all Gutenberg blocks located in the build/blocks directory.
 *
 * @since WP Buko 1.0.0
 */
function wp_buko_register_blocks()
{

    $build_dir = WP_BUKO_DIR . '/build/blocks/';
    if (file_exists($build_dir)) {
        $blocks = scandir($build_dir);
        foreach ($blocks as $block) {
            if ($block !== '.' && $block !== '..') {
                register_block_type($build_dir . $block);
            }
        }
    }
}
add_action('init', 'wp_buko_register_blocks');
