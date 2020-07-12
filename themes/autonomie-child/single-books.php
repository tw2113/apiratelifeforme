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

get_header(); ?>

<main id="primary">
    <article>
        <div class="entry-content e-content h-entry" itemprop="description articleBody">
            <?php
            while ( have_posts() ) : the_post(); ?>
                <h2><?php the_title(); ?></h2>
                <h3>Book description:</h3>
                <?php
                the_content();

                $meta = get_post_meta( get_the_ID() );
                if ( ! empty( $meta ) && is_array( $meta ) ) {
                    foreach ( $meta as $key => $data ) {
                        if ( ! in_array( $key, [ '_edit_lock', '_edit_last' ], true ) ) {
                            $key = str_replace( 'pbc_', '', $key );
							echo '<p>' . $key . ': ' . $data[0] . '</p>';
                        }
                    }
                }
            endwhile;
            ?>
        </div>
    </article>
</main><!-- #content -->

<?php autonomie_content_nav( 'nav-below' ); ?>

<?php get_footer(); ?>
