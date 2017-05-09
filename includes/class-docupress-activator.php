<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.robertdevore.com/
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
 * @author     Robert DeVore <deviodigital@gmail.com>
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
		DocuPress();

		/**
		 * Custom Categories
		 */
		DocuPress_Collections();

		/**
		 * Flush Rewrite Rules
		 */
		 global $wp_rewrite;
		 $wp_rewrite->init();
		 $wp_rewrite->flush_rules();
	}

}
