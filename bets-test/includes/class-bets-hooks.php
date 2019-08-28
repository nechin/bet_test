<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hooks class
 *
 * @author Alexander Vitkalov <nechin.va@gmail.com>
 * @copyright (c) 28.08.2019, Vitkalov
 * @version 1.0
 */
class Bets_Hooks{
	/**
	 * Bets_Hook constructor.
	 */
	public function __construct() {
	}

	/**
	 * Init action
	 */
	public function wp_init() {
		$this->register_taxonomies();
		$this->register_post_types();
		$this->register_terms();
		$this->register_new_roles();
	}

	/**
	 * Load plugin textdomain
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'bets', false, BETS_PLUGIN_BASE . '/languages' );
	}

	/**
	 * Load mu plugin textdomain
	 */
	public function load_mu_plugin_textdomain() {
		load_muplugin_textdomain( 'bets', BETS_PLUGIN_BASE . '/languages' );
	}

	/**
	 * Register new taxonomies
	 */
	public function register_taxonomies() {
		// Add taxonomy bet type
		register_taxonomy( BETS_POST_TAXONOMY_TYPE, BETS_POST_TYPE, [
			'hierarchical' => false,
			'labels'       => [
				'name'                       => __( 'Bet type', 'bets' ),
				'singular_name'              => __( 'Bet type', 'bets' ),
				'search_items'               => __( 'Search Bet types', 'bets' ),
				'popular_items'              => __( 'Popular Bet types', 'bets' ),
				'all_items'                  => __( 'All Bet types', 'bets' ),
				'edit_item'                  => __( 'Edit Bet type', 'bets' ),
				'update_item'                => __( 'Update Bet type', 'bets' ),
				'add_new_item'               => __( 'Add New Bet type', 'bets' ),
				'new_item_name'              => __( 'New Bet type', 'bets' ),
				'separate_items_with_commas' => __( 'Separate Bet types with commas', 'bets' ),
				'add_or_remove_items'        => __( 'Add or remove Bet types', 'bets' ),
				'choose_from_most_used'      => __( 'Choose from the most used Bet types', 'bets' ),
				'menu_name'                  => __( 'Bet types', 'bets' ),
			],
			'public'       => true,
			'query_var'    => true,
			'rewrite'      => array( 'slug' => 'bet_type' ),
			'capabilities' => array(
				'manage_terms' => 'manage_bet_type',
				'edit_terms'   => 'edit_bet_type',
				'delete_terms' => 'delete_bet_type',
				'assign_terms' => 'assign_bet_type',
			)
		] );

		// Add taxonomy bets status
		register_taxonomy( BETS_POST_TAXONOMY_STATUS, BETS_POST_TYPE, [
			'hierarchical' => false,
			'labels'       => [
				'name'                       => __( 'Bet status', 'bets' ),
				'singular_name'              => __( 'Bet status', 'bets' ),
				'search_items'               => __( 'Search Bet statuses' ),
				'popular_items'              => __( 'Popular Bet statuses' ),
				'all_items'                  => __( 'All Bet statuses' ),
				'edit_item'                  => __( 'Edit Bet status' ),
				'update_item'                => __( 'Update Bet status' ),
				'add_new_item'               => __( 'Add New Bet status' ),
				'new_item_name'              => __( 'New Bet status' ),
				'separate_items_with_commas' => __( 'Separate Bet statuses with commas' ),
				'add_or_remove_items'        => __( 'Add or remove Bet statuses' ),
				'choose_from_most_used'      => __( 'Choose from the most used Bet statuses' ),
				'menu_name'                  => __( 'Bet statuses' ),
			],
			'public'       => true,
			'query_var'    => true,
			'rewrite'      => array( 'slug' => 'bet_status' ),
			'capabilities' => array(
				'manage_terms' => 'manage_bet_status',
				'edit_terms'   => 'edit_bet_status',
				'delete_terms' => 'delete_bet_status',
				'assign_terms' => 'assign_bet_status',
			)
		] );
	}

