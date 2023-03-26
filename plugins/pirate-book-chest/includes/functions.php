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

function get_reading_challenge_status() {

    $current_challenge = new \WP_Query(
        [
            'post_type' => 'book-challenges',
            'post_status' => 'publish',
            'posts_per_page' => 1
        ]
    );

    if ( ! $current_challenge->have_posts() ) {
        return;
    }

    //current read
    $total_goal = $current_challenge->posts[0]->pbc_total_goal;
    $total_read = (string) '0';
    if ( ! empty( $current_challenge->posts[0]->pbc_read_books ) && is_array( $current_challenge->posts[0]->pbc_read_books ) ) {
	    $total_read = count( $current_challenge->posts[0]->pbc_read_books );
    }

    // how many days per book allowed.
	$days_per_book = 365 / $total_goal;

    // current day of the year. Jan 22nd = 22nd day. Feb 28th = 59th day, etc.
	$date        = new \DateTime();
	$day_of_year = abs( $date->format( 'z' ) ) + 1;

    // How many books I should have by this point?
	$should_have = $day_of_year / $days_per_book;

	$difference = abs( $total_read - ceil( $should_have ) );
	if ( $total_read == ceil( $should_have ) ) {
		return 'on schedule';
	} else if ( $total_read < ceil( $should_have ) ) {
		return "{$difference} behind schedule";
	} else if ( $total_read > ceil( $should_have ) ) {
		return "{$difference} ahead of schedule";
	}

	return '';
}