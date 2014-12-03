<?php
/**
 * Template Name: Student Team Template
 */
?>
<?php get_header(); ?>

<div class="content-sidebar-wrapper">

    <?php get_sidebar(); ?>
    <div id="content-wrapper">
        <div id="breadcrumb">
            <?php if(class_exists('bcn_breadcrumb_trail'))
            {
                //Make new breadcrumb object
                $breadcrumb_trail = new bcn_breadcrumb_trail;
                //Setup our options
                //Set the home_title to Blog
                $breadcrumb_trail->opt['home_title'] = "Home";
                $breadcrumb_trail->opt['separator'] = "&nbsp;&nbsp;&raquo;&nbsp;&nbsp;";
                //Set the current item to be surrounded by a span element, start with the prefix
                $breadcrumb_trail->opt['current_item_prefix'] = '';
                //Set the suffix to close the span tag
                $breadcrumb_trail->opt['current_item_suffix'] = '';
                //Fill the breadcrumb trail
                $breadcrumb_trail->fill();
                //Display the trail
                $breadcrumb_trail->display();
            }
            ?>
        </div>

        <div id="breadcrumbdivider">&nbsp;</div>

        <div id="post-content">
            <?php
            if( is_user_logged_in() ){
                if( class_exists( 'CTXPS_Queries') ){

                    if( isset( $_GET['team_id'] ) ){
                        // get team id from $_GET query
                        $team_id = (int) $_GET['team_id'];

                        // check if user belongs to group if not administrator
                        if( !current_user_can( 'manage_options' ) ){
                            $is_member = CTXPS_Queries::check_membership( get_current_user_id(), $team_id );
                        }else{
                            $current_group = CTXPS_Queries::get_group_info( $team_id );
                            $is_member = true;
                        }
                    }else{
                        $groups = CTXPS_Queries::get_groups( get_current_user_id() );
                        $current_group = new stdClass();
                        if( count( $groups ) > 0 ){
                            $current_group = $groups[0];
                        }
                        $team_id = $current_group->ID;
                        $is_member = true;
                    }
                ?>
                <?php if( $is_member ){ ?>
                    <!-- team header -->
                    <div id="team-<?php echo $team_id; ?>" class="student-team-header">
                        <h1><?php echo $current_group->group_title ?></h1>
                        <p><?php echo $current_group->group_description ?></p>
                    </div>

                    <!-- team members -->
                    <div id="team-<?php echo $team_id ?>-members" class="team-members">
                        <h2>Team Members:</h2>
                        <?php
                        $members = CTXPS_Queries::get_group_members( $team_id );
                        $team_members_ids = array(); // used below in a WP Query
                        if( !empty( $members ) ){
                            $roles = get_option( 'gd-team-roles' );

                            foreach( $members as $member ){

                                array_push( $team_members_ids, $member->ID );

                                $user_info = get_userdata( $member->ID );

                                $name = get_the_author_meta('display_name', $member->ID);

                                // Display team role
                                $user_role = (int) get_user_meta( $member->ID, 'gd-team-role', true);
                                ?>
                                <div id="member-<?php echo $member->ID ?>" class="team-member">
                                    <a href="<?php echo get_author_posts_url( $member->ID ); ?>">
                                        <?php echo get_avatar( $member->ID, '110' ); ?>
                                        <p><?php echo $name; ?></p>
                                    </a>
                                    <?php
                                    if( is_array( $roles ) && isset( $roles[$user_role] ) ){
                                        echo '<p><b>Role:</b> ' . $roles[$user_role] . '</p>';
                                        $user_role = 0;
                                    }else{
                                        echo '<p><b>Role:</b> unassigned</p>';
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                        }else{
                        ?>
                            <h1>This team has no team members!</h1>
                        <?php
                        }
                        ?>
                        <div style="clear: both;"></div>
                    </div>

                    <!-- team progress -->
                    <div id="team-<?php echo $team_id ?>-progress" class="team-progress">
                        <h2>Team Progress:</h2>
                        <div class="clear"></div>
                        <?php
                        // Get progress the teams have made
                        $team_progress = get_option( 'gd-team-' . $team_id . '-progress' );

                        $steps = array(
                            'Pick a disease',
                            'Select a drug compound',
                            'Day 3: Screening Drug Efficacy & Toxicity',
                            'Day 4: Phase 1 Clinical Trial',
                            'Day 5: Phases 2/3 Clinical Trials',
                            'FDA Report',
                            'Career Debrief'
                        );

                        /*
                        // check if the team has made a Cholera or TB decision, which will filter the query
                        $team_progress = get_option( 'gd-team-' . $team_id . '-progress' );

                        // Run a query to get the right ordering of progress points
                        $progress_pts = get_option( 'gd_progress_pts' );
                        $gd_query_args = array(
                            'post_type' => 'page',
                            'post_status' => 'publish',
                            'post__in' => $progress_pts,
                            'posts_per_page' => -1,
                            'orderby' => 'meta_value_num',
                            'meta_key' => '_gd_step_order',
                            'order' => 'ASC'
                        );
                        $gd_query = new WP_Query( $gd_query_args );

                        // @todo: create "start" or "pivot" steps
                        // Check for cholera or TB decisions based on the "Pick a disease" step
                        $disease_decision = '';
                        if( !empty( $team_progress['decisions'] ) ){
                            foreach( $team_progress['decisions'] as $decision_id => $decision ){
                                if( strpos( sanitize_title( $decision->step_title ), 'pick-a-disease' ) !== false ){
                                    $disease_decision = $decision->choice_made;
                                    break;
                                }
                            }
                        }


                        if ( $gd_query->have_posts() ) {
                            echo '<ol class="progress-meter">';
                            while ( $gd_query->have_posts() ) {
                                $gd_query->the_post();
                                $progress_pt_page = get_post( get_the_ID() );
                                $progress_class = 'progress-point todo';

                                // If the team progressed thru the point, mark it as done
                                if( isset( $team_progress[ get_the_ID() ] ) ){
                                    $submission_post_id = $team_progress[ get_the_ID() ];
                                    $progress_post = get_post( $submission_post_id );

                                    if( $progress_post->post_status == 'publish' ){
                                        $progress_class = 'progress-point done';
                                    }
                                }


                                $step_tag = get_post_meta( get_the_ID(), '_gd_step_metadata', true);

                                // determines based off "Cholera" or "TB" decision in "Pick a disease" step
                                // whether or not to show steps tagged as "TB" or "Cholera"
                                $show_step = true;
                                if( !empty( $step_tag) ){
                                    if( $step_tag !== $disease_decision ){
                                        $show_step = false;
                                    }
                                }

                                if( is_object( $progress_pt_page ) && $show_step ){
                                    $step_html = '<li class="' . $progress_class . '">';
                                        $step_html .= '<a href="' . get_permalink( $progress_pt_page->ID )  .'">' . $progress_pt_page->post_title  . '</a>';
                                    $step_html .= '</li>';

                                    // Apply some filters before we echo the step
                                    $step_html = apply_filters( 'gd_progress_tracker_step_html_pre_render', $step_html, $progress_pt_page, $team_id );

                                    echo $step_html;
                                }
                            }
                            echo '</ol>';
                        }
                        wp_reset_postdata();
                        */
                        ?>
                        <div class="clear"></div>
                    </div>

                    <!-- team submissions -->
                    <div id="team-<?php echo $team_id ?>-submission" class="team-submissions">
                        <h2>Team Submissions:</h2>
                        <?php
                        global $wp_query;
                        $wp_query->is_singular = false;
                        $args = array(
                            'author__in' => $team_members_ids,
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
                                ?>
                                <div id="post-<?php the_ID() ?>" class="author-post">

                                    <!-- post meta -->
                                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        <br />
                                        <small style="font-weight:normal;">Posted on <?php the_date(); ?> by <?php the_author_posts_link(); ?> | Categories: <?php the_category(', '); ?>
                                            <?php the_tags( '&nbsp;' . __( '| Tagged:&nbsp;' ) . ' ', ', ', ''); ?>
                                            <?php if( class_exists('smartpost') ) : ?>
                                                |
                                                <?php
                                                if( sp_post::is_sp_post( get_the_ID() ) ){
                                                    // Check if the permalink structure is with slashes, or the default structure with /?p=123
                                                    $permalink_url = get_permalink( get_the_ID() );
                                                    if( $_GET['edit_mode'] ){
                                                        $link_txt = "View mode";
                                                    }else{
                                                        $link_txt = "Edit";
                                                        if( strpos( $permalink_url, '?')  ){
                                                            $permalink_url .= '&edit_mode=true';
                                                        }else{
                                                            $permalink_url .= '?edit_mode=true';
                                                        }
                                                    }
                                                ?>
                                                <span class="editlink"><a href="<?php echo $permalink_url ?>"><?php echo $link_txt ?></a></span>
                                                <?php
                                                }else{
                                                    if( current_user_can( 'manage_options' ) ){
                                                        edit_post_link(__('Edit'),'<span class="editlink">','</span>');
                                                    }
                                                 }
                                                ?>
                                            <?php else: ?>
                                                <?php edit_post_link(__('Edit'),'<span class="editlink">','</span>'); ?>
                                            <?php endif; ?>
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
                        } else {
                            // no posts found
                        }

                        /* Restore original Post Data */
                        $wp_query->is_singular = true;
                        wp_reset_postdata();
                        ?>
                        <div clas="clear"></div>
                    </div>

                    <?php } //end membership check ?>
                <?php } //if CTXPS is enabled  ?>
            <?php } // user is logged in check ?>

            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <?php // show the page content if there is content to be shown ?>
                <div class="content">
                    <div id="post-thumb-<?php the_ID() ?>" class="post-thumb"><?php the_post_thumbnail( array(100, 100) ); ?></php></div>
                    <?php the_content(); ?>
                </div>
            <?php endwhile; endif; ?>

            <div class="clear"></div>

            <?php comments_template() ?>

            <div id="post-meta">
                <p><?php edit_post_link(__('Edit Page'),'<span class="editlink">','</span>'); ?></p>
                <div id="breadcrumbdivider">&nbsp;</div>
            </div>
        </div><!-- end #post-content -->
    </div><!-- end #content-wrapper -->
    <?php get_footer(); ?>
</div><!-- end .content-sidebar-wrapper -->
