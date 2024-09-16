<?php
/**
 * Author URI:        https://plugin.pizza/
 * Author:            Plugin Pizza
 * Description:       Custom landing pages for category archives.
 * Domain Path:       /languages
 * License:           GPLv3+
 * Plugin Name:       Category Landing Page
 * Plugin URI:        https://github.com/pluginpizza/category-landing-page/
 * Text Domain:       category-landing-page
 * Version:           1.0.0
 * Requires PHP:      5.3.0
 * Requires at least: 4.6.0
 * GitHub Plugin URI: pluginpizza/category-landing-page
 *
 * @package PluginPizza\CategoryLandingPage
 */

namespace PluginPizza\CategoryLandingPage;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Maybe load a category landing page template.
add_filter( 'template_include', __NAMESPACE__ . '\maybe_load_category_landing_page', 999 );

/**
 * Maybe load a category landing page template.
 *
 * @param string $template The path of the template to include.
 * @return string
 */
function maybe_load_category_landing_page( $template ) {

	if ( ! is_category() && ! is_tag() && ! is_tax() ) {
		return $template;
	}

	$queried_object = get_queried_object();

	if ( ! is_a( $queried_object, '\WP_Term' ) ) {
		return $template;
	}

	$args = array(
		'post_type'      => 'categorylandingpage',
		'post_status'    => 'publish', // @todo Add private etc.
		'posts_per_page' => 1,
		'meta_key'       => 'category-landing-page',
		'meta_value'     => absint( $queried_object->term_id ),
	);

	$query = new \WP_Query( $args );

	if ( $query->have_posts() ) {

		$query->the_post();

		global $wp_query;

		$wp_query->post        = $query->post;
		$wp_query->posts       = array( $query->post );
		$wp_query->post_count  = 1;
		$wp_query->is_singular = true;

		// $wp_query->is_page = true;
		// $wp_query->is_archive = false;
		// $wp_query->is_tag = false;
		// $wp_query->is_tax = false;
		// $wp_query->set( 'posts', array( $landing_page ) );
		// $wp_query->set( 'queried_object', $landing_page );
		// $wp_query->set( 'queried_object_id', $landing_page->ID );
		// $wp_query->set( 'is_category', false );
		// $wp_query->set( 'is_tax', false );
		// $wp_query->set( 'is_tag', false );
		// $wp_query->set( 'is_page', true );
		// $wp_query->set( 'tax_query', [] );

		// wp_die( '<pre>' . var_export( $wp_query, true ) . '</pre>' ); // phpcs:ignore

		// Set a global variable to access the page title in the title filter.
		global $custom_page_title;

		$custom_page_title = get_the_title();

		// Reset post data
		wp_reset_postdata();

		return get_page_template();
	}

	return $template;
}

add_filter( 'wp_title_parts', __NAMESPACE__ . '\maybe_update_title' );

/**
 * Update the page title if we're loading a category landing page.
 *
 * @param string[] $title_array Array of parts of the page title.
 * @return array
 */
function maybe_update_title( $title_array ) {

	global $custom_page_title;

	if ( ! empty( $custom_page_title ) ) {
		$title_array['title'] = $custom_page_title;
	}

	return $title_array;
}
