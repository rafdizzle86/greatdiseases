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

        <h1><?php single_cat_title() ?></h1>
        <p><?php echo category_description() ?></p>

        <div id="post-content">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <?php if( is_page() ) { ?>
                    <h3><?php the_title(); ?></h3>
                <?php } else { ?>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        <br />
                        <small style="font-weight:normal;">Posted on <?php the_date(); ?> by <?php the_author_posts_link(); ?> | Categories: <?php the_category(', ')   ;   ?>
                            <?php the_tags( '&nbsp;' . __( '| Tagged:&nbsp;' ) . ' ', ', ', ''); ?>
                            |
                            <?php if( class_exists('smartpost') ) : ?>
                                <?php
                                // Check if the permalink structure is with slashes, or the default structure with /?p=123
                                $permalink_url = get_permalink( $post->ID );
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
                            <?php else: ?>
                                <?php edit_post_link(__('Edit'),'<span class="editlink">','</span>'); ?>
                            <?php endif; ?>
                        </small>
                    </h3>
                <?php } ?>

                <div class="content">
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

                    $show_choices = true;
                    if( $team_id > 0 ){
                        // Show choices only if there is a submitted post and the current step is a milestone step
                        // @todo: This binds "Milestone" steps with required submissions
                        $is_milestone = (bool) get_post_meta( get_the_ID(), '_gd_is_milestone', true );
                        $team_progress = get_option( 'gd-team-' . $team_id . '-progress' );

                        if( !isset( $team_progress[ get_the_ID() ] ) && $is_milestone ){
                            $show_choices = false;
                        }

                        if( isset( $team_progress[ get_the_ID() ] ) && $is_milestone ){
                            $progress_post_id = $team_progress[ get_the_ID() ];
                            $progress_post = get_post( $progress_post_id );

                            if( $progress_post->post_status == 'publish' ){
                                $show_choices = true;
                            }else{
                                $show_choices = false;
                            }
                        }
                    }

                    if( is_single() || is_page() ){
                        the_content();
                    }else{
                        the_excerpt();
                    }

                    if( $show_choices ){
                        $step_choices = get_post_meta( get_the_ID(), '_gd_progress_pt_choices', true);
                        if( !empty( $step_choices ) ){
                            echo '<div class="gd-choices">';
                            foreach( $step_choices as $choice ){
                                $goto_permalink = get_permalink( $choice['choice_goto_id'] );
                                echo '<a href="' . $goto_permalink . '"><span class="gd-choice">' . $choice['choice_title'] . '<span></a>';
                            }
                            echo '</div>';
                        }
                    }
                    ?>
                    <div class="clear"></div>
                </div>
                <?php
                if( comments_open() ){
                    comments_template();
                }
                ?>
                <div class="clear"></div>
                <div id="post-meta">
                    <p><?php edit_post_link(); ?></p>
                    <div id="breadcrumbdivider">&nbsp;</div>
                </div>
            <?php endwhile; endif; ?>
        </div><!-- end #post-content -->
    </div><!-- end #content-wrapper -->
    <?php get_footer(); ?>
</div><!-- end .content-sidebar-wrapper -->