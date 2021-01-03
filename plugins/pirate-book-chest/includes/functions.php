<?php

namespace tw2113\pbc;

function get_total_to_read_pages( $books = [] ) {

	if ( empty ( $books ) ) {
		return '0';
	}

	global $wpdb;

	$book_ids = str_replace( "''", "'", implode( "','", $books->posts ) );
	$book_ids = "'{$book_ids}'";

	$rs = $wpdb->get_results(
		"SELECT sum( meta_value ) as pages from wp_postmeta where meta_key = 'pbc_total_pages' and post_id in ( {$book_ids} )"
	);

	if ( empty( $rs ) ) {
		return '0';
	}

	return $rs[0]->pages;
}

function the_total_to_read_pages( $books = [] ) {
	echo get_total_to_read_pages( $books );
}

function the_longest_book() {
	global $wpdb;
	$rs = $wpdb->get_results(
		"SELECT post_id, meta_value FROM wp_postmeta WHERE meta_key = 'pbc_total_pages' ORDER BY CAST( meta_value as int) DESC LIMIT 1"
	);
	if ( ! empty( $rs ) ) {
		return $rs;
	}

	return 'n/a';
}

function the_shortest_book() {
	global $wpdb;
	$rs = $wpdb->get_results(
		"SELECT post_id, meta_value FROM wp_postmeta WHERE meta_key = 'pbc_total_pages' ORDER BY CAST( meta_value as int) ASC LIMIT 1"
	);
	if ( ! empty( $rs ) ) {
		return $rs;
	}

	return 'n/a';
}

function get_reading_challenge_years() {
	$args = [
		'post_type' => 'book-challenges',
		'posts_per_page' => -1,
	];

	$challenges = new \WP_Query( $args );
	$content = '';
	if ( $challenges->have_posts() ) : while ( $challenges->have_posts() ) : $challenges->the_post();
	ob_start();
	?>
	<h2><a href="<?php echo esc_attr( get_the_permalink() ); ?>"><?php the_title(); ?></a></h2>
	<?php
	$content .= ob_get_clean();
	endwhile;
	wp_reset_postdata();
	endif;

	return $content;
}
add_shortcode( 'read-challenges', __NAMESPACE__ . '\get_reading_challenge_years' );