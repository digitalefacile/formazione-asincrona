<?php
require_once(__DIR__ . '/config.php');
global $DB, $USER, $CFG;
$protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
$domain = $_SERVER['HTTP_HOST'];
$current_url = $protocol . $domain;
$sesskey = sesskey();

// var_dump($sesskey);

$final_url = $current_url . '/auth/oauth2/login.php?id=1&wantsurl='.urlencode($current_url).'%2F&sesskey=' . $sesskey ;
redirect($final_url, null, 0);

// var_dump($final_url);