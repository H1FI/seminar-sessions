<?php
/**
 * WordCamp.org Post Types Widgets
 */

if ( ! class_exists( 'WCB_Widget_Sponsors' ) ) :
/**
 * Sponsors widget class.
 *
 * @see WordCamp_Post_Types_Plugin::register_widgets()
 */
class WCB_Widget_Sponsors extends WP_Widget {

	function __construct() {
		$widget_ops = array(
			'classname' => 'wcb_widget_sponsors',
			'description' => __( 'Your Sponsors', 'seminar-sessions' ),
		);
		WP_Widget::__construct( 'wcb_sponsors', __( 'Sponsors', 'seminar-sessions' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		/** @var $wcpt_plugin WordCamp_Post_Types_Plugin */
		global $wcpt_plugin;

		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		// Fetch sponsor levels
		$terms = $wcpt_plugin->get_sponsor_levels();
		?>

		<style>
			.wcb_widget_sponsors .sponsor-logo {
				display: block;
			}
			.wcb_widget_sponsors .sponsor-logo img {
				max-width: 100%;
				height: auto;
			}
		</style>

		<?php foreach ( $terms as $term ) : ?>
			<?php
				$sponsors = new WP_Query( array(
					'post_type' => 'wcb_sponsor',
					'posts_per_page' => -1,
					'order' => 'ASC',
					'taxonomy' => $term->taxonomy,
					'term' => $term->slug,
				) );

				if ( ! $sponsors->have_posts() )
					continue;
			?>

			<div class="sponsor-level <?php echo $term->slug; ?>">
				<h4 class="sponsor-level-title"><?php echo esc_html( $term->name ); ?></h4>

				<?php while ( $sponsors->have_posts() ) : $sponsors->the_post(); ?>

					<a class="sponsor-logo" href="<?php the_permalink(); ?>">
						<?php ( has_post_thumbnail() ) ? the_post_thumbnail( array( 200, 150 ) ) : the_title(); ?>
					</a>

				<?php endwhile; ?>

			</div><!-- .sponsor-level -->
		<?php endforeach; ?>

		<?php
		echo $after_widget;
		wp_reset_postdata();
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = $instance['title'];
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'seminar-sessions' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '' ) );
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}
}
endif; // class_exists

/**
 * Speakers widget class.
 *
 * Allows organizers to showcase their speakers with a few options. Useful for a
 * Featured Speakers block, can be randomized and is cached for $cache_time seconds.
 *
 * @uses get_transient(), set_transient(), do_shortcode()
 * @see WordCamp_Post_Types_Plugin->shortcode_speakers()
 */
class WCPT_Widget_Speakers extends WP_Widget {

	protected $cache_time = 3600; // seconds

	function __construct() {
		$widget_ops = array(
			'classname' => 'wcpt_widget_speakers',
			'description' => __( 'Your Speakers', 'seminar-sessions' ),
		);
		WP_Widget::__construct( 'wcpt_speakers', __( 'Speakers', 'seminar-sessions' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		/** @var $wcpt_plugin WordCamp_Post_Types_Plugin */
		global $wcpt_plugin;

		$transient_key = 'wcpt-' . md5( $args['widget_id'] );
		$instance['title'] = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );


		echo $args['before_widget'];
		if ( $instance['title'] )
			echo $args['before_title'] . $instance['title'] . $args['after_title'];

		if ( false === ( $widget_content = get_transient( $transient_key ) ) ) {
			// Shortcode attributes
			$attr = array();

			if ( $instance['count'] )
				$attr['posts_per_page'] = $instance['count'];

			if ( $instance['random'] )
				$attr['orderby'] = 'rand';

			if ( $instance['avatar_size'] )
				$attr['avatar_size'] = strip_tags( $instance['avatar_size'] );

			if ( $instance['category'] )
				$attr['category'] = strip_tags( $instance['category'] );

			$attr_str = array();
			foreach ( $attr as $key => $value )
				$attr_str[ $key ] = sprintf( '%s="%s"', $key, esc_attr( $value ) );
			$attr_str = implode( ' ', $attr_str );

			// Run and store cached version.
			$widget_content = do_shortcode( "[speakers {$attr_str}]" );
			set_transient( $transient_key, $widget_content, $this->cache_time );
		}

		echo $widget_content;

		$speakers_permalink = $wcpt_plugin->get_wcpt_permalink( 'speakers' );
		if ( ! empty( $speakers_permalink ) )
			printf( '<a class="wcpt-speakers-link" href="%s">%s</a>', esc_url( $speakers_permalink ), esc_html( __( 'View all speakers &rarr;', 'seminar-sessions' ) ) );

		echo $args['after_widget'];

		wp_reset_postdata();
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
			'title' => '',
			'count' => 3,
			'random' => false,
			'avatar_size' => 150,
			'category' => 0
		) );

