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
            <?php while ( have_posts() ) : the_post(); ?>
            <article <?php autonomie_post_id(); ?> <?php post_class(); ?><?php autonomie_semantics( 'post' ); ?>>
                <h1><?php the_title(); ?></h1>

                <?php the_content(); ?>

                <a href="<?php echo get_post_type_archive_link( 'music_video' ); ?>">Random video</a>

            <?php endwhile; // end of the loop. ?>
            </article>
        </div>
    </article>
</main><!-- #content -->

<?php get_footer(); ?>
