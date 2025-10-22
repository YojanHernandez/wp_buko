<?php

/**
 * Schedule Appointment
 * 
 * @package WP Buko
 */

$title = $attributes['title'] ?? '';
$attributes_json = wp_json_encode($attributes);
?>

<div <?= get_block_wrapper_attributes(['class' => 'wp-buko-schedule',]); ?> data-schedule='<?= esc_attr($attributes_json); ?>'>
	<div class="wp-buko-schedule__card">
		<?php if ($title) : ?>
			<h2 class="wp-buko-schedule__title">
				<?= esc_html($title); ?>
			</h2>
		<?php endif; ?>
		<div class="wp-buko-schedule__wrapper" data-schedule="<?= esc_attr($attributes_json); ?>"></div>
	</div>
</div>