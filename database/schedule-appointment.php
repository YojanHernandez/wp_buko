<?php
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Creates the wp_buko_appointments table in the database.
 *
 * This function creates a table in the WordPress database to store
 * appointments. It is called when the plugin is activated.
 *
 * @global wpdb $wpdb The WordPress database object.
 *
 * @return void
 */
function wp_buko_create_schedule_table()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'buko_appointments';

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(100) NOT NULL,
        email varchar(150) NOT NULL,
        `date` date NOT NULL,
        `time` varchar(20) NOT NULL,
        status varchar(20) NOT NULL DEFAULT 'pending',
        created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    add_action('after_switch_theme', 'wp_buko_create_schedule_table');


    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

add_action('after_switch_theme', 'wp_buko_create_schedule_table');
