<?php

/**
 * Adds a configuration menu in the WordPress dashboard.
 *
 * Adds a menu in the WordPress dashboard that allows configuring
 * WP Buko options. The menu is called "WP Buko Settings" and is
 * located in the WordPress settings section.
 *
 * @since WP Buko 1.0.0
 */
function wp_buko_add_admin_menu()
{
    add_menu_page(
        'WP Buko Settings',
        'WP Buko',
        'manage_options',
        'wp-buko-settings',
        'wp_buko_settings_page',
        'dashicons-calendar-alt',
        30
    );
}
add_action('admin_menu', 'wp_buko_add_admin_menu');

/**
 * Registers the WP Buko settings with WordPress.
 *
 * This function registers the settings for WP Buko with WordPress.
 * It is called when the `admin_init` action hook is triggered.
 *
 * @since WP Buko 1.0.0
 */
function wp_buko_register_settings()
{
    register_setting('wp_buko_settings', 'wp_buko_default_slots');
}
add_action('admin_init', 'wp_buko_register_settings');


/**
 * Function to render the slots form (reusable)
 *
 * @param array|null $current_slots Current slots to display
 * @param bool $is_admin Whether for admin or public
 * @return string Form HTML
 */
function wp_buko_render_slots_form($current_slots = null, $is_admin = true) {
    if ($current_slots === null) {
        $current_slots = wp_buko_get_default_slots();
    }

    $form_class = $is_admin ? 'wp-buko-admin-form' : 'wp-buko-public-form';
    $button_class = $is_admin ? 'button button-primary' : 'wp-buko-submit-btn';
    $container_id = $is_admin ? 'admin-slots-container' : 'public-slots-container';
    $add_button_id = $is_admin ? 'admin-add-slot' : 'public-add-slot';

    ob_start();
    ?>
    <div class="wp-buko-slots-form <?php echo esc_attr($form_class); ?>">
        <div class="wp-buko-form-section">
            <h3 class="wp-buko-form-title">Configure Available Slots</h3>
            <div id="<?php echo esc_attr($container_id); ?>" class="wp-buko-slots-container">
                <?php foreach ($current_slots as $index => $slot): ?>
                    <div class="wp-buko-slot-input-group">
                        <input
                            type="text"
                            name="wp_buko_default_slots[]"
                            value="<?php echo esc_attr($slot); ?>"
                            placeholder="HH:MM AM/PM"
                            class="wp-buko-slot-input"
                            required
                        />
                        <button type="button" class="wp-buko-remove-slot" data-index="<?php echo $index; ?>">×</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" id="<?php echo esc_attr($add_button_id); ?>" class="wp-buko-add-slot-btn <?php echo $is_admin ? 'button' : 'wp-buko-add-btn'; ?>">
                Add Slot
            </button>
            <p class="wp-buko-form-description">Define available appointment times. Format: HH:MM AM/PM (e.g: 09:00 AM)</p>
        </div>

        <div class="wp-buko-form-actions">
            <button type="submit" name="wp_buko_save_slots" class="wp-buko-save-btn <?php echo esc_attr($button_class); ?>" disabled>
                Save Configuration
            </button>
        </div>
    </div>

    <script>
    (function() {
        const containerId = '<?php echo esc_attr($container_id); ?>';
        const addButtonId = '<?php echo esc_attr($add_button_id); ?>';
        const isAdmin = <?php echo $is_admin ? 'true' : 'false'; ?>;

        function validateSlots() {
            const inputs = document.querySelectorAll('#' + containerId + ' input[type="text"]');
            const saveBtn = document.querySelector('.wp-buko-save-btn');
            let allValid = true;

            inputs.forEach(input => {
                const value = input.value.trim();
                const timeRegex = /^(0?[1-9]|1[0-2]):[0-5][0-9] (AM|PM)$/i;

                if (!value) {
                    input.classList.add('wp-buko-error');
                    allValid = false;
                } else if (!timeRegex.test(value)) {
                    input.classList.add('wp-buko-error');
                    allValid = false;
                } else {
                    input.classList.remove('wp-buko-error');
                }
            });

            if (inputs.length === 0) {
                allValid = false;
            }

            saveBtn.disabled = !allValid;
            return allValid;
        }

        function addSlotInput() {
            const container = document.getElementById(containerId);
            const inputGroup = document.createElement('div');
            inputGroup.className = 'wp-buko-slot-input-group';

            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'wp_buko_default_slots[]';
            input.placeholder = 'HH:MM AM/PM';
            input.className = 'wp-buko-slot-input';
            input.required = true;

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'wp-buko-remove-slot';
            removeBtn.textContent = '×';

            inputGroup.appendChild(input);
            inputGroup.appendChild(removeBtn);
            container.appendChild(inputGroup);

            input.addEventListener('input', validateSlots);
            removeBtn.addEventListener('click', function() {
                inputGroup.remove();
                validateSlots();
            });

            validateSlots();
        }

        // Event listeners
        document.getElementById(addButtonId).addEventListener('click', addSlotInput);

        document.addEventListener('DOMContentLoaded', function() {
            // Add listeners to existing inputs
            document.querySelectorAll('#' + containerId + ' input[type="text"]').forEach(input => {
                input.addEventListener('input', validateSlots);
            });

            // Add listeners to existing remove buttons
            document.querySelectorAll('.wp-buko-remove-slot').forEach(btn => {
                btn.addEventListener('click', function() {
                    this.parentElement.remove();
                    validateSlots();
                });
            });

            validateSlots();
        });
    })();
    </script>
    <?php
    return ob_get_clean();
}

