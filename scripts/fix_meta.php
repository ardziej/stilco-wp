<?php
require_once('/var/www/html/wp-load.php');

$comments = get_comments(array(
    'type' => 'review',
    'number' => 5,
    'order' => 'DESC',
    'status' => 'approve'
));

$attachments = get_posts(array(
    'post_type' => 'attachment',
    'numberposts' => 5,
    'post_mime_type' => 'image',
    'order' => 'DESC'
));

if(count($comments) >= 5 && count($attachments) >= 5) {
    for($i = 0; $i < 5; $i++) {
        $comment_id = $comments[$i]->comment_ID;
        $attachment_id = $attachments[$i]->ID;
        update_comment_meta($comment_id, '_review_image_id', $attachment_id);
        echo "Attached image $attachment_id to comment $comment_id\n";
    }
} else {
    echo "Not enough comments or attachments\n";
}
