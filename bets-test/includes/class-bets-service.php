<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Service class with varios functions
 *
 * @author Alexander Vitkalov <nechin.va@gmail.com>
 * @copyright (c) 28.08.2019, Vitkalov
 * @version 1.0
 */
class Bets_Service{
	/**
	 * Bets_Service constructor.
	 */
	public function __construct() {
	}

	/**
	 * Set post template
	 *
	 * @param $post_id
	 */
	public function set_post_template( $post_id ) {
		update_post_meta( $post_id, '_wp_page_template', 'page-bet.php' );
	}

	/**
	 * Create bet by data
	 *
	 * @param $title string - Bet title
	 * @param $description string - Bet description
	 * @param $type string - Bet type
	 *
	 * @return bool
	 */
	public function create_bet( $title, $description, $type ) {
		if ( ! empty( $title ) && ! empty( $description ) && ! empty( $type ) ) {
			$new_post_id = wp_insert_post( [
				'post_title'     => $title,
				'post_type'      => BETS_POST_TYPE,
				'post_name'      => sanitize_title( $title ),
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
				'post_content'   => $description,
				'post_status'    => 'publish',
				'post_author'    => get_user_by( 'id', 1 )->user_id
			] );

			if ( $new_post_id && ! is_wp_error( $new_post_id ) ) {
				$this->set_post_template( $new_post_id );
				wp_set_post_terms( $new_post_id, $type, BETS_POST_TAXONOMY_TYPE, true );

				return true;
			}
		}

		return false;
	}

	/**
	 * Make a bet
	 *
	 * @param $post_id int - Post ID
	 * @param $bet string - Bet
	 *
	 * @return bool
	 */
	public function make_bet( $post_id, $bet ) {
		if ( ! empty( $post_id ) && ! empty( $bet ) ) {
			update_post_meta( $post_id, '_bet_vote', $bet );

			$name       = 'bet_cookie_' . $post_id;
			$is_secure  = is_ssl() && 'https' === parse_url( get_option( 'home' ), PHP_URL_SCHEME );
			$expiration = time() + DAY_IN_SECONDS;

			$result = setcookie( $name, 1, $expiration, COOKIEPATH, COOKIE_DOMAIN, $is_secure, true );
			if ( COOKIEPATH != SITECOOKIEPATH ) {
				$site_result = setcookie( $name, 1, $expiration, SITECOOKIEPATH, COOKIE_DOMAIN, $is_secure, true );

				return $result || $site_result;
			}

			return true;
		}

		return false;
	}
}
