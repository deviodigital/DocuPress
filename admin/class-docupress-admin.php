<?php
/**
 * The admin-specific functionality of the plugin.
 * 
 * @package    DocuPress
 * @subpackage DocuPress/admin
 * @author     Robert DeVore <contact@deviodigital.com>
 * @link       https://deviodigital.com
 * @since      1.0.0
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 * 
 * @package    DocuPress
 * @subpackage DocuPress/admin
 * @author     Robert DeVore <contact@deviodigital.com>
 * @link       https://deviodigital.com
 * @since      1.0.0
 */
class DocuPress_Admin {

    /**
     * The ID of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string    $_plugin_name    The ID of this plugin.
     */
    private $_plugin_name;

    /**
     * The version of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string    $_version    The current version of this plugin.
     */
    private $_version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $_plugin_name - The name of this plugin.
     * @param string $_version     - The version of this plugin.
     * 
     * @since 1.0.0
     */
    public function __construct( $_plugin_name, $_version ) {

        $this->plugin_name = $_plugin_name;
        $this->version     = $_version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since  1.0.0
     * @return void
     */
    public function enqueue_styles() {
        $screen = get_current_screen();
        
        // Enqueue styles only if on the block editor screen.
        if ( $screen && $screen->is_block_editor ) {
            wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/docupress-admin.css', [ 'wp-edit-blocks' ], $this->version, 'all' );
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since  1.0.0
     * @return void
     */
    public function enqueue_scripts() {
        $screen = get_current_screen();
        
        // Enqueue scripts only if on the block editor screen.
        if ( $screen && $screen->is_block_editor ) {
            wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/docupress-admin.js', [ 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor'], $this->version, false );
        }
    }

}
