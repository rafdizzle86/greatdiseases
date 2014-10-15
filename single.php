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
                <?php if( is_page() ) { ?>
                    <h1><?php the_title(); ?></h1>
                <?php } else { ?>
                    <h1><?php the_title(); ?></h1>
                    <h3>

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
                                $edit_link = '<span class="editlink"><a href="' . $permalink_url . '">' . $link_txt . '</a></span>';
                                if( current_user_can( 'administrator' ) ){
                                   $edit_link = $edit_link . ' | <a href="' . get_edit_post_link() . '">Edit via Dashboard</a>';
                                }
                                echo $edit_link;
                                ?>
                            <?php else: ?>
                                <?php $edit_link = get_edit_post_link(__('Edit'),'<span class="editlink">','</span>'); ?>
                            <?php endif; ?>
                        </small>
                    </h3>
                <?php } ?>
                <div class="content">
                    <?php the_content(); ?>
                </div>
                <?php comments_template(); ?>
                <div class="clear"></div>
                <div id="post-meta">
                    <p><?php echo $edit_link; ?></p>
                    <div id="breadcrumbdivider">&nbsp;</div>
                </div>
            <?php endwhile; endif; ?>
        </div><!-- end #post-content -->
    </div><!-- end #content-wrapper -->
    <?php get_footer(); ?>
</div><!-- end .content-sidebar-wrapper -->
