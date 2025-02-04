<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://deviodigital.com
 * @since      1.0.0
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
 * DocuPress Articles Widget
 *
 * @since 1.0.0
 */
class DocuPress_Articles_Widget extends WP_Widget {

    /**
     * Constructor
     *
     * @access public
     * @since  1.0.0
     * @return void
     */
    public function __construct() {

        parent::__construct(
            'docupress_articles_widget',
            __( 'DocuPress Articles', 'docupress' ),
            [
                'description' => esc_attr__( 'Display documentation articles', 'docupress' ),
                'classname'   => 'docupress-widget',
            ]
        );

    }

    /**
     * Widget definition
     *
     * @access      public
     * @since       1.0.0
     * @see         WP_Widget::widget
     * @param       array $args Arguments to pass to the widget.
     * @param       array $instance A given widget instance.
     * @return      void
     */
    public function widget( $args, $instance ) {
        if ( ! isset( $args['id'] ) ) {
            $args['id'] = 'docupress_articles_widget';
        }

        $title = apply_filters( 'widget_title', $instance['title'], $instance, $args['id'] );

        echo $args['before_widget'];

        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // Empty var.
        $randorder = '';

        // Randomize order?
        if ( 'on' === $instance['order'] ) {
            $randorder = 'rand';
        }

        // Get collections from widget settings.
        $collections = $instance['collections'];

        // Setup the WP_Query.
        if ( 'all' === $collections ) {
            $articles_widget = new WP_Query(
                [
                    'post_type' => 'docupress',
                    'showposts' => $instance['limit'],
                    'orderby'   => $randorder,
                ]
            );
        } else {
            $articles_widget = new WP_Query(
                [
                    'post_type' => 'docupress',
                    'showposts' => $instance['limit'],
                    'orderby'   => $randorder,
                    'tax_query' => [
                        [
                            'taxonomy' => 'docupress_collections',
                            'field'    => 'slug',
                            'terms'    => $collections
                        ],
                    ],
                ]
            );
        }

        $articles = '<ul class="docupress-widget-list">';

        // Loop through articles.
        while ( $articles_widget->have_posts() ) : $articles_widget->the_post();
            $articles .= '<li>';
            $articles .= '<a href="' . esc_url( get_permalink( $articles_widget->ID ) ) . '" class="docupress-widget-link">' . get_the_title( $articles_widget->ID ) . '</a>';
            $articles .= '</li>';
        endwhile;

        wp_reset_postdata();

        // Add link to all collections?
        if ( 'all' !== $collections && 'on' === $instance['viewall'] ) {
            $articles .= '<li>';
            $articles .= '<a href="' . get_bloginfo( 'url' ) . '/collections/' . $collections . '">' . esc_attr__( 'view all', 'docupress' ) . ' &rarr;</a>';
            $articles .= '</li>';
        }

        $articles .= '</ul>';

        echo $articles;

        echo $args['after_widget'];
    }


    /**
     * Update widget options
     *
     * @access      public
     * @since       1.0.0
     * @see         WP_Widget::update
     * @param       array $new_instance The updated options.
     * @param       array $old_instance The old options.
     * @return      array $instance The updated instance options
     */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['title']       = strip_tags( $new_instance['title'] );
        $instance['limit']       = strip_tags( $new_instance['limit'] );
        $instance['collections'] = $new_instance['collections'];
        $instance['order']       = $new_instance['order'];
        $instance['viewall']     = $new_instance['viewall'];

        return $instance;
    }


    /**
     * Display widget form on dashboard
     *
     * @access      public
     * @since       1.0.0
     * @see         WP_Widget::form
     * @param       array $instance A given widget instance.
     * @return      void
     */
    public function form( $instance ) {
        $defaults = [
            'title'       => 'Documentation',
            'limit'       => '5',
            'collections' => '',
            'order'       => '',
            'viewall'     => '',
        ];

        $instance = wp_parse_args( (array) $instance, $defaults );
    ?>
    <p>
        <label for="<?php esc_attr_e( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget Title:', 'docupress' ); ?></label>
        <input class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'title' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php esc_attr_e( $instance['title'] ); ?>" />
    </p>

    <p>
        <label for="<?php esc_attr_e( $this->get_field_id( 'limit' ) ); ?>"><?php esc_html_e( 'Amount of articles to show:', 'docupress' ); ?></label>
        <input class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'limit' ) ); ?>" type="number" name="<?php esc_attr_e( $this->get_field_name( 'limit' ) ); ?>" min="1" max="999" value="<?php esc_attr_e( $instance['limit'] ); ?>" />
    </p>

    <p>
        <label for="<?php esc_attr_e( $this->get_field_id( 'collections' ) ); ?>"><?php esc_attr_e( 'Collections:', 'docupress' ); ?></label>
        <?php 
            $terms = get_terms( 'docupress_collections' );
            if ( $terms ) {
                printf( '<select name="%s" id="'. $this->get_field_id( 'collections' ) .'" name="'. $this->get_field_name( 'collections' ) .'" class="widefat">', esc_attr( $this->get_field_name( 'collections' ) ) );
                    if ( 'all' !== $instance['collections'] ) {
                        $selected = '';
                    } else {
                        $selected = 'selected="selected"';
                    }
                    printf( '<option value="%s" '. esc_html( $selected ) .'>%s</option>', 'all', 'All' );
                    foreach ( $terms as $term ) {
                        if ( esc_attr( $term->slug ) == $instance['collections'] ) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        printf( '<option value="%s" ' . esc_html( $selected ) . '>%s</option>', esc_attr( $term->slug ), esc_html( $term->name ) );
                    }
                print( '</select>' );
            }
        ?>
    </p>

    <p>
        <input class="checkbox" type="checkbox" <?php checked( $instance['order'], 'on' ); ?> id="<?php esc_attr_e( $this->get_field_id( 'order' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'order' ) ); ?>" />
        <label for="<?php esc_attr_e( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Randomize output?', 'docupress' ); ?></label>
    </p>

    <p>
        <input class="checkbox" type="checkbox" <?php checked( $instance['viewall'], 'on' ); ?> id="<?php esc_attr_e( $this->get_field_id( 'viewall' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'viewall' ) ); ?>" />
        <label for="<?php esc_attr_e( $this->get_field_id( 'viewall' ) ); ?>"><?php esc_html_e( 'Display link to all articles in collection?', 'docupress' ); ?></label>
    </p>

    <?php
    }
}

