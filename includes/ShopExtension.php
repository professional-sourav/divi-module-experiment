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

		add_action( 'wp_enqueue_scripts', [ $this, 'loadScripts' ] );

		add_action( 'et_save_post', [ $this, 'saveOptionETPost' ] );

		add_filter( 'woocommerce_shortcode_products_query', [ $this, 'fetchUpsellProducts' ] );
	}


	public function loadScripts()
	{
		$this->options['ajax_pagination']  = get_option('csh_custom_woo_products_ajax_pagination');

		wp_localize_script( "{$this->name}-frontend-bundle", 'shop_extension_options', $this->options );
	}


	public function saveOptionETPost() {

		$shortcode_data     = isset( $_POST['modules'] ) ? json_decode( stripslashes( $_POST['modules'] ), true ) : array(); 
		
		$option_value = $this->get_attr_data( $shortcode_data[0]['content'], ["csh_custom_woo_products", "ajax_pagination"] );

		update_option( 'csh_custom_woo_products_ajax_pagination', $option_value );
	}

	public function fetchUpsellProducts( $args ) {

		// if ( $_POST['depends_on']['type'] === "upsells" ) {

			$items 	= $this->getAllUpsellIds();

			if ( !empty( $items['upsells'] ) ) {

				$ids_arr = [];

				// collect all the ids in a single array
				foreach ( $items['upsells'] as $upsell_ids ) {

					$ids_arr = array_merge( $ids_arr, $upsell_ids );
				}

				$args['post__in'] 		= array_unique( $ids_arr );
				$args['post__not_in'] 	= $items['products'];
			}
		// }
	
		return $args;
	}



	/**
	 * Return upsell product ids as string
	 */
	private function getAllUpsellIds() {

		$upsells 	= [];
		$products 	= [];

		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => -1
		);
	
		$loop = new WP_Query( $args );
	
		while ( $loop->have_posts() ) : $loop->the_post();

			global $product;

			$product_id = $product->is_type('variation') ? $product->get_parent_id() : $product->get_id();
    		$product 	= wc_get_product( $product_id );
    		$upsell_ids = $product->get_upsell_ids();
			
			if ( is_array( $upsell_ids ) ) {

				$upsells[] 	= $upsell_ids;
				$products[] = $product_id;
			}
		
		endwhile;
	
		wp_reset_query();

		return ["products" => $products, "upsells" => $upsells];
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
