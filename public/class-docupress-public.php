<?php

/**
 * The public-facing functionality of the plugin.
 * 
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    DocuPress
 * @subpackage DocuPress/public
 * @author     Robert DeVore <contact@deviodigital.com>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       https://deviodigital.com
 * @since      1.0.0
 */
class DocuPress_Public {

    /**
     * The ID of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string  $_plugin_name - The ID of this plugin.
     */
    private $_plugin_name;

    /**
     * The version of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string  $_version - The current version of this plugin.
     */
    private $_version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $_plugin_name - The name of the plugin.
     * @param string $_version     - The version of this plugin.
     * 
     * @since 1.0.0
     */
    public function __construct( $_plugin_name, $_version ) {

        $this->plugin_name = $_plugin_name;
        $this->version     = $_version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since  1.0.0
     * @return void
     */
    public function enqueue_styles() {
        // General public CSS.
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/docupress-public.min.css', [], $this->version, 'all' );
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since  1.0.0
     * @return void
     */
    public function enqueue_scripts() {
        // General public JS.
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/docupress-public.js', [ 'jquery' ], $this->version, false );
        // Localize the general JS script so we can pass data to it with PHP.
        wp_localize_script( $this->plugin_name, 'DocuPressRatingAjax', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'docupress-article-rating-nonce' ) ]
        );
    }

}
