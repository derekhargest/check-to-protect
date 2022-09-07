<?php

/**
 * Register custom widget areas ( aka sidebars )
 */
register_sidebar( array(
	'name' => 'Sidebar',
	'id' => 'sidebar',
	'class' => 'widget',
	'description' => 'Sidebar',
	'before_widget' => '<li class="widget %2$s"><div class="widget-content">',
	'after_widget' => '</div></li>',
	'before_title' => '<h4 class="widget-title">',
	'after_title' => '</h4>'
));

/**
 * Load custom widgets
 */
function mg_load_widgets() {
	// register_widget('mg_custom_widget');
}
add_action('widgets_init', 'mg_load_widgets');

/**
 * Custom Widget Template
 */
class mg_custom_widget extends WP_Widget {
	function __construct() {
		$widget_ops = array(
			'classname' => 'widget_custom',
			'description' => 'Custom widget.',
			'customize_selective_refresh' => true,
		);

		parent::__construct( 'custom', 'Custom Widget', $widget_ops );
	}

	/**
	 * Determines the front end display of the widget using args from the theme
	 */
	function widget($args, $instance) {
		global $post;
		extract($args);
		$title = $instance['title'];

		echo $before_widget . $before_title . $title . $after_title;
		?>

		<?php echo $after_widget;
	}

	/**
	 * Take user settings and update/save them
	 */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

	/**
	 * Determines the back-end (cms) display used to update settings
	 */
	function form($instance) {
		$defaults = array('title' => '');
		$instance = wp_parse_args((array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text"></input>
		</p>
	<?php
	}
}