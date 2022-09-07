<?php
/**
 * Theme wrapper
 *
 * @link http://roots.io/an-introduction-to-the-roots-theme-wrapper/
 * @link http://scribu.net/wordpress/theme-wrappers.html
 */

function mg_get_template() {
	global $post;
	@include MG_Theme_Wrapper::$main_template;
}

function mg_get_header() {
	global $post;
	@include new MG_Theme_Wrapper('partials/header.php');
}

function mg_get_footer() {
	global $post;
	@include new MG_Theme_Wrapper('partials/footer.php');
}

function mg_get_sidebar() {
	global $post;
	@include new MG_Theme_Wrapper('partials/sidebar.php');
}

class MG_Theme_Wrapper {
	// Stores the full path to the main template file
	static $main_template;

	// Stores the base name of the template file; e.g. 'page' for 'page.php' etc.
	static $base;

	public function __construct($template = 'wrapper.php') {
		$this->slug = basename($template, '.php');
		$this->templates = array($template);

		if (self::$base) {
			$str = substr($template, 0, -4);
			array_unshift($this->templates, sprintf($str . '-%s.php', self::$base));
		}
	}

	public function __toString() {
		$this->templates = apply_filters('wrap_' . $this->slug, $this->templates);
		return locate_template($this->templates);
	}

	static function wrap($main) {
		self::$main_template = $main;
		self::$base = basename(self::$main_template, '.php');

		if (self::$base === 'index') {
			self::$base = false;
		}

		return new MG_Theme_Wrapper();
	}
}
add_filter('template_include', array('MG_Theme_Wrapper', 'wrap'), 99);