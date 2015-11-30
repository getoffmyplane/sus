<?php
/*
Plugin Name: Fail2Ban filter
Version: 1.0
Description: Sets a 401 Status Code which shows in access logs for use with fail2ban
Author: Tim Nash
Author URI: https://timnash.co.uk
Code Modified from  Konstantin Kovshenin original - http://kovshenin.com/2014/fail2ban-wordpress-nginx/
*/
function fail2ban_login_failed_401() {
    status_header( 401 );
}
add_action( 'wp_login_failed', 'fail2ban_login_failed_401' );