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
            <?php
            if ( empty( $_GET ) ) {
                while ( have_posts() ) : the_post(); ?>
                <article <?php autonomie_post_id(); ?> <?php post_class(); ?><?php autonomie_semantics( 'post' ); ?>>
                    <h1><?php the_title(); ?></h1>

                    <?php the_content(); ?>

                    <p><a href="<?php echo esc_url( get_post_type_archive_link( 'music_video' ) ); ?>">Random video</a></p>

                    <p><a href="<?php echo esc_url( add_query_arg( ['list' => 'all'], get_post_type_archive_link( 'music_video' ) ) ); ?>">List of all available</a></p>
                </article>
                <?php endwhile;
            }
            if ( ! empty( $_GET ) && 'all' === $_GET['list'] ) {
                echo apply_filters( 'the_content', 'https://open.spotify.com/playlist/4hEEKBrynPtxMpm3TAX2ld?si=xPSxV0keQu2XzQDzBRcywg' );
                ?>
                <p><a href="spotify:playlist:4hEEKBrynPtxMpm3TAX2ld">Open in spotify for complete list</a></p>
                <ul>
                <?php
                while ( have_posts() ) : the_post(); ?>
                <li <?php autonomie_post_id(); ?> <?php post_class(); ?><?php autonomie_semantics( 'post' ); ?>>
                    <?php
					printf(
                        '<a href="%s">%s</a>',
                        get_the_permalink(),
                        get_the_title()
                    );
                    ?>
                </li>
                <?php
                endwhile; ?>
                </ul>
            <?php
			}
            ?>
        </div>
    </article>
</main><!-- #content -->

<?php get_footer(); ?>
