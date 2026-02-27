<?php
require_once('/var/www/html/wp-load.php');

$user = get_user_by('login', 'stilco');
if (!$user) {
    $users = get_users();
    if (!empty($users)) {
        $user = $users[0];
    } else {
        die("No users found.\n");
    }
}

global $wpdb;
$consumer_key    = 'ck_' . wc_rand_hash();
$consumer_secret = 'cs_' . wc_rand_hash();

$wpdb->insert(
    $wpdb->prefix . 'woocommerce_api_keys',
    array(
        'user_id'         => $user->ID,
        'description'     => 'Local AI Script',
        'permissions'     => 'read_write',
        'consumer_key'    => wc_api_hash( $consumer_key ),
        'consumer_secret' => $consumer_secret,
        'truncated_key'   => substr( $consumer_key, -7 )
    )
);

file_put_contents('/var/www/html/wp-content/keys.txt', "$consumer_key\n$consumer_secret\n");
echo "Keys generated successfully.\n";
