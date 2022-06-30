<?php

/**
 * Fired during plugin activation
 *
 * @package    DocuPress
 * @subpackage DocuPress/includes
 * @author     Robert DeVore <contact@deviodigital.com>
 * @link       https://deviodigital.com
 * @since      1.0.0

 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @package    DocuPress
 * @subpackage DocuPress/includes
 * @author     Robert DeVore <contact@deviodigital.com>
 * @link       https://deviodigital.com
 * @since      1.0.0
 */
class DocuPress_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since  1.0.0
     * @return void
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