	/**
	 * Register new post type "Bets"
	 */
	public function register_post_types() {
		register_post_type( BETS_POST_TYPE, [
			'label'           => __( 'Bets', 'bets' ),
			'labels'          => [
				'name'               => __( 'Bets', 'bets' ),
				'singular_name'      => __( 'Bet', 'bets' ),
				'add_new'            => __( 'Add Bet', 'bets' ),
				'add_new_item'       => __( 'Add Bet', 'bets' ),
				'edit_item'          => __( 'Edit Bet', 'bets' ),
				'new_item'           => __( 'New Bet', 'bets' ),
				'view_item'          => __( 'View Bet', 'bets' ),
				'search_items'       => __( 'Search Bet', 'bets' ),
				'not_found'          => __( 'Not found', 'bets' ),
				'not_found_in_trash' => __( 'Not found in trash', 'bets' ),
				'parent_item_colon'  => '',
				'menu_name'          => __( 'Bets', 'bets' ),
			],
			'description'     => __( 'Bets', 'bets' ),
			'public'          => true,
			'map_meta_cap'    => true,
			'capability_type' => [ 'bet', 'bets' ],
		] );
	}

	/**
	 * Register new terms
	 */
	public function register_terms() {
		foreach ( [ 'ordinar', 'express' ] as $term ) {
			$is_term = term_exists( $term, BETS_POST_TAXONOMY_TYPE );
			if ( empty( $is_term ) ) {
				wp_insert_term( $term, BETS_POST_TAXONOMY_TYPE, [
					'description' => __( ucfirst( $term ), 'bets' ),
					'slug'        => $term,
				] );
			}
		}

		foreach ( [ 'win', 'losing', 'return', 'active' ] as $term ) {
			$is_term = term_exists( $term, BETS_POST_TAXONOMY_STATUS );
			if ( empty( $is_term ) ) {
				wp_insert_term( $term, BETS_POST_TAXONOMY_STATUS, [
					'description' => __( ucfirst( $term ), 'bets' ),
					'slug'        => $term,
				] );
			}
		}
	}

	/**
	 * Register new roles: capper and moderator
	 */
	public function register_new_roles() {
		if ( ! get_option( 'bets_roles_added' ) ) {
			global $wp_roles;

			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}

			remove_role( 'partner' );
			remove_role( BETS_POST_ROLE_CAPPER );
			remove_role( BETS_POST_ROLE_MODERATOR );

			add_role( BETS_POST_ROLE_CAPPER, __( 'Capper', 'bets' ), [
				'read'                => true,
				'read_bet'            => true,
				'edit_bet'            => true,
				'edit_published_bets' => true,
				'edit_private_bets'   => true,
				'edit_bets'           => true,
				'create_bets'         => true,
				'publish_bets'        => true,
				'assign_bet_type'       => true,
				'assign_bet_status'     => true,
			] );

			add_role( BETS_POST_ROLE_MODERATOR, __( 'Moderator', 'bets' ), [
				'read'                  => true,
				'read_bet'              => true,
				'edit_bet'              => true,
				'edit_bets'             => true,
				'edit_others_bets'      => true,
				'edit_published_bets'   => true,
				'delete_bet'            => true,
				'delete_bets'           => true,
				'delete_others_bets'    => true,
				'delete_published_bets' => true,
				'create_bets'           => true,
				'publish_bets'          => true,
				'assign_bet_type'       => true,
				'assign_bet_status'     => true,
			] );

			$role = get_role( 'administrator' );
			$role->add_cap( 'read_bet' );
			$role->add_cap( 'read_private_bets' );
			$role->add_cap( 'edit_bet' );
			$role->add_cap( 'edit_bets' );
			$role->add_cap( 'edit_others_bets' );
			$role->add_cap( 'edit_private_bets' );
			$role->add_cap( 'edit_published_bets' );
			$role->add_cap( 'delete_bet' );
			$role->add_cap( 'delete_bets' );
			$role->add_cap( 'delete_private_bets' );
			$role->add_cap( 'delete_published_bets' );
			$role->add_cap( 'delete_others_bets' );
			$role->add_cap( 'create_bets' );
			$role->add_cap( 'publish_bets' );
			$role->add_cap( 'manage_bet_type' );
			$role->add_cap( 'edit_bet_type' );
			$role->add_cap( 'delete_bet_type' );
			$role->add_cap( 'assign_bet_type' );
			$role->add_cap( 'manage_bet_status' );
			$role->add_cap( 'edit_bet_status' );
			$role->add_cap( 'delete_bet_status' );
			$role->add_cap( 'assign_bet_status' );

			add_option( 'bets_roles_added', true );
		}
	}

	/**
	 * Save post action
	 *
	 * @param $post_ID
	 * @param $post
	 * @param $update
	 */
	public function save_post( $post_ID, $post, $update ) {
		if ( $update ) {
			bets()->service->set_post_template( $post_ID );
		}
	}
}
