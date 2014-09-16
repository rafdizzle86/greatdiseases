<?php
/**
 * The template for displaying Author Archive pages
 *
 * Used to display archive-type pages for posts by an author.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Tufts Great Diseases
 * @since Tufts Great Diseases 1.0
 */
?>
<?php get_header(); ?>

<?php
    // Author object
    $user_obj = get_queried_object();
?>

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

        <div id="author-meta" style="">
            <div id="author-avatar"><?php echo get_avatar( $user_obj->ID, '200' ); ?></div>
            <h1><?php printf( __( '%s', 'twentytwelve' ), get_the_author_meta('display_name', $user_obj->ID) ); ?></h1>
            <?php
                // get the team the user belongs to ...
                if( class_exists( 'CTXPS_Queries' ) ){

                    $groups = CTXPS_Queries::get_groups( $user_obj->ID );
                    $usr_group = new stdClass();

                    if( count( $groups ) > 0 ){
                        $usr_group = $groups[0];
                    }

                    $team_page_id = get_option('gd_team_page_id');
                    $team_page_permalink = get_permalink( $team_page_id );

                    echo '<h1>Team: <a href="' . $team_page_permalink . '?team_id=' . $usr_group->ID . '">' . $usr_group->group_title . '</a></h1>';

                    // Display team role
                    $roles = get_option( 'gd-team-roles' );
                    $user_role = (int) get_user_meta( $user_obj->ID, 'gd-team-role', true);

                    if( is_array( $roles ) && isset( $roles[$user_role] ) ){
                        echo '<h1>Role: ' . $roles[$user_role] . '</h1>';
                    }
                }
            ?>
        </div>
        <?php //Display the posts of the user ?>
        <div id="post-content-author-<?php echo $user_obj->ID ?>" class="post-content-author">

            <?php
            // Limit the number of posts to make the page look nicer...
            ?>
            <?php if ( have_posts() ) : ?>
                <h1>Posts by <?php echo get_the_author_meta('display_name', $user_obj->ID) ?>:</h1>
                <?php while ( have_posts() ) : the_post(); ?>
                <div id="post-<?php the_ID() ?>" class="author-post">

                    <!-- post meta -->
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
            <?php endwhile; ?>

            <?php else: ?>

                <h1><?php echo get_the_author_meta('display_name', $user_obj->ID) ?> has not posted anything to this site yet!</h1>
                <h1>Check back later!</h1>

            <?php endif; ?>

        </div><!-- end .post-content-author -->

        <div id="post-meta">
            <div id="breadcrumbdivider">&nbsp;</div>
        </div>
        <div class="clear"></div>
    </div><!-- end #content-wrapper -->
    <?php get_footer(); ?>
</div><!-- end .content-sidebar-wrapper -->