/**
 * DocuPress Collections Widget
 *
 * @since 1.0.0
 */
class DocuPress_Collections_Widget extends WP_Widget {

    /**
     * Constructor
     *
     * @access      public
     * @since       1.0.0
     * @return      void
     */
    public function __construct() {

        parent::__construct(
            'docupress_collections_widget',
            esc_attr__( 'DocuPress Collections', 'docupress' ),
            [
                'description' => esc_attr__( 'Display a list of collections', 'docupress' ),
                'classname'   => 'docupress-collections-widget',
            ]
        );

    }

    /**
     * Widget definition
     *
     * @access      public
     * @since       1.0.0
     * @see         WP_Widget::widget
     * @param       array $args Arguments to pass to the widget.
     * @param       array $instance A given widget instance.
     * @return      void
     */
    public function widget( $args, $instance ) {
        if ( ! isset( $args['id'] ) ) {
            $args['id'] = 'docupress_collections_widget';
        }

        $title = apply_filters( 'widget_title', $instance['title'], $instance, $args['id'] );

        echo $args['before_widget'];

        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // Get all collections.
        $terms = get_terms( 'docupress_collections' );

        // Display collections in unordered list.
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            $collections = '<ul class="docupress-widget-list">';
            foreach ( $terms as $term ) {
                $collections .= '<li><a href="' . esc_url( get_term_link( $term ) ) . '" alt="' . esc_attr( sprintf( esc_html__( 'View all articles filed under %s', 'docupress' ), $term->name ) ) . '">' . $term->name . '</li></a>';
            }
            $collections .= '</ul>';
            echo $collections;
        }

        echo $args['after_widget'];
    }


    /**
     * Update widget options
     *
     * @access      public
     * @since       1.0.0
     * @see         WP_Widget::update
     * @param       array $new_instance The updated options.
     * @param       array $old_instance The old options.
     * @return      array $instance The updated instance options
     */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['limit'] = strip_tags( $new_instance['limit'] );

        return $instance;
    }

    /**
     * Display widget form on dashboard
     *
     * @access      public
     * @since       1.0.0
     * @see         WP_Widget::form
     * @param       array $instance A given widget instance.
     * @return      void
     */
    public function form( $instance ) {
        $defaults = [
            'title' => esc_attr__( 'Collections', 'docupress' ),
            'limit' => '5',
        ];

        $instance = wp_parse_args( (array) $instance, $defaults );
    ?>
    <p>
        <label for="<?php esc_attr_e( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget Title:', 'docupress' ); ?></label>
        <input class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'title' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php esc_attr_e( $instance['title'] ); ?>" />
    </p>

    <?php
    }
}

/**
 * DocuPress Related Articles Widget
 *
 * @since 1.4.0
 */
class DocuPress_Related_Articles_Widget extends WP_Widget {

    /**
     * Constructor
     *
     * @access      public
     * @since       1.0.0
     * @return      void
     */
    public function __construct() {

        parent::__construct(
            'docupress_related_articles_widget',
            esc_attr__( 'DocuPress Related Articles', 'docupress' ),
            [
                'description' => esc_attr__( 'Display related articles', 'docupress' ),
                'classname'   => 'docupress-widget',
            ]
        );

    }

