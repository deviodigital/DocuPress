<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.robertdevore.com/
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

/**
 * DocuPress Widget
 *
 * @since       1.0.0
 */
class docupress_widget extends WP_Widget {

	/**
	 * Constructor
	 *
	 * @access      public
	 * @since       1.0.0
	 * @return      void
	 */
	public function docupress_widget() {
	    parent::WP_Widget(
	        false,
	        __( 'DocuPress Articles', 'docupress' ),
	        array(
	            'description'  => __( 'Your most recent documentation articles', 'docupress' ),
	        )
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
		    $args['id'] = 'docupress_widget';
		}

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $args['id'] );

		echo $args['before_widget'];

		if ( $title ) {
		    echo $args['before_title'] . $title . $args['after_title'];
		}

		if ( 'on' === $instance['order'] ) {
			$randorder = 'rand';
		} else {
			$randorder = '';
		}

		$collections = $instance['collections'];
		
		if ( 'all' === $collections ) {
			$docupress_widget = new WP_Query(
				array(
					'post_type' => 'docupress',
					'showposts' => $instance['limit'],
					'orderby'	=> $randorder,
				)
			);
		} else {
			$docupress_widget = new WP_Query(
				array(
					'post_type' => 'docupress',
					'showposts' => $instance['limit'],
					'orderby'	=> $randorder,
					'tax_query'			=> array(
						array(
							'taxonomy'	=> 'docupress_collections',
							'field'		=> 'slug',
							'terms'		=> $collections
						),
					),
				)
			);
		}

		echo "<ul class='docupress-widget-list'>";

		while ( $docupress_widget->have_posts() ) : $docupress_widget->the_post();

			$do_not_duplicate = $post->ID;

			echo '<li>';
			echo "<a href='" . esc_url( get_permalink( $post->ID ) ) . "' class='docupress-widget-link'>" . get_the_title( $post->ID ) . "</a>";
			echo '</li>';

		endwhile; // End loop.
		
		$websitelink = get_bloginfo('home');

		if ( 'all' === $collections ) { } else {
			if ( 'on' === $instance['viewall'] ) {
				echo "<li>";
				echo "<a href='" . $websitelink . "/collections/". $collections ."'>view all &rarr;</a>";
				echo "</li>";
			}
		}

		echo '</ul>';

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

	    $instance['title']              = strip_tags( $new_instance['title'] );
	    $instance['limit']              = strip_tags( $new_instance['limit'] );
	    $instance['collections']        = $new_instance['collections'];
	    $instance['order']              = $new_instance['order'];
	    $instance['viewall']            = $new_instance['viewall'];

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
	    $defaults = array(
	        'title'    			=> 'Recent Articles',
	        'limit'  			=> '5',
			'collections'       => '',
	        'order'  			=> '',
			'viewall'           => '',
	    );

			$instance = wp_parse_args( (array) $instance, $defaults );
	?>
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget Title:', 'docupress' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_html( $instance['title'] ); ?>" />
	</p>

	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php esc_html_e( 'Amount of articles to show:', 'docupress' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" type="number" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" min="1" max="999" value="<?php echo esc_html( $instance['limit'] ); ?>" />
	</p>

	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'collections' ) ); ?>"><?php _e( 'Collections:', 'docupress' ); ?></label>
		<?php 
			$terms = get_terms( 'docupress_collections' );
			if ( $terms ) {
				printf( '<select name="%s" id="'. $this->get_field_id( 'collections' ) .'" name="'. $this->get_field_name( 'collections' ) .'" class="widefat">', esc_attr( $this->get_field_name( 'collections' ) ) );
					if ( esc_attr( $term->slug ) == $instance['collections'] ) {
						$collectionsinfo = '';
					} else {
						$collectionsinfo = 'selected="selected"';
					}
					printf( '<option value="%s" '. $collectionsinfo .'>%s</option>', 'all', 'All' );
				foreach ( $terms as $term ) {
					if ( esc_attr( $term->slug ) == $instance['collections'] ) {
						$collectionsinfo = 'selected="selected"';
					} else {
						$collectionsinfo = '';
					}
					printf( '<option value="%s" '. $collectionsinfo .'>%s</option>', esc_attr( $term->slug ), esc_html( $term->name ) );
				}
				print( '</select>' );
			}
		?>
	</p>

	<p>
		<input class="checkbox" type="checkbox" <?php checked( $instance['order'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>" />
		<label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Randomize output?', 'docupress' ); ?></label>
	</p>

	<p>
		<input class="checkbox" type="checkbox" <?php checked( $instance['viewall'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'viewall' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'viewall' ) ); ?>" />
		<label for="<?php echo esc_attr( $this->get_field_id( 'viewall' ) ); ?>"><?php esc_html_e( 'Display link to all articles in collection?', 'docupress' ); ?></label>
	</p>

	<?php
	}
}

/**
 * Register the new widget
 *
 * @since       1.0.0
 * @return      void
 */
function DocuPress_register_widget() {
	register_widget( 'docupress_widget' );
}
add_action( 'widgets_init', 'DocuPress_register_widget' );

?>