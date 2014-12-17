<?php
// Great Diseases config
include_once( 'gd-admin-settings.php' );

//Our class extends the WP_List_Table class, so we need to make sure that it's there
include_once( 'gd-admin-progress-pts-table.php' );

/**
 * Checks if the current step is a "locked" step. If it is, it will check if
 * the current team has a submission for the step and display only the choice that the
 * team has made.
 */
function gd_check_for_locked_step( $choices, $step_id ){
    $curr_team_id = gd_get_current_users_team_id();
    $curr_team_progress = get_option('gd-team-' . $curr_team_id . '-progress' );

    $is_locked = get_post_meta($step_id, '_gd_is_step_locked', true );
    $is_locked = $is_locked == 'true' ? true : false;

    // If the step is locked and the team has made a decision for this step, the filter the choices
    if( $is_locked && isset( $curr_team_progress['decisions'][$step_id] ) ){
        foreach( $choices as $choice_key => $choice ){
            $choice_goto_id = $choice['choice_goto_id'];
            if( $choice_goto_id == $curr_team_progress['decisions'][$step_id]->choice_goto_id ){
                // override choices with the choice made
                $choices_new = array( array('choice_title' => $choice['choice_title'], 'choice_goto_id' => $choice['choice_goto_id']) );
                break;
            }
        }
    }else{
        $choices_new = $choices;
    }

    return $choices_new;
}

add_filter('gd_step_choices', 'gd_check_for_locked_step', 10, 2);

/**
 * Records the decision of each step
 */

function gd_record_decision(){
    $nonce = $_POST[ 'gd_nonce' ];
    if( !wp_verify_nonce( $nonce, 'gd_record_decision' ) ){
        header("HTTP/1.0 409 Security Check.");
        exit;
    }

    if( empty( $_POST['stepID'] ) ){
        header("HTTP/1.0 409 Could not locate post ID.");
        exit;
    }

    if( !isset( $_POST['choiceID'] ) ){
        header("HTTP/1.0 409 Could not locate choice ID.");
        exit;
    }

    $progress_pt_id = (int) $_POST['stepID'];
    $choice_id = (int) $_POST['choiceID'];

    $team_id = gd_get_current_users_team_id();
    if( $team_id > 0  && is_user_logged_in() ){
        $team_progress_option_id =  'gd-team-' . $team_id . '-progress';
        $team_progress = get_option( $team_progress_option_id );

        // record the team decision
        // @todo: how to handle multiple users? Right now it'll work with just one user
        if( !isset( $team_progress['decisions'] ) ){
            $team_progress['decisions'] = array();
        }

        // Get the available choices
        $choices = get_post_meta( $progress_pt_id, '_gd_progress_pt_choices', true );

        $decision = new stdClass();
        $decision->user_id = get_current_user_id();
        $decision->step_id = $progress_pt_id;
        $decision->choice_made = $choices[ $choice_id ]['choice_title'];
        $decision->choice_goto_id = $choices[ $choice_id ]['choice_goto_id'];
        $decision->step_title = get_the_title( $progress_pt_id );

        $team_progress['decisions'][$progress_pt_id] = $decision;
        update_option( $team_progress_option_id, $team_progress );

        echo json_encode( array( 'decision' => $decision, 'url' => get_permalink( $choices[$choice_id]['choice_goto_id'] ) ) );
    }
    exit;
}
add_action( 'wp_ajax_gd_record_decision', 'gd_record_decision' );

/**
 * Clears a team's progress - the gd-team-<#>-progress option
 */
