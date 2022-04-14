<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://deviodigital.com
 * @since      1.2
 *
 * @package    DocuPress
 * @subpackage DocuPress/admin
 * @author     Robert DeVore <contact@deviodigital.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	wp_die();
}

/**
 * DocuPress Article Details metabox
 * 
 * @return void
 */
function docupress_add_article_details_metaboxes() {
	// Create array of CPT's to display the metabox on.
	$screens = apply_filters( 'docupress_article_details_metabox_screens', array( 'docupress' ) );
	// Loop through screens.
	foreach ( $screens as $screen ) {
		add_meta_box(
			'docupress_articles',
			__( 'DocuPress Details', 'docupress' ),
			'docupress_article_details_metabox',
			$screen,
			'side',
			'default'
		);
	}
}
add_action( 'add_meta_boxes', 'docupress_add_article_details_metaboxes' );

/**
 * Check the metadata
 *
 * @return boolean false/true
 */
function docupress_article_details_meta( $value ) {
	global $post;

	$field = get_post_meta( $post->ID, $value, true );

	if ( ! empty( $field ) ) {
		return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
	} else {
		return false;
	}
}

/**
 * Building the metabox
 * 
 * @return string $details 
 */
function docupress_article_details_metabox() {
	global $post;

	// Noncename needed to verify where the data originated.
	echo '<input type="hidden" name="docupress_article_details_meta_noncename" id="docupress_article_details_meta_noncename" value="' .
	wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

	// Get the metabox data if it has already been entered.
	$docupress_path = get_post_meta( $post->ID, 'docupress_path', true );
	$docupress_url  = get_post_meta( $post->ID, 'docupress_url', true );
	$checkbox       = ( docupress_article_details_meta( 'docupress_article_estimated_reading_display' ) === 'hide_estimated_reading' ) ? ' checked' : '';

	// Build the HTML output.
	$details  = '<div class="docupress details">';
	$details .= '<p>' . esc_attr__( 'Path', 'docupress' ) . '<span>' . esc_attr__( '(ex: path/to/file.php)', 'docupress' ) . '</span>:</p>';
	$details .= '<input type="text" name="docupress_path" value="' . esc_html( $docupress_path )  . '" class="widefat" />';
	$details .= '</div>';
	$details .= '<div class="docupress details">';
	$details .= '<p>' . esc_attr__( 'Link', 'docupress' ) . '<span>(ex: https://github.com/...)</span>:</p>';
	$details .= '<input type="url" name="docupress_url" value="' . esc_url( $docupress_url ) . '" class="widefat" />';
	$details .= '</div>';
	$details .= '<div class="docupress details">';
	$details .= '<p><input type="checkbox" name="docupress_article_estimated_reading_display" id="docupress_article_estimated_reading_display" value="hide_estimated_reading"' . $checkbox . '>
							<label for="docupress_article_estimated_reading_display">' . esc_attr__( 'Remove estimated reading time?', 'docupress' ) . '</label>
							</p>';
	$details .= '</div>';

	// Display details.
	echo $details;
}

/**
 * Save the Metabox Data
 * 
 * @return void
 */
function docupress_save_article_details_meta( $post ) {
	/**
	 * Verify this came from the our screen and with proper authorization,
	 * because save_post can be triggered at other times
	 */
	if (
		! isset( $_POST['docupress_article_details_meta_noncename' ] ) ||
		! wp_verify_nonce( filter_input( INPUT_POST, 'docupress_article_details_meta_noncename' ), plugin_basename( __FILE__ ) )
	) {
		return $post->ID;
	}

	// Is the user allowed to edit the post or page?
	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return $post->ID;
	}

	/**
	 * OK, we're authenticated: we need to find and save the data
	 * We'll put it into an array to make it easier to loop though.
	 */
	$article_details['docupress_path'] = esc_html( filter_input( INPUT_POST, 'docupress_path' ) );
	$article_details['docupress_url']  = esc_html( filter_input( INPUT_POST, 'docupress_url' ) );

	// Get estimated reading time display.
	$article_details['docupress_article_estimated_reading_display'] = esc_html( filter_input( INPUT_POST, 'docupress_article_estimated_reading_display' ) );

	// Add values of $documentdetails_meta as custom fields.
	foreach ( $article_details as $key => $value ) {
		// Don't store custom data twice.
		if ( 'revision' === $post->post_type ) {
			return;
		}
		// Make it a CSV if $value is an array (unlikely).
		$value = implode( ',', (array) $value );
		// Update metadata.
		if ( get_post_meta( $post->ID, $key, false ) ) {
			update_post_meta( $post->ID, $key, $value );
		} else {
			add_post_meta( $post->ID, $key, $value );
		}
		// Delete metadata if blank.
		if ( ! $value ) {
			delete_post_meta( $post->ID, $key );
		}
	}
}
add_action( 'save_post', 'docupress_save_article_details_meta', 1, 1 );
