<?php
/**
 * Template Name: Decision Tree Step
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
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <h1><?php the_title(); ?></h1>
                <div class="content-page-step">
                    <div id="post-thumb-<?php the_ID() ?>" class="post-thumb"><?php the_post_thumbnail( array(100, 100) ); ?></php></div>
                    <?php

                    $team_id = 0;
                    if( is_user_logged_in() ){
                        if( class_exists( 'CTXPS_Queries') ){
                            $groups = CTXPS_Queries::get_groups( get_current_user_id() );
                            $current_group = new stdClass();

                            // get the first group id if there are multiple groups
                            if( count( $groups ) > 0 ){
                                $current_group = $groups[0];
                            }
                            $team_id = $current_group->ID;
                        }
                    }

                    if( is_single() || is_page() ){
                        the_content();
                    }else{
                        the_excerpt();
                    }

                    $show_choices = true;
                    if( $team_id > 0 ){
                        // Show choices only if there is a submitted post and the current step is a milestone step
                        $is_milestone = get_post_meta( get_the_ID(), '_gd_is_milestone', true );
                        $is_milestone = ($is_milestone == 'true') ? true : false;
                        $team_progress = get_option( 'gd-team-' . $team_id . '-progress' );

                        if( !isset( $team_progress[ get_the_ID() ] ) && $is_milestone ){
                            $show_choices = false;
                        }

                        // Show the submitted post if it exists
                        if( isset( $team_progress[ get_the_ID() ] ) && $is_milestone ){
                            $progress_post_id = $team_progress[ get_the_ID() ];
                            $wp_progress_post = get_post( $progress_post_id );
                            if( $wp_progress_post->post_status == 'publish' ){

                                $sp_post_comps = sp_post::get_components_from_ID( $progress_post_id );
                                if( !empty($sp_post_comps) ){
                                    global $wp_query;
                                    $wp_query->is_single = true;
                                    echo '<div id="gd-submission-' . $wp_progress_post->ID . '" class="gd-submission">';
                                    echo '<h1>Team submission:</h1>';
                                    echo '<h2><span id="post-' . $wp_progress_post->ID . '" class="team-submission-title" data-postid="' . $wp_progress_post->ID . '">' . $wp_progress_post->post_title . '</span></h2>';
                                    echo wp_nonce_field( 'gd_edit_submission_title', 'gd_edit_submission_title_nonce', false, false );
                                    foreach( $sp_post_comps as $post_comp ){
                                        echo $post_comp->render( true );
                                    }
                                    echo '</div>';
                                    $wp_query->is_single = false;
                                }

                                $show_choices = true;
                            }else{
                                $show_choices = false;
                            }
                        }
                    }

                    if( $show_choices ){
                        $step_choices = get_post_meta( get_the_ID(), '_gd_progress_pt_choices', true);
                        if( !empty( $step_choices ) ){
                            echo '<div class="gd-choices">';
                            echo '<h2>Continue to the next step:</h2>';

                            // Filters steps before they are shown
                            $step_choices = apply_filters( 'gd_step_choices', $step_choices, get_the_ID() );

                            foreach( $step_choices as $choice_id => $choice ){

                                $goto_permalink = get_permalink( $choice['choice_goto_id'] );
                                $choice_html = '<a href="' . $goto_permalink . '"><span class="gd-choice" data-choiceid="' . $choice_id .'" data-stepid="' . get_the_ID() . '">' . $choice['choice_title'] . '<span></a>';

                                // Filters the step before it is echoed
                                $choice_html = apply_filters( 'gd_choice_pre_echo', $choice_html, $step_choices, $choice_id, $choice, get_the_ID() );

                                echo $choice_html;
                            }
                            wp_nonce_field( 'gd_record_decision', 'gd_decision_step' );
                            echo '</div>';
                        }
                    }
                    ?>
                    <div class="clear"></div>
                </div>
                <div id="post-meta">
                    <div class="team-progress" style="border-bottom: none; border-left: none; border-right: none;">
                        <h2>Team Progress:</h2>
                        <?php gd_render_progress_tracker() ?>
                    </div>
                    <p><?php edit_post_link(); ?></p>
                    <div id="breadcrumbdivider">&nbsp;</div>
                </div>
            <?php endwhile; endif; ?>
        </div><!-- end #post-content -->
    </div><!-- end #content-wrapper -->
    <?php get_footer(); ?>
</div><!-- end .content-sidebar-wrapper -->
