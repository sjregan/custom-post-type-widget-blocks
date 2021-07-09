<?php
/**
 * Custom Post Type Widget Blocks Search
 *
 * @package Custom_Post_Type_Widget_Blocks
 *
 * @since 1.0.0
 */

namespace Custom_Post_Type_Widget_Blocks\Blocks;

/**
 * Core class Custom_Post_Type_Widget_Blocks_Search
 *
 * @since 1.0.0
 */
class Custom_Post_Type_Widget_Blocks_Search {
	/**
	 * Ensure that the ID attribute only appears in the markup once
	 *
	 * @since 1.0.2
	 *
	 * @static
	 * @access private
	 * @var int
	 */
	private static $block_id = 0;

	public function __construct() {
		add_action( 'init', [ $this, 'register_block_type' ] );
	}

	public function register_block_type() {
		register_block_type(
			'custom-post-type-widget-blocks/search',
			[
				'attributes'      => [
					'postType'    => [
						'type'    => 'string',
						'default' => 'any',
					],
					'align'       => [
						'type' => 'string',
						'enum' => [ 'left', 'center', 'right', 'wide', 'full' ],
					],
					'className'   => [
						'type' => 'string',
					],
					'label'       => [
						'type'    => 'string',
						'default' => __( 'Search', 'custom-post-type-widget-blocks' ),
					],
					'placeholder' => [
						'type'    => 'string',
						'default' => '',
					],
					'buttonText'  => [
						'type'    => 'string',
						'default' => __( 'Search', 'custom-post-type-widget-blocks' ),
					],
				],
				'render_callback' => [ $this, 'render_callback' ],
				'editor_script'   => 'custom-post-type-widget-blocks-editor-script',
				'editor_style'    => 'custom-post-type-widget-blocks-editor-style',
				'style'           => 'custom-post-type-widget-blocks-style',
			]
		);
	}

	public function render_callback( $attributes ) {
		self::$block_id++;

		$input_id      = 'wp-block-custom-post-type-widget-blocks-search__input-' . self::$block_id;
		$label_markup  = '';
		$button_markup = '';

		if ( ! empty( $attributes['label'] ) ) {
			$label_markup = sprintf(
				'<label for="%s" class="wp-block-custom-post-type-widget-blocks-search__label">%s</label>',
				$input_id,
				$attributes['label']
			);
		} else {
			$label_markup = sprintf(
				'<label for="%s" class="wp-block-custom-post-type-widget-blocks-search__label screen-reader-text">%s</label>',
				$input_id,
				__( 'Search', 'custom-post-type-widget-blocks' )
			);
		}

		$input_markup = sprintf(
			'<input type="search" id="%s" class="wp-block-custom-post-type-widget-blocks-search__input" name="s" value="%s" placeholder="%s" required />',
			$input_id,
			esc_attr( get_search_query() ),
			esc_attr( $attributes['placeholder'] )
		);

		if ( ! empty( $attributes['postType'] ) ) {
			$input_markup .= '<input type="hidden" name="post_type" value="' . $attributes['postType'] . '">';
		}

		if ( ! empty( $attributes['buttonText'] ) ) {
			$button_markup = sprintf(
				'<button type="submit" class="wp-block-custom-post-type-widget-blocks-search__button">%s</button>',
				$attributes['buttonText']
			);
		}

		$class = 'wp-block-custom-post-type-widget-blocks-search wp-block-search';
		if ( isset( $attributes['className'] ) ) {
			$class .= ' ' . $attributes['className'];
		}
		if ( isset( $attributes['align'] ) ) {
			$class .= ' align' . $attributes['align'];
		}

		return sprintf(
			'<form class="%s" role="search" method="get" action="%s">%s</form>',
			$class,
			esc_url( home_url( '/' ) ),
			$label_markup . $input_markup . $button_markup
		);
	}
}
