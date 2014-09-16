<?php
// Great Diseases config
include_once( 'gd-admin-settings.php' );

/**
 * 1. Get the current user's team id
 * 2. Get the category (i.e. progress point) that the post was submitted from
 * 3. Set this post as the "designated" post for the retrieved progress point
 */
function gd_check_progress_point( $sp_post ){

    // get the current user's team id
    $team_id = 0;
    if( class_exists( 'CTXPS_Queries' ) ){
        $groups = CTXPS_Queries::get_groups( get_current_user_id() );
        $current_group = new stdClass();
        if( count( $groups ) > 0 ){
            $current_group = $groups[0];
        }
        $team_id = $current_group->ID;
    }

    // get the category that the post was submitted from
    $post = $sp_post->get_wp_post();
    $post_cats = get_the_category( $post->ID );
    $post_cat = $post_cats[0];

    // compare post cat with the progress point cat id
    $gd_progress_pts = get_option( 'gd-progress-pts' );
    $progress_pt_id = 0; // if this ends up being > 0, then they've made progress
    if( is_array( $gd_progress_pts ) && !empty( $gd_progress_pts ) ){
        foreach( $gd_progress_pts as $pt_key => $pt_label ){
            $cat_obj = get_term_by( 'name', $pt_label, 'category' );
            if( $cat_obj->term_id == $post_cat->term_id ){
                $progress_pt_id = $pt_key;
                break;
            }
        }
    }

    // if they've submitted a progress point, set the post as the progress pt "post"
    if( $progress_pt_id > 0 && $team_id > 0 ){
        $team_progress_option_id =  'gd-team-' . $team_id . '-progress';
        $team_progress = get_option( $team_progress_option_id );
        /**
         * Team progress array format
         * array(
         *   [progress pt id] => [post object]
         * )
         */
        $team_progress[ $progress_pt_id ] = $post->ID;
        update_option( $team_progress_option_id, $team_progress );
    }
}
add_action( 'sp_after_new_sp_post', 'gd_check_progress_point', 10, 1 );

/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function gd_add_set_team_page_meta_box() {
    add_meta_box(
        'gd_set_team_page',
        __( 'Set Great Diseases Team Page', 'gd_text_domain' ),
        'gd_render_set_team_page_meta_box',
        'page',
        'side',
        'core'
    );
}
add_action( 'add_meta_boxes', 'gd_add_set_team_page_meta_box' );

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function gd_render_set_team_page_meta_box( $post ) {

    // Add an nonce field so we can check for it later.
    wp_nonce_field( 'gd_set_team_page_meta_box', 'gd_set_team_page_meta_box_nonce' );

    /*
     * Use get_post_meta() to retrieve an existing value
     * from the database and use the value for the form.
     */
    $value = get_option( 'gd_team_page_id' );

    if( $value == $post->ID ){
        echo '<p>' . _e( 'This page (ID: <b>' . $post->ID . '</b>) has been set as the Great Diseases team page and will be referenced across the site as such.', 'gd_text_domain') . '</p>';
    }else{
        echo '<label for="gd_set_team_page_button">';
        _e( 'Set this page as the Great Diseases team page. The ID of this page will be referenced across the site.', 'gd_text_domain' );
        echo '</label>';
        echo '<p><button type="submit" class="button" id="gd_set_team_page_button" name="gd_set_team_page_button" value="true">Set as Great Diseases Team Page</button></p>';
    }
}


/**
 * When the post is saved, saves our gd team page id.
 *
 * @param int $post_id The ID of the post being saved.
 */
