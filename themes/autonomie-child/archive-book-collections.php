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

            <div>
            <?php
            while ( have_posts() ) : the_post(); ?>
                <div class="individual-book">
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	                <?php edit_post_link( 'Edit collection', '<span class="edit-book">', '</span>', null, 'btn btn-primary btn-edit-post-link' ); ?>
                </div>
                <?php
            endwhile;
            ?>
            </div>
        </div>
    </article>
</main><!-- #content -->

<?php autonomie_content_nav( 'nav-below' ); ?>

<?php get_footer(); ?>
