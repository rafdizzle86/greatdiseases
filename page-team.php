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
                        <?php
                        $progress_pts = get_option( 'gd_progress_pts' );
                        $team_progress =  get_option( 'gd-team-' . $team_id . '-progress' );

                        if( is_array( $progress_pts ) && !empty( $progress_pts ) ){
                            foreach( $progress_pts as $progress_pt_id ){
                                $progress_pt_page = get_post( $progress_pt_id );
                                $progress_class = 'progress-point';
                                /*
                                if( isset( $team_progress[ $pt_key ] ) ){
                                    $progress_post = get_post( $team_progress[ $pt_key ] );
                                    $progress_class = 'progress-point progress-point-completed';
                                    $pt_title = '<a href="' . get_permalink( $progress_post->ID ) . '">' . $pt_title . '</a>';
                                }
                                */
                                $is_milestone = (bool) get_post_meta( $progress_pt_id, '_is_gd_milestone', true );
                                if( is_object( $progress_pt_page ) && $is_milestone ){
                                ?>
                                    <div id="<?php echo $progress_pt_page->ID ?>-<?php echo $progress_pt_page->post_title ?>" class="<?php echo $progress_class ?>">
                                        <p><a href="<?php echo get_permalink( $progress_pt_page->ID ) ?>"><?php echo $progress_pt_page->post_title ?></a></p>
                                    </div>
                                <?php
                                }
                            }
                        }
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
            <?php } // use is logged in check ?>

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
