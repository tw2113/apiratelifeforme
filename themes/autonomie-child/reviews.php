<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to autonomie_comment() which is
 * located in the functions.php file.
 *
 * @package Autonomie
 * @since Autonomie 1.0.0
 */
?>

<div id="comments">

    <?php
    if ( is_user_logged_in() ) {
		comment_form(
            [
                'format'            => 'html5',
                'title_reply'       => 'Leave a review',
                'label_submit'      => 'Submit review',
                'cancel_reply_link' => 'Cancel review',
                'logged_in_as'      => '',
            ]
        );
	}
    ?>

</div>