		$title = $instance['title'];
		$count = absint( $instance['count'] );
		$random = (bool) $instance['random'];
		$avatar_size = $instance['avatar_size'];
		$category = $instance['category'];

		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'seminar-sessions' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Number of speakers to show:', 'seminar-sessions' ); ?> <input type="text" size="3" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" value="<?php echo esc_attr( $count ); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id( 'avatar_size' ); ?>"><?php _e( 'Speaker image size (in px or name, eg. medium):', 'seminar-sessions' ); ?> <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'avatar_size' ); ?>" name="<?php echo $this->get_field_name( 'avatar_size' ); ?>" value="<?php echo esc_attr( $avatar_size ); ?>" /></label></p>
		<p>
			<label for="<?php echo $this->get_field_name( 'category' ) ?>"><?php _e( 'Only show speakers from this category:', 'seminar-sessions' ) ?></label>
			<?php wp_dropdown_categories( array(
					'name' => $this->get_field_name( 'category' ),
					'id' => $this->get_field_name( 'category' ),
					'taxonomy' => 'wcb_speakercat',
					'value_field' => 'slug',
					'hide_empty' => 0,
					'show_option_all' => __( 'All categories', 'seminar-sessions' ),
					'selected' => esc_attr( $category )
			) ); ?>
		</p>
		<p>
			<input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id( 'random' ); ?>" name="<?php echo $this->get_field_name( 'random' ); ?>" <?php checked( $random ); ?> />
			<label for="<?php echo $this->get_field_id( 'random' ); ?>"><?php _e( 'Display in random order', 'seminar-sessions' ); ?></label>
		</p>

		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array(
			'title' => '',
			'count' => 3,
			'random' => false,
			'avatar_size' => 150,
			'category' => 0
		) );

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['avatar_size'] = strip_tags( $new_instance['avatar_size'] );
		$instance['count'] = absint( $new_instance['count'] );
		$instance['random'] = (bool) $new_instance['random'];
		$instance['category'] = strip_tags( $new_instance['category'] );

		// Clear transient cache
		$transient_key = 'wcpt-' . md5( $this->id );
		delete_transient( $transient_key );
		return $instance;
	}
}

/**
 * Sessions widget class.
 *
 * Useful to showcase a featured session in your sidebar.
 *
 * @uses get_transient(), set_transient(), do_shortcode()
 * @see WordCamp_Post_Types_Plugin->shortcode_sessions()
 */
class WCPT_Widget_Sessions extends WP_Widget {

	protected $cache_time = 3600; // seconds

	function __construct() {
		$widget_ops = array(
			'classname' => 'wcpt_widget_sessions',
			'description' => __( 'Show off your sessions', 'seminar-sessions' ),
		);
		WP_Widget::__construct( 'wcpt_sessions', __( 'Sessions', 'seminar-sessions' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		/** @var $wcpt_plugin WordCamp_Post_Types_Plugin */
		global $wcpt_plugin;

		$transient_key = 'wcpt-' . md5( $args['widget_id'] );
		$instance['title'] = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'];
		if ( $instance['title'] )
			echo $args['before_title'] . $instance['title'] . $args['after_title'];

		if ( false === ( $widget_content = get_transient( $transient_key ) ) ) {
			// Shortcode attributes
			$attr = array(
				'show_avatars' => 'true',
				'show_meta' => 'true',
				'speaker_link' => 'anchor',
			);

			if ( $instance['count'] )
				$attr['posts_per_page'] = $instance['count'];

			if ( $instance['random'] )
				$attr['orderby'] = 'rand';

			$attr_str = array();
			foreach ( $attr as $key => $value )
				$attr_str[ $key ] = sprintf( '%s="%s"', $key, esc_attr( $value ) );
			$attr_str = implode( ' ', $attr_str );

			// Run and store cached version.
			$widget_content = do_shortcode( "[sessions {$attr_str}]" );
			set_transient( $transient_key, $widget_content, $this->cache_time );
		}

		echo $widget_content;

		$sessions_permalink = $wcpt_plugin->get_wcpt_permalink( 'sessions' );
		if ( ! empty( $sessions_permalink ) )
			printf( '<a class="wcpt-sessions-link" href="%s">%s</a>', esc_url( $sessions_permalink ), esc_html( __( 'View all sessions &rarr;', 'seminar-sessions' ) ) );

		echo $args['after_widget'];

		wp_reset_postdata();
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
			'title' => '',
			'count' => 3,
			'random' => false,
		) );

		$title = $instance['title'];
		$count = absint( $instance['count'] );
		$random = (bool) $instance['random'];
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'seminar-sessions' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Number of speakers to show:', 'seminar-sessions' ); ?> <input type="text" size="3" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" value="<?php echo esc_attr( $count ); ?>" /></label></p>
		<p>
			<input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id( 'random' ); ?>" name="<?php echo $this->get_field_name( 'random' ); ?>" <?php checked( $random ); ?> />
			<label for="<?php echo $this->get_field_id( 'random' ); ?>"><?php _e( 'Display in random order', 'seminar-sessions' ); ?></label>
		</p>

		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array(
			'title' => '',
			'count' => 3,
			'random' => false,
		) );

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['count'] = absint( $new_instance['count'] );
		$instance['random'] = (bool) $new_instance['random'];

