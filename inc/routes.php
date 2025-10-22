<?php

/**
 * Registers the routes for the WP Buko API.
 *
 * Registers the following routes:
 * - `/available`: A GET route that returns the available slots for a given date.
 * - `/book`: A POST route that creates a new booking.
 * - `/bookings`: A GET route that returns a list of all bookings.
 *
 * The `/bookings` route requires the `edit_posts` capability.
 */
function wp_buko_register_api_routes()
{
    $endpoint = 'wp_buko/v1';
    register_rest_route($endpoint, '/available', [
        'methods'  => 'GET',
        'callback' => 'wp_buko_get_available',
        'args' => [
            'date' => ['required' => true],
        ]
    ]);

    register_rest_route($endpoint, '/book', [
        'methods' => 'POST',
        'callback' => 'wp_buko_create_booking',
    ]);

    register_rest_route($endpoint, '/bookings', [
        'methods'  => 'GET',
        'callback' => 'wp_buko_list_bookings'
    ]);

    register_rest_route($endpoint, '/slots', [
        'methods' => 'GET',
        'callback' => 'wp_buko_get_default_slots'
    ]);
}
add_action('rest_api_init', 'wp_buko_register_api_routes');

/**
 * Returns the available slots for a given date.
 *
 * @param WP_REST_Request $request The current request object.
 *
 * @return WP_REST_Response The response object.
 */
function wp_buko_get_available($request)
{
    global $wpdb;
    $params = $request->get_query_params();
    $date = sanitize_text_field($params['date']);

    $default_slots = wp_buko_get_default_slots();

    $table = $wpdb->prefix . 'buko_appointments';
    $reserved = $wpdb->get_col($wpdb->prepare(
        "SELECT `time` FROM $table WHERE `date` = %s",
        $date
    ));

    $available = [];
    foreach ($default_slots as $s) {
        $available[] = [
            'time' => $s,
            'available' => ! in_array($s, $reserved),
        ];
    }

    return rest_ensure_response([
        'date' => $date,
        'slots' => $available,
    ]);
}

/**
 * Creates a booking for a given date and time.
 *
 * @param WP_REST_Request $request The current request object.
 *
 * @return WP_REST_Response The response object.
 *
 * @throws WP_Error If the booking data is invalid or if the slot is already taken.
 */

function wp_buko_create_booking($request)
{
    global $wpdb;
    $data = $request->get_json_params();

    $name  = sanitize_text_field($data['name'] ?? '');
    $email = sanitize_email($data['email'] ?? '');
    $date  = sanitize_text_field($data['date'] ?? ''); // YYYY-MM-DD
    $time  = sanitize_text_field($data['time'] ?? '');

    if (empty($name)) {
        return new WP_Error('invalid_data', 'El nombre es requerido.', ['status' => 400]);
    }

    if (!is_email($email)) {
        return new WP_Error('invalid_data', 'El correo electrónico no es válido', ['status' => 400]);
    }

    if (empty($date)) {
        return new WP_Error('invalid_data', 'La fecha es requerida.', ['status' => 400]);
    }

    if (empty($time)) {
        return new WP_Error('invalid_data', 'El horario es requerido.', ['status' => 400]);
    }

    $table = $wpdb->prefix . 'buko_appointments';

    $exists = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table WHERE `date` = %s AND `time` = %s",
        $date,
        $time
    ));

    if (intval($exists) > 0) {
        return new WP_Error('slot_taken', 'The slot is already booked', ['status' => 409]);
    }

    $inserted = $wpdb->insert($table, [
        'name' => $name,
        'email' => $email,
        'date' => $date,
        'time' => $time,
        'status' => 'pending',
    ]);

    if ($inserted) {
        $id = $wpdb->insert_id;
        return rest_ensure_response(['success' => true, 'id' => $id]);
    }

    return new WP_Error('db_error', 'Could not create the booking', ['status' => 500]);
}

/**
 * Returns a list of all bookings.
 *
 * @param WP_REST_Request $request The current request object.
 *
 * @return WP_REST_Response The response object.
 *
 * @since WP Buko 1.0.0
 */
function wp_buko_list_bookings($request)
{
    global $wpdb;
    $table = $wpdb->prefix . 'buko_appointments';
    $rows = $wpdb->get_results("SELECT * FROM $table ORDER BY created_at DESC", ARRAY_A);
    return rest_ensure_response($rows);
}
