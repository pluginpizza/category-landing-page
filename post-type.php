<?php
/**
 * Contains functionality for the category landing page post type
 *
 * @package PluginPizza\CategoryLandingPage
 */

namespace PluginPizza\CategoryLandingPage\PostType;

// Register custom post type.
add_action( 'init', __NAMESPACE__ . '\register_category_landing_page_post_type' );

// Filter the custom post type updated messages.
add_filter( 'post_updated_messages', __NAMESPACE__ . '\post_updated_messages' );

// Filter the category landing page edit link.
add_filter( 'get_edit_post_link', __NAMESPACE__ . '\edit_post_link', 10, 3 );

/**
 * Register the category landing page post type.
 *
 * @return void
 */
function register_category_landing_page_post_type() {

	$labels = array(
		'add_new'                  => esc_html__( 'Add New Landing Page', 'category-landing-page' ),
		'add_new_item'             => esc_html__( 'Add New Landing Page', 'category-landing-page' ),
		'all_items'                => esc_html__( 'All Landing Pages', 'category-landing-page' ),
		'archives'                 => esc_html__( 'Landing Page Archives', 'category-landing-page' ),
		'attributes'               => esc_html__( 'Landing Page Attributes', 'category-landing-page' ),
		'edit_item'                => esc_html__( 'Edit Landing Page', 'category-landing-page' ),
		'featured_image'           => esc_html__( 'Featured image', 'category-landing-page' ),
		'filter_items_list'        => esc_html__( 'Filter landing page list', 'category-landing-page' ),
		'insert_into_item'         => esc_html__( 'Insert into content', 'category-landing-page' ),
		'items_list_navigation'    => esc_html__( 'Landing page list navigation', 'category-landing-page' ),
		'items_list'               => esc_html__( 'Landing page list', 'category-landing-page' ),
		'item_published'           => esc_html__( 'Landing page published.', 'category-landing-page' ),
		'item_published_privately' => esc_html__( 'Landing page published privately.', 'category-landing-page' ),
		'item_reverted_to_draft'   => esc_html__( 'Landing page reverted to draft.', 'category-landing-page' ),
		'item_scheduled'           => esc_html__( 'Landing page scheduled.', 'category-landing-page' ),
		'item_updated'             => esc_html__( 'Landing page updated.', 'category-landing-page' ),
		'menu_name'                => esc_html__( 'Landing Pages', 'category-landing-page' ),
		'name'                     => esc_html__( 'Landing Page', 'category-landing-page' ),
		'new_item'                 => esc_html__( 'New Landing Page', 'category-landing-page' ),
		'not_found'                => esc_html__( 'No landing pages found', 'category-landing-page' ),
		'not_found_in_trash'       => esc_html__( 'No landing pages found in the trash', 'category-landing-page' ),
		'parent_item_colon'        => '',
		'remove_featured_image'    => esc_html__( 'Remove featured image', 'category-landing-page' ),
		'search_items'             => esc_html__( 'Search Landing Pages', 'category-landing-page' ),
		'set_featured_image'       => esc_html__( 'Set featured landing page image', 'category-landing-page' ),
		'singular_name'            => esc_html__( 'Landing Page', 'category-landing-page' ),
		'uploaded_to_this_item'    => esc_html__( 'Uploaded to this landing page', 'category-landing-page' ),
		'use_featured_image'       => esc_html__( 'Use as featured image', 'category-landing-page' ),
		'view_item'                => esc_html__( 'View Landing Page', 'category-landing-page' ),
		'view_items'               => esc_html__( 'View Landing Pages', 'category-landing-page' ),
	);

	$args = array(
		/*
		 * Adding the `edit_link` key so that we can lean on the WordPress core delete post
		 * functionality. Note that we're not adding an `action` key, WordPress will do that
		 * for us. Also note that we're adding a filter to `get_edit_post_link` to then override
		 * the edit link where we need it, eg. in the list view. A bit of a hop, skip and jump.
		 */
		'_edit_link'          => '/post.php?post=%d',
		'can_export'          => true,
		'capability_type'     => 'post',
		'delete_with_user'    => false,
		'exclude_from_search' => false,
		'has_archive'         => false,
		'hierarchical'        => false,
		'labels'              => $labels,
		'map_meta_cap'        => true,
		'menu_icon'           => 'dashicons-admin-page',
		'menu_position'       => 80,
		'public'              => false,
		'publicly_queryable'  => false,
		'query_var'           => 'category-landing-page',
		'rest_base'           => 'category-landing-page',
		'rewrite'             => false,
		'show_in_admin_bar'   => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_rest'        => true,
		'show_ui'             => true,
		'supports'            => array(
			'title',
			'editor',
			'custom-fields',
		),
	);

	register_post_type( 'categorylandingpage', $args );
}

/**
 * Filter display messages.
 *
 * @param array $messages Post updated messages. For defaults @see wp-admin/edit-form-advanced.php.
 * @return array $messages Modified post updated messages.
 */
function post_updated_messages( $messages ) {

	global $post;

	$messages['autocompleter'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => esc_html__( 'Landing page updated.', 'category-landing-page' ),
		2  => esc_html__( 'Custom field updated.', 'category-landing-page' ),
		3  => esc_html__( 'Custom field deleted.', 'category-landing-page' ),
		4  => esc_html__( 'Landing page updated.', 'category-landing-page' ),
		/* translators: %s: previous revision */
		5  => isset( $_GET['revision'] ) ? sprintf( esc_html__( 'Landing Page restored to revision from %s', 'category-landing-page' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		6  => esc_html__( 'Landing page created.', 'category-landing-page' ),
		7  => esc_html__( 'Landing page saved.', 'category-landing-page' ),
		8  => esc_html__( 'Landing page submitted.', 'category-landing-page' ),
		/* translators: %s: Landing Page scheduled publication date */
		9  => sprintf( esc_html__( 'Landing page scheduled for: <strong>%1$s</strong>.', 'category-landing-page' ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => esc_html__( 'Landing page draft updated.', 'category-landing-page' ),
	);

	return $messages;
}

/**
 * Filter the category landing page edit link.
 *
 * @param string $link    The edit link.
 * @param int    $post_id Post ID.
 * @param string $context The link context. If set to 'display' then ampersands
 *                        are encoded.
 * @return string The autocompleter edit link.
 */
function edit_post_link( $link, $post_id, $context ) {

	$post = get_post( $post_id );

	if ( ! is_a( $post, '\WP_Post' ) ) {
		return $link;
	}

	if ( 'autocompleter' !== $post->post_type ) {
		return $link;
	}

	$link = add_query_arg(
		array(
			'action' => 'edit',
			'post'   => (int) $post->ID,
		),
		PLUGINPIZZA_AUTOCOMPLETER_TOOLKIT_SETTINGS_URL
	);

	if ( 'display' === $context ) {
		$link = str_replace( '&', '&amp;', $link );
	}

	return $link;
}

