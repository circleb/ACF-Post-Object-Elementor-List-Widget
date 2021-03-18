<?php
/**
 * Plugin Name:       ACF Post Object Elementor List Widget
 * Plugin URI:        github.com/circleb/ACF-Post-Object-Elementor-List-Widget
 * Description:       Adds the ability to display the contents of an ACF Post Object field as a list of post links.
 * Version:           0.5.0
 * Author:            Ben Owen
 * License:           GPLv3
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

final class Elementor_ACF_PO_Extension {
	const VERSION = '0.5.0';
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';
	const MINIMUM_PHP_VERSION = '7.0';

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		add_action( 'plugins_loaded', [ $this, 'on_plugins_loaded' ] );
	}

	public function i18n() {
		load_plugin_textdomain( 'elementor-acf-po-li-extension' );
	}

	public function on_plugins_loaded() {
		if ( $this->is_compatible() ) {
			add_action( 'elementor/init', [ $this, 'init' ] );
		}
	}

	public function is_compatible() {

		// Check if Elementor is installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return false;
		}

		// Check if ACF is installed and activated
		if ( ! class_exists('ACF') ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_acf_plugin' ] );
			return false;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return false;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return false;
		}

		return true;

	}

	public function init() {
	
		$this->i18n();

		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );

	}

	public function init_widgets() {

		// Include Widget files
		require_once( __DIR__ . '/widget.php' );

		// Register widget
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_ACF_PO_Widget() );

	}

	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementor-acf-po-li-extension' ),
			'<strong>' . esc_html__( 'Elementor ACF PO Extension', 'elementor-acf-po-li-extension' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-acf-po-li-extension' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	public function admin_notice_missing_acf_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" or "%3$s" to be installed and activated.', 'elementor-acf-po-li-extension' ),
			'<strong>' . esc_html__( 'Elementor ACF PO Extension', 'elementor-acf-po-li-extension' ) . '</strong>',
			'<strong>' . esc_html__( 'Advanced Custom Fields', 'elementor-acf-po-li-extension' ) . '</strong>',
			'<strong>' . esc_html__( 'Advanced Custom Fields PRO', 'elementor-acf-po-li-extension' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-acf-po-li-extension' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'elementor-acf-po-li-extension' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-acf-po-li-extension' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-acf-po-li-extension' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'elementor-acf-po-li-extension' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'elementor-acf-po-li-extension' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

}

Elementor_ACF_PO_Extension::instance();