/**
 * Displays the WP Buko settings page.
 *
 * This function displays the settings page for WP Buko. It is called when
 * the `wp_buko_settings_page` action hook is triggered.
 *
 * The settings page allows the user to configure the available slots for
 * scheduling appointments. The slots are defined in the format
 * "HH:MM AM/PM" and are stored in the `wp_buko_default_slots`
 * option.
 *
 * @since WP Buko 1.0.0
 */
function wp_buko_settings_page()
{
    if (isset($_POST['wp_buko_save_slots']) && check_admin_referer('wp_buko_settings')) {
        $slots = isset($_POST['wp_buko_default_slots']) ? array_map('sanitize_text_field', $_POST['wp_buko_default_slots']) : [];

        // Validate slots
        $valid_slots = [];
        foreach ($slots as $slot) {
            $slot = trim($slot);
            if (preg_match('/^(0?[1-9]|1[0-2]):[0-5][0-9] (AM|PM)$/i', $slot)) {
                $valid_slots[] = $slot;
            }
        }

        if (!empty($valid_slots)) {
            update_option('wp_buko_default_slots', $valid_slots);
            echo '<div class="notice notice-success"><p>Configuration saved successfully.</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>Error: You must configure at least one valid slot.</p></div>';
        }
    }

    $current_slots = wp_buko_get_default_slots();
    ?>
    <div class="wrap">
        <h1>WP Buko Configuration</h1>
        <form method="post" action="">
            <?php wp_nonce_field('wp_buko_settings'); ?>
            <?php echo wp_buko_render_slots_form($current_slots, true); ?>
        </form>
    </div>
    <?php
}

/**
 * Shortcode to use the configuration form on public pages
 *
 * @return string Form HTML
 */
function wp_buko_slots_config_shortcode() {
    if (!current_user_can('manage_options')) {
        return '<p>You do not have permissions to access this configuration.</p>';
    }

    if (isset($_POST['wp_buko_save_slots'])) {
        $slots = isset($_POST['wp_buko_default_slots']) ? array_map('sanitize_text_field', $_POST['wp_buko_default_slots']) : [];

        // Validate slots
        $valid_slots = [];
        foreach ($slots as $slot) {
            $slot = trim($slot);
            if (preg_match('/^(0?[1-9]|1[0-2]):[0-5][0-9] (AM|PM)$/i', $slot)) {
                $valid_slots[] = $slot;
            }
        }

        if (!empty($valid_slots)) {
            update_option('wp_buko_default_slots', $valid_slots);
            echo '<div class="wp-buko-notice wp-buko-notice--success">Configuration saved successfully.</div>';
        } else {
            echo '<div class="wp-buko-notice wp-buko-notice--error">Error: You must configure at least one valid slot.</div>';
        }
    }

    $current_slots = wp_buko_get_default_slots();
    ob_start();
    ?>
    <form method="post" action="" class="wp-buko-public-config-form">
        <?php echo wp_buko_render_slots_form($current_slots, false); ?>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('wp_buko_slots_config', 'wp_buko_slots_config_shortcode');
