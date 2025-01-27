<?php
/**
 * Plugin Name: Cool Kids Network
 * Plugin URI:  
 * Description: A user management system for the Cool Kids Network.
 * Version:     1.0
 * Author:      Mohammed Imtiyaz
 * Author URI:  
 * Text Domain: cool-kids-network
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Autoload necessary files.
require_once plugin_dir_path( __FILE__ ) . 'includes/class-cool-kids-init.php';

// Initialize the plugin.
Cool_Kids_Init::run();
