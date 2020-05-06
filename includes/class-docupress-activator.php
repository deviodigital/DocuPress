<?php

/**
 * Fired during plugin activation
 *
 * @link       https://deviodigital.com
 * @since      1.0.0
 *
 * @package    DocuPress
 * @subpackage DocuPress/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    DocuPress
 * @subpackage DocuPress/includes
 * @author     Robert DeVore <contact@deviodigital.com>
 */
class DocuPress_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		/**
		 * Custom Post Type
		 */
		docupress_cpt();

		/**
		 * Custom Categories
		 */
		docupress_collections_taxonomy();

		/**
		 * Flush Rewrite Rules
		 */
		 global $wp_rewrite;
		 $wp_rewrite->init();
		 $wp_rewrite->flush_rules();
	}

}
