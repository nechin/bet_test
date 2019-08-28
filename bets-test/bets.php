<?php
/**
 * Plugin Name: Bets (test plugin)
 * Plugin URI: http://vitkalov.ru/
 * Description: This is the test plugin that allows work with bets
 * Author: Alexander Vitkalov <nechin.va@gmail.com>
 * Version: 1.0.0
 * Text Domain: bets
 * Domain Path: /languages/
 * Author URI: http://vitkalov.ru
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check WP version
global $wp_version;
if ( version_compare( $wp_version, '4.7.0', '<' ) ) {
	$wbcr_plugin_error_func = function () use ( $exception ) {
		$error = sprintf( __( 'To use the plugin "Bets (test plugin)" you need to update WordPress to %s or higher!', 'bets' ), '4.7.0' );
		echo '<div class="notice notice-error"><p>' . $error . '</p></div>';
	};

	add_action( 'admin_notices', $wbcr_plugin_error_func );
	add_action( 'network_admin_notices', $wbcr_plugin_error_func );

	return;
}

// Check PHP version
if ( version_compare( PHP_VERSION, '5.4', '<' ) ) {
	$wbcr_plugin_error_func = function () use ( $exception ) {
		$error = sprintf( __( 'To use the plugin "Bets (test plugin)" you need to update php to %s or higher!', 'bets' ), '5.4' );
		echo '<div class="notice notice-error"><p>' . $error . '</p></div>';
	};

	add_action( 'admin_notices', $wbcr_plugin_error_func );
	add_action( 'network_admin_notices', $wbcr_plugin_error_func );

	return;
}

if ( ! class_exists( 'Bets_Plugin' ) ) {
	define( 'BETS_POST_TAXONOMY_TYPE', 'bets_type' );
	define( 'BETS_POST_TAXONOMY_STATUS', 'bets_status' );
	define( 'BETS_POST_TYPE', 'bets' );
	define( 'BETS_POST_ROLE_CAPPER', 'capper' );
	define( 'BETS_POST_ROLE_MODERATOR', 'moderator' );

	define( 'BETS_PLUGIN_PATH', __FILE__ );
	define( 'BETS_PLUGIN_DIR', dirname( BETS_PLUGIN_PATH ) );
	define( 'BETS_PLUGIN_URL', plugins_url( null, BETS_PLUGIN_PATH ) );
	define( 'BETS_PLUGIN_BASE', plugin_basename( dirname( BETS_PLUGIN_PATH ) ) );

	define( 'BETS_PLUGIN_DIR_INCLUDES', BETS_PLUGIN_DIR . '/includes' );

	require_once BETS_PLUGIN_DIR_INCLUDES . '/class-bets-plugin.php';
}

try {
	$bets_base = Bets_Plugin::instance();
	$bets_base->run();
}
catch ( Exception $e ) {
	exit( $e->getMessage() );
}

/**
 * Get the instance
 *
 * @return \Bets_Plugin|null
 */
function bets() {
	return Bets_Plugin::instance();
}