function gd_clear_team_progress(){
    $team_id = gd_get_current_users_team_id();
    if( $team_id > 0 ){
        get_option( 'gd-team-' . $team_id . '-progress' );
    }

    $nonce = $_POST[ 'gd_nonce' ];
    if( !wp_verify_nonce( $nonce, 'gd_clear_team_progress' ) ){
        header("HTTP/1.0 409 Security Check.");
        exit;
    }
    $team_progress = get_option( 'gd-team-' . $team_id . '-progress' );
    if( !empty( $team_progress['decisions'] ) ){

    }

    $success = update_option( 'gd-team-' . $team_id . '-progress', array() );
    echo json_encode( array( 'success' => $success ) );
    exit;
}
add_action( 'wp_ajax_gd_clear_team_progress', 'gd_clear_team_progress' );

/**
 * Gets the current user's team id
 * @return int
 */
function gd_get_current_users_team_id(){
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
    return $team_id;
}

/**
 * 1. Get the current user's team id
 * 2. Get the step/progress point that the post was submitted from
 * 3. Update step post_meta to show:
 *    - The team ID of the user's team
 *    - The user ID that has submitted the content
 *    - The post ID that was submitted
 * 4. Update the team progress option
 */
function gd_check_progress_point( $sp_post_id ){

    // get the origin object id of the submitted smartpost and see if it's a progress point
    $post = get_post( $sp_post_id );
    $origin_obj_id = get_post_meta( $post->ID, 'sp_origin_id', true );

    $gd_progress_pts = get_option( 'gd_progress_pts' );

    if( ( $progress_pt_key = array_search( $origin_obj_id, $gd_progress_pts ) ) !== false ){

        $progress_pt_id = $gd_progress_pts[ $progress_pt_key ];

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

        // if they've submitted a progress point, set the post as the progress pt "post"
        if( $progress_pt_id > 0 && $team_id > 0 ){
            // Set team progress pt
            $team_progress_option_id =  'gd-team-' . $team_id . '-progress';
            $team_progress = get_option( $team_progress_option_id );
            $team_progress[ $progress_pt_id ] = $post->ID;
            update_option( $team_progress_option_id, $team_progress );
        }
    }
}
add_action( 'sp_qp_widget_ajax_new_draft', 'gd_check_progress_point', 10, 1 );

/**
 * Renders team submissions for the progress points
 * @param $team_id
 */
function gd_render_team_submissions( $team_id ){

    $team_progress = get_option( 'gd-team-' . $team_id . '-progress' );
    $team_submissions = array();
    if( !empty( $team_progress ) ){
        foreach( $team_progress as $step_id => $step_submmission ){
            if( $step_id !== 'decisions' ){
                array_push( $team_submissions, $step_submmission );
            }
        }
    }

    global $wp_query;
    $wp_query->is_singular = false;
    $args = array(
        'post__in' =>  $team_submissions,
        'posts_per_page' => -1,
        'paged' => get_query_var( 'paged' ),
        'order' => 'DESC',
        'post_type' => 'post'
    );
    $team_query = new WP_Query( $args );
    // The Loop
    if ( $team_query->have_posts() ) {
        echo '<ul>';
        while ( $team_query->have_posts() ) {
            $team_query->the_post();
            $step_page_id = get_post_meta( get_the_ID(), 'sp_origin_id', true );
            ?>
            <div id="post-<?php the_ID() ?>" class="author-post">

                <!-- post meta -->
                <h3><a href="<?php echo get_the_permalink( $step_page_id ); ?>"><?php the_title(); ?></a>
                    <br />
                    <small style="font-weight: normal;">Submitted in <b><?php echo get_the_title( $step_page_id); ?></b> by <?php the_author_posts_link(); ?> on <?php the_date(); ?>
                        <?php the_tags( '&nbsp;' . __( '| Tagged:&nbsp;' ) . ' ', ', ', ''); ?>
                        <?php if( class_exists('smartpost') ){ ?>
                            |
                            <?php
                            if( sp_post::is_sp_post( get_the_ID() ) && current_user_can( 'edit_post', get_the_ID() ) ){
                                // Check if the permalink structure is with slashes, or the default structure with /?p=123
                                $permalink_url = get_permalink( get_the_ID() );
                                $link_txt = "Edit";
                                if( strpos( $permalink_url, '?')  ){
                                    $permalink_url .= '&edit_mode=true';
                                }else{
                                    $permalink_url .= '?edit_mode=true';
                                }
                                ?>
                                <span class="editlink"><a href="<?php echo $permalink_url ?>"><?php echo $link_txt ?></a></span>
                            <?php
                            }else{
                                edit_post_link(__('Edit'),'<span class="editlink">','</span>');
                            }
                            ?>
                        <?php }else{ ?>
                            <?php edit_post_link(__('Edit'),'<span class="editlink">','</span>'); ?>
                        <?php } ?>
                    </small>
                </h3>

                <!-- article content -->
                <div class="content">
                    <div id="post-thumb-<?php the_ID() ?>" class="post-thumb">
                        <a href="<?php the_permalink() ?>">
                            <?php the_post_thumbnail( array(100, 100) ); ?>
                        </a>
                    </div>
                    <?php the_excerpt(); ?>
                </div>
                <div class="clear"></div>
            </div><!-- end post-<?php the_ID() ?>-->
        <?php
        }
        echo '</ul>';
    }else{
        ?>
        <br />
        <p>To begin, click on the first step in the progress tracker above!</p>
    <?php
    }
    // Restore original Post Data
    $wp_query->is_singular = true;
    wp_reset_postdata();
}