function myplugin_save_meta_box_data( $post_id ) {

    /*
     * We need to verify this came from our screen and with proper authorization,
     * because the save_post action can be triggered at other times.
     */

    // Check if our nonce is set.
    if ( ! isset( $_POST['gd_set_team_page_meta_box_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['gd_set_team_page_meta_box_nonce'], 'gd_set_team_page_meta_box' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

    } else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    /* OK, it's safe for us to save the data now. */

    // Make sure that it is set.
    if ( ! isset( $_POST['gd_set_team_page_button'] ) ) {
        return;
    }

    // Update the meta field in the database.
    $set_gd_team_page = $_POST['gd_set_team_page_button'];
    if( $set_gd_team_page == 'true' ){
        update_option( 'gd_team_page_id', $post_id );
    }


}
add_action( 'save_post', 'myplugin_save_meta_box_data' );

/**
 * Sets the team roles when the site is loaded. If the option deosn't exist,
 * then the hardcoded values below are used to update the option
 */
function gd_set_team_roles(){
    $roles = get_option( 'gd-team-roles' );

    if( $roles === false ){
        // Hard-coded role IDs...
        $roles = array(
            1 => 'Animal Biologist',
            2 => 'Clinical Research Associate',
            3 => 'Clinical Trial Nurse',
            4 => 'Regulatory Specialist',
            5 => 'Research Specialist',
            6 => 'Research Scientist',
            7 => 'Toxicologist'
        );
        update_option( 'gd-team-roles', $roles );
    }
}
gd_set_team_roles();

/**
 * Displays the various team roles in the user profile page
 */
function gd_team_roles_fields( $user ){
    if( current_user_can( 'manage_options' ) ){
        $user_role_id = get_user_meta( $user->ID, 'gd-team-role', true);
        $roles = get_option( 'gd-team-roles' );
    ?>
    <h3>Great Disease Team Role</h3>
    <table>
        <tr>
            <th>Select a team role for this user:</th>
            <td>
                <select id="gd-team-role" name="gd-team-role">
                    <option value="0">Select Role...</option>
                    <?php
                        if( is_array( $roles ) && !empty( $roles) ){
                            foreach( $roles as $role_id => $role_label ){
                                if( $user_role_id == $role_id ){
                                    $selected = 'selected';
                                }else{
                                    $selected = '';
                                }

                                echo '<option value="' . $role_id . '" ' . $selected . '>' . $role_label . '</option>';
                            }
                        }
                    ?>
                </select>
            </td>
        </tr>
    </table>
    <?php
    }
}

add_action( 'show_user_profile', 'gd_team_roles_fields' );
add_action( 'edit_user_profile', 'gd_team_roles_fields' );

/**
 * Saves a role to the user meta of the currently displayed user
 */
function save_gd_team_roles( $user_id ){
    if ( !current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }

    $role_id = (int) $_POST['gd-team-role'];

    if( $role_id == 0 ){
        delete_user_meta( $user_id, 'gd-team-role' );
    }

    if( isset( $role_id ) ){
        update_user_meta( $user_id, 'gd-team-role', $role_id );
    }

    return $user_id;
}

add_action( 'personal_options_update', 'save_gd_team_roles' );
add_action( 'edit_user_profile_update', 'save_gd_team_roles' );

// Theme Functions

/**
 * Template for comments.
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function gd_comment_template( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <article id="comment-<?php comment_ID(); ?>" class="comment">
            <header class="comment-meta comment-author vcard">
            <?php
                echo get_avatar( $comment, 25 ) . ' ';
                printf( '%1$s', get_comment_author_link() );
            ?>
            </header><!-- .comment-meta -->

            <?php if ( '0' == $comment->comment_approved ) : ?>
                <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentytwelve' ); ?></p>
            <?php endif; ?>

            <section class="comment-content comment">
                <?php comment_text(); ?>
            </section><!-- .comment-content -->

            <div class="reply">
                <?php
                comment_reply_link( array_merge(
                    $args,
                    array(
                        'reply_text' => __( '<small>Reply to ' . $comment->comment_author, 'twentytwelve' ),
                        'after' => ' | <span class="delete-comment" data-commentid="' . $comment->comment_ID . '">Delete Comment</span></small>',
                        'depth' => $depth,
                        'max_depth' => $args['max_depth']
                        )
                    )
                ); ?>
            </div><!-- .reply -->
        </article><!-- #comment-## -->
<?php
}

if ( function_exists('register_sidebar') ){
    register_sidebar(
        array('name'=>'leftsidebar_student_home',
            'description' => __( 'Appears on all posts and pages except', 'gd-text-domain' ),
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h3>',
            'after_title' => '</h3>',
        )
    );
}

register_nav_menus(
    array(
        'primary'=>__('Primary Menu'),
        'secondary'=>__('Secondary Menu'),
        'student_main'=>__('Student Main'),
    )
);

// This theme users wp_nav_menu() in one location for the menu.
if ( function_exists( 'register_nav_menus' ) ) {
	register_nav_menus(
		array(
		  'primary' => 'Primary',
		)
	);
}

/**
 * AJAX functions handling posting, editing, and deleting comments
 */
add_action('wp_ajax_delete_gd_comment', 'delete_gd_comment');

/**
 * Delete a comment
 */
function delete_gd_comment(){
    $nonce = $_POST['gd_nonce'];
    if( !wp_verify_nonce($nonce, 'delete_gd_comment' ) ){
        header("HTTP/1.0 409 Security Check.");
        exit;
    }

    if( empty($_POST['commentID'])){
        header("HTTP/1.0 409 Could not locate comment ID.");
        exit;
    }

    $commentID = (int) $_POST['commentID'];
    $comment   = get_comment($commentID);

    if(is_null($comment)){
        header("HTTP/1.0 409 Could not load comment.");
        exit;
    }

    $success = wp_delete_comment($commentID);

    if($success === false){
        header("HTTP/1.0 409 Could not delete comment.");
        exit;
    }

    echo json_encode( array('success' => true, 'details' => 'Comment successfully deleted', 'commentID' => $commentID) );
    exit;
}

/**
 * Add a menu item to the menu that displays user avatar + login/logout links. Floats menu item to the right.
 * @param $items
 * @return string
 */
function login_menu_item( $items ){
    global $current_user;
    if( is_user_logged_in() ){
        $login_item = '<li id="menu-item-login" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-login sp-menu-not-sortable">';
        $login_item .= '<a href="' . get_author_posts_url($current_user->ID) . '">';
        $login_item .=  get_avatar($current_user->ID, 16) . ' Welcome ' . $current_user->display_name . '!';
        $login_item .= '</a>';

        $login_item .= '<ul class="sub-menu">';

        $login_item .= '<li id="menu-item-logout" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-logout sp-menu-not-sortable">';
        $login_item .= '<a href="' . get_author_posts_url($current_user->ID) . '">';
        $login_item .= 'My Profile';
        $login_item .= '</a>';
        $login_item .= '</li>';

        $login_item .= '<li id="menu-item-logout" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-logout sp-menu-not-sortable">';
        $login_item .= '<a href="' . wp_logout_url() . '&redirect_to=' . home_url() .'">';
        $login_item .= 'Logout';
        $login_item .= '</a>';
        $login_item .= '</li>';

        if( current_user_can( 'edit_dashboard' ) ){
            $login_item .= '<li id="menu-item-logout" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-logout sp-menu-not-sortable">';
            $login_item .= '<a href="' . admin_url() . '" target="_new">';
            $login_item .= 'Dashboard';
            $login_item .= '</a>';
            $login_item .= '</li>';
        }

        $login_item .= '</ul>';
        $login_item .= '</li>';

    }else{
        $login_item = '<li id="menu-item-login" class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-home menu-item-login">';
        $login_item .= '<a href="' . wp_login_url() . '?redirect_to=' . site_url() .'">';
        $login_item .=  'Login';
        $login_item .= '</a>';
        $login_item .= '</li>';
    }

    return $items . $login_item;
}
add_filter( 'wp_nav_menu_items', 'login_menu_item' );