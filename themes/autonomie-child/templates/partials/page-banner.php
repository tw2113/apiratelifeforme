<?php if ( autonomie_show_page_banner() ) : ?>
<div class="page-banner">
	<?php if ( ! is_singular() ) : ?>
	<div class="page-branding">
		<?php if ( autonomie_get_the_archive_title() ) { ?>
		<h1 id="page-title"<?php autonomie_semantics( 'page-title' ); ?>><?php echo autonomie_get_the_archive_title(); ?></h1>
		<?php } ?>

		<?php if ( autonomie_get_the_archive_description() ) { ?>
		<div id="page-description"<?php autonomie_semantics( 'page-description' ); ?>><?php echo autonomie_get_the_archive_description(); ?>
        <?php if ( is_post_type_archive() ) {
            printf(
                '<p class="post-type-feed"><img src="%s" alt="Atom feed icon" /> <a href="%s">Atom feed</a></p>',
                esc_url( includes_url( 'images/rss.png' ) ),
                get_post_type_archive_feed_link( get_post_type(), 'atom' )
            );
        }
        ?>
        </div>
		<?php } ?>
	</div>
	<?php endif; ?>
</div>
<?php endif; ?>
