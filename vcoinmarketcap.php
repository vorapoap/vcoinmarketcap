<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://github.com/vorapoap
 * @since             1.0.0
 * @package           Vcoinmarketcap
 *
 * @wordpress-plugin
 * Plugin Name:       VCoinmarketCap
 * Plugin URI:        http://github.com/vorapoap
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Vorapoap Lohwongwatana
 * Author URI:        http://github.com/vorapoap
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       vcoinmarketcap
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-vcoinmarketcap-activator.php
 */
function activate_vcoinmarketcap() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-vcoinmarketcap-activator.php';
	Vcoinmarketcap_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-vcoinmarketcap-deactivator.php
 */
function deactivate_vcoinmarketcap() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-vcoinmarketcap-deactivator.php';
	Vcoinmarketcap_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_vcoinmarketcap' );
register_deactivation_hook( __FILE__, 'deactivate_vcoinmarketcap' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-vcoinmarketcap.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_vcoinmarketcap() {

	$plugin = new Vcoinmarketcap();
	$plugin->run();

}
run_vcoinmarketcap();
