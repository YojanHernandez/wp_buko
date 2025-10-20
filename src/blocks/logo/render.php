<?php

/**
 * Custom Logo
 */

$siteTitle = $attributes['title'] ?? '';
?>
<div <?= get_block_wrapper_attributes(array('class' => 'wp-buko-logo')); ?>
    data-site-title="<?= $siteTitle; ?>">
</div>