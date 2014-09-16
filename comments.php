<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to twentytwelve_comment() which is
 * located in the functions.php file.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() )
    return;

if( is_singular() ) wp_enqueue_script( 'comment-reply' );
?>

<div id="comments" class="comments-area">

    <?php // You can start editing here -- including this comment! ?>

    <?php if ( have_comments() ) : ?>
        <h2 class="comments-title">
            <?php
            printf( _n( 'One comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', get_comments_number(), 'twentytwelve' ),
                number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
            ?>
        </h2>

        <div class="commentlist">
            <?php wp_list_comments( array( 'callback' => 'gd_comment_template', 'style' => 'ul' ) ); ?>
        </div><!-- .commentlist -->

        <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
            <nav id="comment-nav-below" class="navigation" role="navigation">
                <h1 class="assistive-text section-heading"><?php _e( 'Comment navigation', 'twentytwelve' ); ?></h1>
                <div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'twentytwelve' ) ); ?></div>
                <div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'twentytwelve' ) ); ?></div>
            </nav>
        <?php endif; // check for comment navigation ?>

        <?php
        /* If there are no comments and comments are closed, let's leave a note.
         * But we only want the note on posts and pages that had comments in the first place.
         */
        if ( ! comments_open() && get_comments_number() ) : ?>
            <p class="nocomments"><?php _e( 'Comments are closed.' , 'twentytwelve' ); ?></p>
        <?php endif; ?>

    <?php else: // have_comments() ?>
        <h2 class="comments-title">Leave a comment</h2>
    <?php endif; ?>
    <?php
    comment_form(
        array(
            'title_reply' => '',
            'title_reply_to' => __( 'Replying to %s', 'gd_text_domain' ),
            'cancel_reply_link' => __( 'Cancel Reply' ),
            'comment_field' => '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="4" aria-required="true" placeholder="Type in a comment..."></textarea></p>',
            'logged_in_as' => '',
            'comment_notes_before' => '',
            'comment_notes_after' => ''
        )
    );
    ?>
    <?php wp_nonce_field('delete_gd_comment', 'gd_nonce'); ?>
</div><!-- #comments .comments-area -->