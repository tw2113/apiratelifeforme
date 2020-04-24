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
        <div class="entry-content e-content" itemprop="description articleBody">
            <ul class="bookmarks">
            <?php while ( have_posts() ) : the_post(); ?>

                <li class="h-entry"><strong><?php the_title(); ?></strong> <?php the_content(); echo get_the_term_list( get_the_ID(), 'bookmark_topics', 'Topics: ', ', ' ); ?></li>

            <?php endwhile; // end of the loop. ?>
            </ul>

            <?php
            $topics = get_terms(
                [
                    'taxonomy' => 'bookmark_topics',
                ]
            );
            ?>

            <h2>Individual topics</h2>
            <?php
            if ( ! empty( $topics ) && ! is_wp_error( $topics ) ) {
                echo '<ul class="bookmark-topics">';
                foreach ( $topics as $topic ) {
                    printf(
                        '<li><a href="%s">%s</a> - <img src="%s" alt="Atom feed icon" /> <a href="%s">%s</a></li>',
                        get_term_link( $topic ),
                        $topic->name,
						esc_url( includes_url( 'images/rss.png' ) ),
						get_term_feed_link( $topic->term_id, 'bookmark_topics', 'atom' ),
                        'Atom feed'
                    );
                }
                echo '</ul>';
            }
            ?>
        </div>
    </article>
</main><!-- #content -->

<?php autonomie_content_nav( 'nav-below' ); ?>

<?php get_footer(); ?>
