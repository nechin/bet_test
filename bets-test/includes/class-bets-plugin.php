<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Bets Plugin class
 *
 * @author Alexander Vitkalov <nechin.va@gmail.com>
 * @copyright (c) 28.08.2019, Vitkalov
 * @version 1.0
 */
class Bets_Plugin{

	/**
	 * @var null
	 */
	protected static $_instance = null;

	/**
	 * @var null
	 */
	public $hooks = null;

	/**
	 * @var null
	 */
	public $service = null;

	/**
	 * Bets_Base constructor.
	 */
	public function __construct() {
		$this->includes();

		$this->hooks   = new Bets_Hooks();
		$this->service = new Bets_Service();
		new Bets_Ajax();

		$this->bind_hooks();
	}

	/**
	 * Get instatce
	 *
	 * @return Bets_Plugin|null
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Include files
	 */
	private function includes() {
		require_once BETS_PLUGIN_DIR_INCLUDES . '/class-bets-hooks.php';
		require_once BETS_PLUGIN_DIR_INCLUDES . '/class-bets-service.php';
		require_once BETS_PLUGIN_DIR_INCLUDES . '/class-bets-ajax.php';
	}

	/**
	 * Bind hooks
	 */
	private function bind_hooks() {
		add_action( 'init', [ $this->hooks, 'wp_init' ] );
		add_action( 'admin_init', [ $this->hooks, 'load_plugin_textdomain' ] );
		add_action( 'muplugins_loaded', [ $this->hooks, 'load_mu_plugin_textdomain' ] );
		add_action( 'save_post_' . BETS_POST_TYPE, [ $this->hooks, 'save_post' ], 10, 3 );
	}

	/**
	 * Run execution
	 */
	public function run() {
	}

}