/**
 * Renders the progress tracker of the current users's team
 */
function gd_render_progress_tracker(){
    $team_id = gd_get_current_users_team_id();
    if( $team_id > 0 ){
        global $wpdb;
        $gd_progress_pts = get_option( 'gd_progress_pts' );
        $gd_query_args = array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'post__in' => $gd_progress_pts,
            'order' => 'ASC',
            'posts_per_page' => 1,
            'orderby' => 'meta_value_num',
            'meta_key' => '_gd_step_order'
        );

        $gd_query = new WP_Query( $gd_query_args );
        $gd_starting_pt = $wpdb->get_results( $gd_query->request );
        wp_reset_query();

        $team_progress = get_option( 'gd-team-' . $team_id . '-progress' );     // Get progress the teams have made
        $gd_progress_tracker_steps = get_option( 'gd_progress_tracker_steps' ); // Get progress bar steps

        // Render the progress tracker
        if( !empty( $gd_progress_tracker_steps ) ){
            $step_html = '';

            $counter = 0;
            foreach( $gd_progress_tracker_steps as $step_id => $step_data ){

                $completed_steps = array(); // collect steps completed
                $keys   = array_keys( $step_data['required_steps'] ); // Get required step IDs
                $result = isset( $team_progress['decisions'][ $keys[0] ] );      // Get the first result

                if( $result ){
                    array_push( $completed_steps, $keys[0] );
                }

                if( count( $keys ) > 1 ){
                    foreach( $keys as $k => $step_id ){

                        if( $k + 1 == count($keys) ){ // If we've reached the last step, don't test logic
                            break;
                        }

                        $logic = $step_data['required_steps'][ $keys[$k] ];

                        // Figure out if we've completed the required steps to consider this progress step "completed"
                        switch( $logic ){
                            case 'or':
                                $result = $result || isset( $team_progress['decisions'][ $keys[$k + 1] ] );
                                break;
                            case 'and':
                                $result = $result && isset( $team_progress['decisions'][ $keys[$k + 1] ] );
                                break;
                        }

                        if( isset( $team_progress['decisions'][ $keys[$k + 1] ] ) ){
                            array_push( $completed_steps, $keys[$k + 1] );
                        }
                    }
                }

                // If result is true (we've completed the step), then mark it as done!
                if( $result ){
                    $progress_class = 'progress-point done';
                }else{
                    $progress_class = 'progress-point todo';
                }

                // If it's the first step, bind it to the first step in the decision tree
                if( $counter == 0 ){
                    $completed_steps = array( $gd_starting_pt[0]->ID );
                }

                $step_html .= '<li class="' . $progress_class . '">';
                if( count( $completed_steps ) > 1 ){
                    $step_html .= '<a href="' . get_the_permalink( $completed_steps[ count($completed_steps) - 1] ) . '">' . $step_data['step_text'] . '</a>';
                }else if( count( $completed_steps ) == 1) {
                    $step_html .= '<a href="' . get_the_permalink( $completed_steps[0] ) . '">' . $step_data['step_text'] . '</a>';
                }else{
                    $step_html .= '<a href="#">' . $step_data['step_text'] . '</a>';
                }
                $step_html .= '</li>';
                $counter++;
            }

            echo '<ol class="progress-meter">';
            echo $step_html;
            echo '</ol>';
        }
    }
}

