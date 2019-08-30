<?php

function autonomie_enqueue_scripts() {}

/**
 * Prints HTML with meta information for the current post-date/time and author.
 * Create your own autonomie_posted_on to override in a child theme
 *
 * @since Autonomie 1.0.0
 */
//function autonomie_posted_on() {
//    $maybe_updated = tw2113\updated_date();
//
//    $entry_date_classes = [
//        'entry-date',
//        'published',
//        'dt-published',
//    ];
//    $entry_date_updated = '';
//    $item_prop = 'datePublished';
//    if ( '' === $maybe_updated ) {
//        $entry_date_classes[] = 'updated';
//        $entry_date_classes[] = 'dt-updated';
//        $item_prop .= ' dateUpdated';
//    } else {
//        $entry_date_updated = $maybe_updated;
//        $item_prop_updated = 'dateUpdated';
//    }
//    // translators: the author byline
//    printf( __( '<address class="byline"><span class="author p-author vcard hcard h-card" itemprop="author" itemscope itemtype="http://schema.org/Person">%5$s <a class="url uid u-url u-uid fn p-name" href="%6$s" title="%7$s" rel="author" itemprop="url"><span itemprop="name">%8$s</span></a></span></address> <span class="sep"> | </span> <a href="%1$s" title="%2$s" rel="bookmark" class="url u-url"><time class="%9$s" datetime="%3$s" itemprop="%10$s">%4$s</time>%11$s</a>', 'autonomie' ),
//        esc_url( get_permalink() ),
//        esc_attr( get_the_time() ),
//        esc_attr( get_the_date( 'c' ) ),
//        esc_html( get_the_date() ),
//        get_avatar( get_the_author_meta( 'ID' ), 40 ),
//        esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
//        // translators:
//        esc_attr( sprintf( __( 'View all posts by %s', 'autonomie' ), get_the_author() ) ),
//        esc_html( get_the_author() ),
//        esc_attr( implode( ' ', $entry_date_classes ) ),
//        esc_attr( $item_prop ),
//        $entry_date_updated
//    );
//}

