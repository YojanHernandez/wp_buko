<?php

/**
 * Returns the default available slots for a given date.
 *
 * @return array The default available slots.
 */
function wp_buko_get_default_slots()
{
    return get_option('wp_buko_default_slots', [
        '09:00 AM',
        '09:30 AM',
        '10:00 AM',
        '10:30 AM',
        '11:00 AM',
        '11:30 AM',
        
    ]);
}


/**
 * Updates the default available slots for a given date.
 *
 * @param array $slots The default available slots.
 *
 * @return bool True if the option was updated successfully, false otherwise.
 */
function wp_buko_update_default_slots($slots)
{
    if (!is_array($slots)) {
        return false;
    }
    return update_option('wp_buko_default_slots', $slots);
}
