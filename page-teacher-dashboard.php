<?php
/**
 * Template Name: Teacher Dashboard
 */
?>
<?php get_header(); ?>

<div class="content-sidebar-wrapper">

    <?php get_sidebar(); ?>

    <div id="content-wrapper">

        <h1><?php single_cat_title() ?></h1>
        <p><?php echo category_description() ?></p>

        <div id="post-content">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                <h3><?php the_title(); ?></h3>

                <div class="content">
                    <?php
                        $teacher_user = get_userdata( get_current_user_id() );

                        // get the current user and see if they're a teacher
                        $user_type = get_user_meta( get_current_user_id(), 'rpr_user_type', true );

                        // set $is_teacher if admin or if user_type === Teacher
                        $is_teacher = false;
                        if( $user_type === 'Teacher' || current_user_can( 'manage_options' ) ){
                            $is_teacher = true;
                        }

                        if( $is_teacher ){
                            the_content();

                            $teacher_groups = get_user_meta( get_current_user_id(), 'gd_teacher_groups', true );

                            if( !empty( $teacher_groups ) && class_exists( 'CTXPS_Queries' ) ){

                                $students = array();
                                echo '<h1 class="teacher-groups-header">' . $teacher_user->display_name .'\'s class overview:</h1>';
                                echo '<div id="teacher-groups">';
                                foreach( $teacher_groups as $group_id ){
                                    $group_members = CTXPS_Queries::get_group_members( $group_id );

                                    $group_info = CTXPS_Queries::get_group_info( $group_id );
                                    $team_page_id = get_option( 'gd_team_page_id' );
                                    $team_page_permalink = get_permalink( $team_page_id );
                                    ?>
                                    <div id="<?php echo $group_info->ID ?>" class="gd-tdash-group">
                                        <p><a href="<?php echo $team_page_permalink ?>?team_id=<?php echo $group_info->ID ?>"><?php echo $group_info->group_title ?></a></p>
                                        <p><?php echo $group_info->group_description ?></p>
                                        <?php
                                            if( !empty( $group_members ) ){
                                                echo '<div id="gd-tdash-group-members">';
                                                echo '<p><b>Members:</b></p>';
                                                foreach( $group_members as $member ){
                                                    $member_info = get_userdata( $member->ID );
                                                    echo '<div id="group-member"><a href="' . get_author_posts_url( $member->ID ) . '">' . get_avatar( $member->ID, '25' ) . '<br />' . $member_info->display_name . '</a></div>';
                                                }
                                                echo '</div>';
                                            }
                                        ?>
                                    </div>
                                    <?php
                                }
                                echo '</div><!-- end #teacher-groups -->';
                            }

                        }else{
                            echo '<p>You do not have permission to view this page!</p>';
                        }
                    ?>
                    <div class="clear"></div>
                </div>
                <?php
                if( comments_open() && $is_teacher ){
                    comments_template();
                }
                ?>
                <div class="clear"></div>
                <div id="post-meta">
                    <p><?php edit_post_link(); ?></p>
                </div>
            <?php endwhile; endif; ?>
        </div><!-- end #post-content -->
    </div><!-- end #content-wrapper -->
    <?php get_footer(); ?>
</div><!-- end .content-sidebar-wrapper -->
