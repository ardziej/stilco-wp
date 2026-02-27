<?php
require_once dirname(dirname(dirname(__DIR__))) . '/wp-load.php';

$pages = get_posts([
    'post_type' => 'page',
    'post_status' => 'publish',
    'numberposts' => -1
]);

foreach ($pages as $p) {
    $template = get_post_meta($p->ID, '_wp_page_template', true);
    echo "Slug: " . $p->post_name . " | Title: " . $p->post_title . " | Template: " . $template . "\n";
}
