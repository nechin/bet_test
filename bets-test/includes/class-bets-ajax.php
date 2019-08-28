<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register ajax requests and execution actions
 *
 * @author Alexander Vitkalov <nechin.va@gmail.com>
 * @copyright (c) 28.08.2019, Vitkalov
 * @version 1.0
 */
class Bets_Ajax{

	/**
	 * Smart_Eps_Ajax constructor.
	 */
	public function __construct() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			add_action( 'wp_ajax_bet_create_new_bet', [ $this, 'create_new_bet' ] );
			add_action( 'wp_ajax_bet_make_a_bet', [ $this, 'make_a_bet' ] );
		}
	}

	/**
	 * Create new bet action
	 */
	public function create_new_bet() {
		$title       = isset( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';
		$description = isset( $_POST['description'] ) ? sanitize_text_field( $_POST['description'] ) : '';
		$type        = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';
		$wpnonce     = isset( $_POST['_wpnonce'] ) ? $_POST['_wpnonce'] : '';

		if ( wp_verify_nonce( $wpnonce, 'bets_add_bet' ) && ! empty( $title ) && ! empty( $description ) && ! empty( $type ) ) {
			$result = bets()->service->create_bet( $title, $description, $type );

			if ( $result ) {
				wp_send_json_success();
			}
		}

		wp_send_json_error();
	}

	/**
	 * Make a bet action
	 */
	public function make_a_bet() {
		$post_id = isset( $_POST['post_id'] ) ? sanitize_text_field( $_POST['post_id'] ) : '';
		$bet     = isset( $_POST['bet'] ) ? sanitize_text_field( $_POST['bet'] ) : '';
		$wpnonce = isset( $_POST['_wpnonce'] ) ? $_POST['_wpnonce'] : '';

		if ( wp_verify_nonce( $wpnonce, 'bets_make_bet' ) && ! empty( $bet ) ) {
			$result = bets()->service->make_bet( $post_id, $bet );

			if ( $result ) {
				wp_send_json_success();
			}
		}

		wp_send_json_error();
	}

}
