<?php

class CSH_CustomWooProducts extends ET_Builder_Module_Shop {

	protected $module_credits = array(
		'module_uri' => '',
		'author'     => '',
		'author_uri' => '',
	);

	public function init() {

		$this->slug       	= 'csh_custom_woo_products';
		$this->vb_support 	= 'on';
		$this->name 		= esc_html__( 'Custom Woo Products', 'csh-shop-extension' );
		$this->folder_name 	= 'et_pb_woo_modules';
	}

	public function get_fields() {

		$fields = parent::get_fields();
		$fields['type']['options']['upsells'] = __("Upsell Products");
		$fields['ajax_pagination'] = array(
			'label'            => esc_html__( 'AJAX Pagination', 'csh_custom_woo_products' ),
			'type'             => 'yes_no_button',
			'option_category'  => 'configuration',
			'options'          => array(
				'on'  => et_builder_i18n( 'Yes' ),
				'off' => et_builder_i18n( 'No' ),
			),
			'default'          => 'off',
			'description'      => esc_html__( 'Turn AJAX functionality of pagination on and off.', 'csh_custom_woo_products' ),
			'computed_affects' => array(
				'__shop',
			),
			'toggle_slug'      => 'elements',
			'mobile_options'   => true,
			'hover'            => 'tabs',
		);

		return $fields;
	}

	/**
	 * Renders the module output.
	 *
	 * @param  array  $attrs       List of attributes.
	 * @param  string $content     Content being processed.
	 * @param  string $render_slug Slug of module that is used for rendering output.
	 *
	 * @return string
	 */
	public function render( $attrs, $content, $render_slug ) {

		return parent::render( $attrs, $content, $render_slug );
	}

}

new CSH_CustomWooProducts;
