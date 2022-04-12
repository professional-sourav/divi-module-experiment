<?php

class CSH_ShopExtension extends DiviExtension {

	/**
	 * The gettext domain for the extension's translations.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $gettext_domain = 'csh-shop-extension';

	/**
	 * The extension's WP Plugin name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $name = 'shop-extension';

	/**
	 * The extension's version
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $version = '1.0.0';


	protected $options = [];

	/**
	 * CSH_ShopExtension constructor.
	 *
	 * @param string $name
	 * @param array  $args
	 */
	public function __construct( $name = 'shop-extension', $args = array() ) {
		$this->plugin_dir     = plugin_dir_path( __FILE__ );
		$this->plugin_dir_url = plugin_dir_url( $this->plugin_dir );

		parent::__construct( $name, $args );

		add_action( 'et_save_post', [ $this, 'saveOptionETPost' ] );

		$this->loadOptions();

		add_action( 'wp_enqueue_scripts', [ $this, 'loadScripts' ] );
	}

	private function loadOptions() {

		$this->options['ajax_pagination']  = get_option('custom_ajax_pagination');
	}


	public function loadScripts()
	{
		wp_localize_script( "{$this->name}-frontend-bundle", 'shop_extension_options', $this->options );
	}


	public function saveOptionETPost() {

		$options     		= isset( $_POST['options'] ) ? $_POST['options'] : array(); // phpcs:ignore ET.Sniffs.ValidatedSanitizedInput -- $_POST['options'] is an array, it's value sanitization is done  at the time of accessing value.
		$layout_type 		= isset( $_POST['layout_type'] ) ? sanitize_text_field( $_POST['layout_type'] ) : '';
		$shortcode_data     = isset( $_POST['modules'] ) ? json_decode( stripslashes( $_POST['modules'] ), true ) : array(); // phpcs:ignore ET.Sniffs.ValidatedSanitizedInput -- modules string will be sanitized at the time of saving in the db.
		
		$option_value = $this->get_attr_data( $shortcode_data[0]['content'], ["csh_hello_world", "ajax_pagination"] );

		update_option( 'custom_ajax_pagination', $option_value );
	}


	private function get_attr_data($shortcode_data, $attr) {
		    
		if ( is_array($shortcode_data) ) {
						
			foreach ( $shortcode_data as $content ) {
		
				if ( $content['type'] === $attr[0]  ) {
			
					return !empty($content['attrs'][$attr[1]]) ? ($content['attrs'][$attr[1]]) : false;
				}
			
				if ( $content['type'] !== "et_pb_text" )
					return $this->get_attr_data( $content['content'], $attr );
			} 
		
			return false;
		}
	}
}

new CSH_ShopExtension;