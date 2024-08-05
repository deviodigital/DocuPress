<?php

/**
 * The Class responsible for defining the custom permalink settings.
 *
 * @link       https://deviodigital.com
 * @since      1.2.0
 *
 * @package    DocuPress
 * @subpackage DocuPress/admin
 */
class DocuPress_Permalink_Settings {
    /**
     * Initialize class.
     */
    public function __construct() {
        $this->init();
        $this->settings_save();
    }

    /**
     * Call register fields.
     */
    public function init() {
        add_filter( 'admin_init', [ &$this, 'register_fields' ] );
    }

    /**
     * Add setting to permalinks page.
     */
    public function register_fields() {
        register_setting( 'permalink', 'docupress_article_slug', 'esc_attr' );
        add_settings_field( 'docupress_article_slug_setting', '<label for="docupress_article_slug">' . esc_attr__( 'DocuPress Base', 'docupress' ) . '</label>', [ &$this, 'fields_html' ], 'permalink', 'optional' );
    }

    /**
     * HTML for permalink setting.
     */
    public function fields_html() {
        $value = get_option( 'docupress_article_slug' );
        wp_nonce_field( 'docupress-article', 'docupress_article_slug_nonce' );
        echo '<input type="text" class="regular-text code" id="docupress_article_slug" name="docupress_article_slug" placeholder="article" value="' . esc_attr( $value ) . '" />';
    }

    /**
     * Save permalink settings.
     */
    public function settings_save() {
        if ( ! is_admin() ) {
            return;
        }

        // We need to save the options ourselves; settings api does not trigger save for the permalinks page.
        if ( isset( $_POST ) && null !== filter_input( INPUT_POST, 'permalink_structure' ) ||
            filter_input( INPUT_POST, 'category_base' ) &&
            filter_input( INPUT_POST, 'docupress_article_slug' ) &&
            wp_verify_nonce( wp_unslash( filter_input( INPUT_POST, 'docupress_article_slug_nonce' ) ), 'docupress' ) ) {
                $article_slug = sanitize_title( wp_unslash( filter_input( INPUT_POST, 'docupress_article_slug' ) ) );
                update_option( 'docupress_article_slug', $article_slug );
        }
    }
}

$docupress_permalink_settings = new DocuPress_Permalink_Settings();
