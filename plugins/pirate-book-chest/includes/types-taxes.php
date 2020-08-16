<?php

namespace tw2113\pbc;

function register_my_cpts() {

	$labels = [
		"name" => __( "Books", "pirate-book-chest" ),
		"singular_name" => __( "Book", "pirate-book-chest" ),
		"featured_image" => __( 'Book Image', 'pirate-book-chest' ),
		"view_items" => __( 'View Books', 'pirate-book-chest' ),
	];

	$args = [
		"label" => __("Books", "pirate-book-chest"),
		"labels" => $labels,
		"description" => "Michael's recreation of GoodReads, on his own personal site. Note: archive and single books are still in style flux.",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"has_archive" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => ["slug" => "books", "with_front" => true],
		"query_var" => true,
		"menu_icon" => "dashicons-book",
		"supports" => ["title", "editor", "thumbnail", "comments"],
	];

	register_post_type( "books", $args );
}
add_action( 'init', __NAMESPACE__ . '\register_my_cpts' );

function register_my_books_taxes() {

	$labels = [
		"name"          => __( "Status", "pirate-book-chest" ),
		"singular_name" => __( "Status", "pirate-book-chest" ),
	];

	$args = [
		"label" => __( "Status", "pirate-book-chest" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => false,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'book-status', 'with_front' => true, ],
		"show_admin_column" => true,
		"show_in_rest" => true,
		"rest_base" => "book_status",
		"show_in_quick_edit" => true,
	];
	register_taxonomy( "book_status", [ "books" ], $args );

	$labels = [
		"name"          => __( "Book Chests", "pirate-book-chest" ),
		"singular_name" => __( "Book Chest", "pirate-book-chest" ),
	];

	$args = [
		"label" => __( "Book Chest", "pirate-book-chest" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'book-chest', 'with_front' => true, ],
		"show_admin_column" => true,
		"show_in_rest" => true,
		"rest_base" => "book_chest",
		"show_in_quick_edit" => true,
	];
	register_taxonomy( "book_chest", [ "books" ], $args );

	$labels = [
		"name"          => __( "Genre", "pirate-book-chest" ),
		"singular_name" => __( "Genre", "pirate-book-chest" ),
	];

	$args = [
		"label" => __( "Genre", "pirate-book-chest" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'genre', 'with_front' => true, ],
		"show_admin_column" => true,
		"show_in_rest" => true,
		"rest_base" => "genre",
		"show_in_quick_edit" => true,
	];
	register_taxonomy( "genre", [ "books" ], $args );

	$labels = [
		"name"          => __( "Genre Tags", "pirate-book-chest" ),
		"singular_name" => __( "Genre Tags", "pirate-book-chest" ),
	];

	$args = [
		"label" => __( "Genre Tags", "pirate-book-chest" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => false,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'genre-tag', 'with_front' => true, ],
		"show_admin_column" => true,
		"show_in_rest" => true,
		"rest_base" => "genre-tag",
		"show_in_quick_edit" => true,
	];
	register_taxonomy( "genre-tag", [ "books" ], $args );

	$labels = [
		"name"          => __( "Book Series", "pirate-book-chest" ),
		"singular_name" => __( "Book Series", "pirate-book-chest" ),
	];

	$args = [
		"label" => __( "Book Series", "pirate-book-chest" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'book-series', 'with_front' => true, ],
		"show_admin_column" => true,
		"show_in_rest" => true,
		"rest_base" => "book-series",
		"show_in_quick_edit" => true,
	];
	register_taxonomy( "book-series", [ "books" ], $args );
}
add_action( 'init', __NAMESPACE__ . '\register_my_books_taxes' );
