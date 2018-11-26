<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://github.com/vorapoap
 * @since      1.0.0
 *
 * @package    Vcoinmarketcap
 * @subpackage Vcoinmarketcap/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Vcoinmarketcap
 * @subpackage Vcoinmarketcap/includes
 * @author     Vorapoap Lohwongwatana <vorapoap@hotmail.com>
 */
class Vcoinmarketcap_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'vcoinmarketcap',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
