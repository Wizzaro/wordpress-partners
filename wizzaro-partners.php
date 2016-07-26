<?php
   /*
   Plugin Name: Wizzaro Partners
   Description: This is plugin for add partners, clients or sponsors information on website
   Version: 1.0.0
   Plugin URI: https://github.com/Wizzaro/wordpress-partners
   Author: Wizzaro
   Author URI: http://www.wizzaro.com
   License: GPL-2.0+
   */

if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

load_plugin_textdomain( 'wizzaro-partners', false, plugin_basename( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'languages' ); 
require_once 'vendor/autoload.php';
Wizzaro\Partners\Plugin::create();