<?php
/**
 * Atom Feed Template for displaying Atom Posts feed.
 *
 * @package WordPress
 */

global $wpdb;

$per_page = get_option( 'posts_per_rss', 10 );

$sql = "SELECT * FROM `{$wpdb->prefix}lastfm_tracks` ORDER BY `date_listened` DESC LIMIT %d";
$tracks = $wpdb->get_results( $wpdb->prepare( $sql, $per_page) );

header( 'Content-Type: ' . feed_content_type( 'atom' ) . '; charset=' . get_option( 'blog_charset' ), true );
$more = 1;



echo '<?xml version="1.0" encoding="' . get_option( 'blog_charset' ) . '"?' . '>';

/** This action is documented in wp-includes/feed-rss2.php */
do_action( 'rss_tag_pre', 'atom' );
?>
<feed
        xmlns="http://www.w3.org/2005/Atom"
        xmlns:thr="http://purl.org/syndication/thread/1.0"
        xml:lang="<?php bloginfo_rss( 'language' ); ?>"
        xml:base="<?php bloginfo_rss( 'url' ); ?>/wp-atom.php"
    <?php
    /**
     * Fires at end of the Atom feed root to add namespaces.
     *
     * @since 2.0.0
     */
    do_action( 'atom_ns' );

    ?>
>
    <title type="text">Michael's Last.FM Listening data.</title>
    <subtitle type="text">Tracks listened to by Michael</subtitle>

    <updated><?php echo get_feed_build_date( 'Y-m-d\TH:i:s\Z' ); ?></updated>

    <link rel="alternate" type="<?php bloginfo_rss( 'html_type' ); ?>" href="<?php bloginfo_rss( 'url' ); ?>" />
    <id><?php bloginfo( 'atom_url' ); ?></id>
    <link rel="self" type="application/atom+xml" href="<?php self_link(); ?>" />

    <?php
    /**
     * Fires just before the first Atom feed entry.
     *
     * @since 2.0.0
     */
    do_action( 'atom_head' );

    foreach( $tracks as $track ) {
    ?>
    <entry>
        <author>
            <name>Michael Beckwith</name>
            <?php

            /**
             * Fires at the end of each Atom feed author entry.
             *
             * @since 3.2.0
             */
            do_action('atom_author');
            ?>
        </author>
        <title type="<?php html_type_rss(); ?>"><![CDATA[<?php echo $track->track_artist . ' - ' .  $track->track_name . ' ' . $track->track_album; ?>]]></title>
        <link rel="alternate" type="<?php bloginfo_rss( 'html_type' ); ?>" href="<?php bloginfo( 'home' ); ?>" />
        <id><?php bloginfo( 'home' ); ?>/<?php echo $track->id; ?></id>
        <published><?php echo date('Y-m-d\TH:i:s\Z', strtotime($track->date_listened)); ?></published>
        <updated><?php echo date('Y-m-d\TH:i:s\Z', strtotime($track->date_listened)); ?></updated>
        <?php
        atom_enclosure();
        /**
         * Fires at the end of each Atom feed item.
         *
         * @since 2.0.0
         */
        do_action('atom_entry');
        ?>
    </entry>
    <?php } ?>
</feed>