<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Autonomie
 * @since Autonomie 1.0.0
 */

use tw2113\pbc as pbc;

get_header(); ?>

		<main id="primary">

			<?php while ( have_posts() ) : the_post(); ?>

                <article <?php autonomie_post_id(); ?> <?php post_class(); ?><?php autonomie_semantics( 'post' ); ?>>
					<?php get_template_part( 'templates/partials/entry-header' ); ?>
                        <div class="entry-content e-content" itemprop="description articleBody" style="max-width:700px;">

							<?php
							$currently_reading_books = new WP_Query(
								[
									'post_type'      => 'books',
									'posts_per_page' => -1,
									'post_status'    => 'publish',
									'fields'         => 'ids',
									'tax_query'      => [
										[
											'taxonomy' => 'book_status',
											'field'    => 'slug',
											'terms'    => 'currently-reading',
										]
									]
								]
							);

							$read_books = new WP_Query(
								[
									'post_type'      => 'books',
									'posts_per_page' => -1,
									'post_status'    => 'publish',
									'fields'         => 'ids',
									'tax_query'      => [
										[
											'taxonomy' => 'book_status',
											'field'    => 'slug',
											'terms'    => 'read',
										]
									]
								]
							);

							$to_read_books = new WP_Query(
								[
									'post_type'      => 'books',
									'posts_per_page' => -1,
									'post_status'    => 'publish',
									'fields'         => 'ids',
									'tax_query'      => [
										[
											'taxonomy' => 'book_status',
											'field'    => 'slug',
											'terms'    => 'to-read',
										]
									]
								]
							);

							?>
                            <h2>Total books</h2>
                            <p><strong>Currently reading:</strong> <?php echo number_format( $currently_reading_books->found_posts ); ?></p>
                            <p><strong>To read:</strong> <?php echo number_format( $to_read_books->found_posts ); ?></p>
                            <p><strong>Already read:</strong> <?php echo number_format( $read_books->found_posts ); ?></p>

                            <?php
                            $shortest = pbc\the_shortest_book();
                            ?>
                            <p><strong>Shortest book in chest:</strong> <?php printf( '<a href="%s">%s</a> with %s pages', esc_attr( get_the_permalink( $shortest[0]->post_id ) ), get_the_title( $shortest[0]->post_id ), number_format( $shortest[0]->meta_value ) ); ?></p>

							<?php
							$longest = pbc\the_longest_book();
							?>
                            <p><strong>Longest book in chest:</strong> <?php printf( '<a href="%s">%s</a> with %s pages', esc_attr( get_the_permalink( $longest[0]->post_id ) ), get_the_title( $longest[0]->post_id ), number_format( $longest[0]->meta_value ) ); ?></p>

                            <?php
							$total_currently_reading = pbc\get_total_to_read_pages( $currently_reading_books );
							$total_to_read           = pbc\get_total_to_read_pages( $to_read_books );
							$total_read              = pbc\get_total_to_read_pages( $read_books );
							$total_cumulative        = $total_currently_reading + $total_to_read + $total_read;
                            ?>
                            <h2>Page statistics</h2>
                            <p><strong>Total pages currently reading:</strong> <?php echo number_format( $total_currently_reading ); ?></p>
                            <p><strong>Total pages to read:</strong> <?php echo number_format( $total_to_read ); ?></p>
                            <p><strong>Total pages already read:</strong> <?php echo number_format( $total_read ); ?></p>
                            <p><strong>Total cumulative pages:</strong> <?php echo number_format( $total_cumulative ); ?></p>

                        </div><!-- .entry-content -->

					<?php get_template_part( 'templates/partials/entry-footer' ); ?>
                </article><!-- #post-<?php the_ID(); ?> -->

			<?php endwhile; // end of the loop. ?>

		</main><!-- #content -->

<?php get_footer(); ?>
