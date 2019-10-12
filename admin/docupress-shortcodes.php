<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.robertdevore.com/
 * @since      1.0.0
 *
 * @package    DocuPress
 * @subpackage DocuPress/admin
 * @author     Robert DeVore <deviodigital@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Add Shortcode
function docupress_shortcode( $atts ) {

    // Attributes.
	extract( shortcode_atts(
		array(
            'limit'       => '5',
            'collections' => 'all',
            'order'       => '',
            'viewall'     => 'on',
		),
		$atts,
		'docupress'
	) );

    global $post;
    
    if ( 'all' === $collections ) {
        $docupress_articles = new WP_Query(
            array(
                'post_type' => 'docupress',
                'showposts' => $limit,
                'orderby'   => $order,
            )
        );
    } else {
        $docupress_articles = new WP_Query(
            array(
                'post_type' => 'docupress',
                'showposts' => $limit,
                'orderby'   => $order,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'docupress_collections',
                        'field'    => 'slug',
                        'terms'    => $collections
                    ),
                ),
            )
        );
    }

    $docupress_list = '<ul class="docupress-shortcode-list">';

    // Loop through the articles.
    while ( $docupress_articles->have_posts() ) : $docupress_articles->the_post();
        $docupress_list .= '<li>';
        $docupress_list .= "<a href='" . esc_url( get_permalink( $post->ID ) ) . "' class='docupress-shortcode-link'>" . get_the_title( $post->ID ) . "</a>";
        $docupress_list .= '</li>';
    endwhile; // End loop.

    wp_reset_postdata();

    // Website link.
    $websitelink = get_bloginfo( 'url' );

    if ( 'all' !== $collections ) {
        if ( 'on' === $viewall ) {
            $docupress_list .= '<li>';
            $docupress_list .= "<a href='" . $websitelink . "/collections/" . $collections . "'>" . __( 'view all', 'docupress' ) . " &rarr;</a>";
            $docupress_list .= '</li>';
        }
    }

    $docupress_list .= '</ul>';

    return $docupress_list;
}
add_shortcode( 'docupress', 'docupress_shortcode' );
