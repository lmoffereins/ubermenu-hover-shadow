<?php

/**
 * UberMenu Hover Shadow
 * 
 * @since 1.0.0
 * @author Laurens Offereins
 *
 * @package UberMenu Hover Shadow
 * @subpackage Main
 */

/**
 * Plugin Name: UberMenu Hover Shadow
 * Plugin URI:  https://github.com/lmoffereins/ubermenu-hover-shadow
 * Description: Cover the site with a shadow layer when hovering UberMenu.
 * Version:     1.0.0
 * Author:      Laurens Offereins
 * Author URI:  https://github.com/lmoffereins
 * Text Domain: umhs
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/** Front *********************************************************************/

// Run plugin on UberMenu hook
add_action( 'uberMenu_load_dependents', 'umhs_init' );

/**
 * Setup plugin hooks and filters
 * 
 * @since 1.0.0
 *
 * @uses add_action()
 */
function umhs_init() {

	// Output Styles
	add_action( 'wp_enqueue_scripts', 'umhs_enqueue_scripts' );
	add_action( 'wp_head',            'umhs_custom_styles'   );

	// Custom Styling
	add_action( 'init',               'umhs_custom_styling'   );
}

/** Output Styles *************************************************************/

/**
 * Enqueue plugin scripts and styles
 *
 * @since 1.0.0
 *
 * @uses wp_register_script()
 * @uses wp_enqueue_script()
 * @uses wp_register_style()
 * @uses wp_enqueue_style()
 */
function umhs_enqueue_scripts() {

	// Bail if on admin
	if ( is_admin() )
		return;

	// Register script
	wp_register_script( 'ubermenu-hover-shadow', plugins_url( 'js/ubermenu-hover-shadow.js', __FILE__ ), array( 'jquery', 'ubermenu' ), '1.0.0', true );
	wp_enqueue_script( 'ubermenu-hover-shadow' );

	// Register style
	wp_register_style( 'ubermenu-hover-shadow', plugins_url( 'css/ubermenu-hover-shadow.css', __FILE__ ), array(), '1.0.0' );
	wp_enqueue_style( 'ubermenu-hover-shadow' );
}

/**
 * Output custom shadow layer styles
 *
 * @since 1.0.0
 *
 * @uses UberMenu::getSettings()
 */
function umhs_custom_styles() {
	global $uberMenu;

	// Get saved settings
	$settings = $uberMenu->getSettings()->settings;
	$c = isset( $settings['umhs-shadow-color']        ) ? $settings['umhs-shadow-color']        : ''; 
	$t = isset( $settings['umhs-shadow-transparency'] ) ? $settings['umhs-shadow-transparency'] : '';

	// Bail if no settings are saved
	if ( empty( $c ) && empty( $t ) )
		return;

	// Build rgba string. Default to '0, 0, 0, 0.4'
	$rgba = '0, 0, 0';
	if ( ! empty( $c ) )
		$rgba = implode( ', ', hex2rgb( $c ) );
	if ( ! empty( $t ) )
		$rgba .= ', 0.' . ( 10 - $t ); // 10 = full transparent = 0.0
	else
		$rgba .= ', 0.4'; ?>

	<style id="umhs-custom-style" type="text/css">
		.umhs.ubermenu-hover-bg.darken {
			background-color: rgba( <?php echo $rgba; ?> );
		}
	</style>

<?php
}

/** Custom Styling ************************************************************/

/**
 * Add custom style settings to the UberMenu style options list
 * 
 * @since 1.0.0
 *
 * @uses UberMenu
 */
function umhs_custom_styling() {
	global $uberMenu;

	// Get settings vars
	$settings = $uberMenu->getSettings();
	$panel_id = 'umhs';

	// Add UberMenu Hover Shadow Style Panel
	$settings->registerPanel( $panel_id, __('Menu Hover Shadow', 'umhs'), 50 );

	// Shadow color
	$settings->addColorPicker( 
		$panel_id,
		'umhs-shadow-color',
		__('Shadow Color', 'umhs'),
		__('The color of the hover shadow. The color defaults to black.', 'umhs'),
		false
	);

	// Shadow transparency
	$settings->addSelect(
		$panel_id,
		'umhs-shadow-transparency',
		__('Shadow Transparency', 'umhs'),
		__('0 means no transparency, 100 means full transparency.', 'umhs'),
		array( 0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100 ),
		40
	);
}


/** Helpers *******************************************************************/

if ( ! function_exists( 'hex2rgb' ) ) :
	/**
	 * Convert hexadecimal color string to RGB
	 *
	 * @since 1.0.0
	 * 
	 * @param string $color Hexadecimal color string
	 * @return array RGB values
	 */
	function hex2rgb( $color ) {
		if ( $color[0] == '#' ) {
			$color = substr( $color, 1 );
		}
		if ( strlen( $color ) == 6 ) {
			list( $r, $g, $b ) = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( strlen( $color ) == 3 ) {
			list( $r, $g, $b ) = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return false;
		}
		$r = hexdec( $r );
		$g = hexdec( $g );
		$b = hexdec( $b );
		return compact( 'r', 'g', 'b' );
	}

endif;