    /**
     * Widget definition
     *
     * @access      public
     * @since       1.0.0
     * @see         WP_Widget::widget
     * @param       array $args Arguments to pass to the widget.
     * @param       array $instance A given widget instance.
     * @return      void
     */
    public function widget( $args, $instance ) {
        if ( ! isset( $args['id'] ) ) {
            $args['id'] = 'docupress_related_articles_widget';
        }

        $title = apply_filters( 'widget_title', $instance['title'], $instance, $args['id'] );

        // Empty var.
        $randorder = '';

        // Randomize order?
        if ( 'on' === $instance['order'] ) {
            $randorder = 'rand';
        }

        // Get current article post terms.
        $terms = get_the_terms( get_the_ID(), 'docupress_collections' );

        // Pluck out the IDs to get an array of IDS.
        $term_ids = wp_list_pluck( $terms, 'term_id' );

        // Setup WP_Query.
        $related_articles = new WP_Query(
            [
                'post_type'    => 'docupress',
                'showposts'    => $instance['limit'],
                'orderby'      => $randorder,
                'post__not_in' => [ get_the_ID() ],
                'tax_query'    => [
                    [
                        'taxonomy' => 'docupress_collections',
                        'field'    => 'id',
                        'terms'    => $term_ids,
                        'operator' => 'IN'
                    ],
                ],
            ]
        );

        // Are related articles found?
        $related_articles->get_posts();

        // Only display related articles if results are found.
        if ( is_singular( 'docupress' ) && null != $related_articles->have_posts() ) {

            echo $args['before_widget'];

            if ( $title ) {
                echo $args['before_title'] . $title . $args['after_title'];
            }

            $articles = '<ul class="docupress-widget-list">';

            // Loop through articles.
            while ( $related_articles->have_posts() ) : $related_articles->the_post();
                $articles .= '<li>';
                $articles .= '<a href="' . esc_url( get_permalink( $related_articles->ID ) ) . '" class="docupress-widget-link">' . get_the_title( $related_articles->ID ) . '</a>';
                $articles .= '</li>';
            endwhile;

            wp_reset_postdata();

            if ( isset( $collections ) && 'all' !== $collections && 'on' === $instance['viewall'] ) {
                $articles .= '<li>';
                $articles .= '<a href="' . get_bloginfo( 'url' ) . '/collections/' . $collections . '">' . esc_attr__( 'view all', 'docupress' ) . ' &rarr;</a>';
                $articles .= '</li>';
            }

            $articles .= '</ul>';

            echo $articles;

            echo $args['after_widget'];
        }
    }

    /**
     * Update widget options
     *
     * @access      public
     * @since       1.0.0
     * @see         WP_Widget::update
     * @param       array $new_instance The updated options.
     * @param       array $old_instance The old options.
     * @return      array $instance The updated instance options
     */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['title']   = strip_tags( $new_instance['title'] );
        $instance['limit']   = strip_tags( $new_instance['limit'] );
        $instance['order']   = $new_instance['order'];
        $instance['viewall'] = $new_instance['viewall'];

        return $instance;
    }

    /**
     * Display widget form on dashboard
     *
     * @access      public
     * @since       1.0.0
     * @see         WP_Widget::form
     * @param       array $instance A given widget instance.
     * @return      void
     */
    public function form( $instance ) {
        $defaults = [
            'title'       => esc_attr__( 'Related Articles', 'docupress' ),
            'limit'       => '5',
            'order'       => '',
            'viewall'     => '',
        ];

        $instance = wp_parse_args( (array) $instance, $defaults );
    ?>
    <p>
        <label for="<?php esc_attr_e( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget Title:', 'docupress' ); ?></label>
        <input class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'title' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php esc_attr_e( $instance['title'] ); ?>" />
    </p>

    <p>
        <label for="<?php esc_attr_e( $this->get_field_id( 'limit' ) ); ?>"><?php esc_html_e( 'Amount of articles to show:', 'docupress' ); ?></label>
        <input class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'limit' ) ); ?>" type="number" name="<?php esc_attr_e( $this->get_field_name( 'limit' ) ); ?>" min="1" max="999" value="<?php esc_attr_e( $instance['limit'] ); ?>" />
    </p>

    <p>
        <input class="checkbox" type="checkbox" <?php checked( $instance['order'], 'on' ); ?> id="<?php esc_attr_e( $this->get_field_id( 'order' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'order' ) ); ?>" />
        <label for="<?php esc_attr_e( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Randomize output?', 'docupress' ); ?></label>
    </p>

    <p>
        <input class="checkbox" type="checkbox" <?php checked( $instance['viewall'], 'on' ); ?> id="<?php esc_attr_e( $this->get_field_id( 'viewall' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'viewall' ) ); ?>" />
        <label for="<?php esc_attr_e( $this->get_field_id( 'viewall' ) ); ?>"><?php esc_html_e( 'Display link to all articles in collection?', 'docupress' ); ?></label>
    </p>

    <?php
    }
}

/**
 * Register the new widgets
 *
 * @since  1.0.0
 * @return void
 */
function docupress_register_widgets() {
    register_widget( 'docupress_articles_widget' );
    register_widget( 'docupress_collections_widget' );
    register_widget( 'docupress_related_articles_widget' );
}
add_action( 'widgets_init', 'docupress_register_widgets' );
