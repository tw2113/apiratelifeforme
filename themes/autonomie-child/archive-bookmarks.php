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

                <li><strong><?php the_title(); ?></strong> <?php the_content(); echo get_the_term_list( get_the_ID(), 'bookmark_topics', 'Topics: ', ', ' ); ?></li>

            <?php endwhile; // end of the loop. ?>
            </ul>
        </div>
    </article>
</main><!-- #content -->

<?php get_footer(); ?>
