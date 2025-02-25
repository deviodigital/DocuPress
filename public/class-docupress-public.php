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
     * @var    string  $plugin_name - The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string  $version - The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name - The name of the plugin.
     * @param string $version     - The version of this plugin.
     * 
     * @since 1.0.0
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version     = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since  1.0.0
     * @return void
     */
    public function enqueue_styles() {
        $post = get_post();
        if ( 
            is_singular( 'docupress' ) || 
            ( $post && has_shortcode( $post->post_content, 'docupress' ) ) || 
            ( $post && has_block( 'docupress/articles', $post ) ) 
        ) {
            wp_enqueue_style(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'css/docupress-public.min.css',
                [],
                $this->version,
                'all'
            );
        }
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since  1.0.0
     * @return void
     */
    public function enqueue_scripts() {
        // Check if we're on a singular DocuPress post or if the 'docupress' shortcode is used on the page.
        if ( is_singular( 'docupress' ) || has_shortcode( get_post()->post_content, 'docupress' ) ) {
            // General public JS.
            wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/docupress-public.js', [ 'jquery' ], $this->version, false );
            // Localize the general JS script so we can pass data to it with PHP.
            wp_localize_script( $this->plugin_name, 'DocuPressRatingAjax', [
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'docupress-article-rating-nonce' ) ]
            );
        }
    }

}
