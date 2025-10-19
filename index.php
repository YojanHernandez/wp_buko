<?php
/**
 * Fallback file for the wp_buko theme
 * This file is used when no block templates are available
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header();

if (have_posts()) :
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
else :
    echo '<p>No content available.</p>';
endif;

get_footer();