/**
 * Cleans the team progress option of cancelled posts
 * @param $post_id
 */
function gd_check_deleted_posts( $post_id ){

    $post = get_post( $post_id );
    $origin_obj_id = get_post_meta( $post->ID, 'sp_origin_id', true );
    $gd_progress_pts = get_option( 'gd_progress_pts' );

    if( ( $progress_pt_key = array_search( $origin_obj_id, $gd_progress_pts ) ) !== false ){

        $progress_pt_id = $gd_progress_pts[ $progress_pt_key ];
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

        $team_progress_option_id =  'gd-team-' . $team_id . '-progress';
        $team_progress = get_option( $team_progress_option_id );

        if( isset( $team_progress[$progress_pt_id] ) ){
            // remove the submission post id for the progress point
            unset( $team_progress[ $progress_pt_id ] );
        }

        if( isset( $team_progress['decisions'][$progress_pt_id]) ){
            // remove the submission post id for the progress point
            unset( $team_progress['decisions'][$progress_pt_id] );
        }

        update_option( $team_progress_option_id, $team_progress );
    }
}
add_action( 'sp_qp_widget_before_after_post', 'gd_check_deleted_posts', 10, 1);
add_action( 'before_delete_post', 'gd_check_deleted_posts', 10, 1 );

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
 * Used for
 */
function gd_display_teacher_groups( $user ){
    if( current_user_can( 'manage_options' ) ){
        $user_type = get_user_meta( $user->ID, 'rpr_user_type', true );
        $teacher_groups = get_user_meta( $user->ID, 'gd_teacher_groups', true );
        if( $user_type === 'Teacher' ){
        ?>
        <h3>Great Disease Teacher Groups</h3>
        <p>Select groups that belong to <?php echo $user->display_name ?>:</p>
        <?php
            if( class_exists( 'CTXPS_Queries' ) ){
               $groups = CTXPS_Queries::get_groups();
               foreach( $groups as $group ){
                   if( $group->ID > 1 ){
                       $checked = in_array( $group->ID, $teacher_groups ) ? 'checked' : '';
                       echo '<input type="checkbox" id="gd-group[]" name="gd-group[]" ' . $checked . ' value="' . $group->ID . '">';
                       echo $group->group_title;
                       echo '<br />';
                   }
               }
            }
        ?>
    <?php
        }// end user type check
    }
}

add_action( 'show_user_profile', 'gd_display_teacher_groups' );
add_action( 'edit_user_profile', 'gd_display_teacher_groups' );

/**
 * Saves groups to teacher users
 */
function save_gd_teacher_groups( $user_id ){
    if ( !current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }

    $selected_groups = $_POST['gd-group'];

    if( empty( $selected_groups ) ){
        update_user_meta( $user_id, 'gd_teacher_groups', array() );
    }else{
        update_user_meta( $user_id, 'gd_teacher_groups', $selected_groups );
    }

    return $user_id;
}

add_action( 'personal_options_update', 'save_gd_teacher_groups' );
add_action( 'edit_user_profile_update', 'save_gd_teacher_groups' );

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