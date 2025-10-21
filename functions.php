<?php

/**
 * WP Buko theme functions
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Variables.
define('WP_BUKO_DIR', get_template_directory());
define('WP_BUKO_URL', get_template_directory_uri());

// Enqueue styles and scripts.
require_once WP_BUKO_DIR . '/inc/enqueue.php';

// Setup.
require_once WP_BUKO_DIR . '/inc/setup.php';

// Custom blocks.
require_once WP_BUKO_DIR . '/inc/register-blocks.php';

// Options page.
require_once WP_BUKO_DIR . '/inc/options.php';

// Custom Tables.
require_once WP_BUKO_DIR . '/database/schedule-appointment.php';

// Custom rest API.
require_once WP_BUKO_DIR . '/api/routes.php';

// Utils.
require_once WP_BUKO_DIR . '/inc/utils.php';