		// Clear transient cache
		$transient_key = 'wcpt-' . md5( $this->id );
		delete_transient( $transient_key );
		return $instance;
	}
}

/**
 * Organizers widget class.
 *
 * Show off the organizing team in the sidebar.
 *
 * @uses get_transient(), set_transient(), do_shortcode()
 * @see WordCamp_Post_Types_Plugin->shortcode_organizers()
 */
class WCPT_Widget_Organizers extends WP_Widget {
	protected $cache_time = 3600; // seconds

	function __construct() {
		$widget_ops = array(
			'classname' => 'wcpt_widget_organizers',
			'description' => __( 'Display your organizing team in the sidebar', 'seminar-sessions' ),
		);
		WP_Widget::__construct( 'wcpt_organizers', __( 'Organizers', 'seminar-sessions' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		/** @var $wcpt_plugin WordCamp_Post_Types_Plugin */
		global $wcpt_plugin;

		$transient_key = 'wcpt-' . md5( $args['widget_id'] );
		$instance['title'] = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'];
		if ( $instance['title'] )
			echo $args['before_title'] . $instance['title'] . $args['after_title'];

		if ( false === ( $widget_content = get_transient( $transient_key ) ) ) {
			// Shortcode attributes
			$attr = array();

			if ( $instance['count'] )
				$attr['posts_per_page'] = $instance['count'];

			if ( $instance['random'] )
				$attr['orderby'] = 'rand';

			$attr_str = array();
			foreach ( $attr as $key => $value )
				$attr_str[ $key ] = sprintf( '%s="%s"', $key, esc_attr( $value ) );
			$attr_str = implode( ' ', $attr_str );

			// Run and store cached version.
			$widget_content = do_shortcode( "[organizers {$attr_str}]" );
			set_transient( $transient_key, $widget_content, $this->cache_time );
		}

		echo $widget_content;

		$organizers_permalink = $wcpt_plugin->get_wcpt_permalink( 'organizers' );
		if ( ! empty( $organizers_permalink ) )
			printf( '<a class="wcpt-organizers-link" href="%s">%s</a>', esc_url( $organizers_permalink ), esc_html( __( 'Organizing team &rarr;', 'seminar-sessions' ) ) );

		echo $args['after_widget'];

		wp_reset_postdata();
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
			'title' => '',
			'count' => 3,
			'random' => false,
		) );

		$title = $instance['title'];
		$count = absint( $instance['count'] );
		$random = (bool) $instance['random'];
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'seminar-sessions' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Number of organizers to show:', 'seminar-sessions' ); ?> <input type="text" size="3" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" value="<?php echo esc_attr( $count ); ?>" /></label></p>
		<p>
			<input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id( 'random' ); ?>" name="<?php echo $this->get_field_name( 'random' ); ?>" <?php checked( $random ); ?> />
			<label for="<?php echo $this->get_field_id( 'random' ); ?>"><?php _e( 'Display in random order', 'seminar-sessions' ); ?></label>
		</p>

		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array(
			'title' => '',
			'count' => 3,
			'random' => false,
		) );

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['count'] = absint( $new_instance['count'] );
		$instance['random'] = (bool) $new_instance['random'];

		// Clear transient cache
		$transient_key = 'wcpt-' . md5( $this->id );
		delete_transient( $transient_key );
		return $instance;
	}
}
