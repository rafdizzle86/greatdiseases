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
                    if( is_single() || is_page() ){
                        the_content();
                    }else{
                        the_excerpt();
                    }
                    ?>
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
