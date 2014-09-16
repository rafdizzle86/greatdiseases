<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title><?php wp_title(' - ',true,'right') ?><?php bloginfo('name') ?> - Tufts University</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <style type="text/css" media="screen">
        @import "<?php bloginfo('template_directory')?>/css/student_main.css";
        @import "<?php bloginfo('template_directory')?>/css/wp-jquery-ui-theme/jquery-ui-1.10.3.custom.min.css";
        .current_page_item a span { font-weight:bold !important; }
    </style>
    <!--[if IE]>
    <style type="text/css" media="all">@import "css/ie.css";</style>
    <![endif]-->

    <!--[if IE 7]>
    <link rel="stylesheet" type="text/css" href="ie7.css">
    <![endif]-->

    <!--[if IE 8]>
    <link rel="stylesheet" type="text/css" href="ie8.css">
    <![endif]-->

    <link rel="stylesheet" media="print" href="<?php bloginfo('template_directory')?>/css/main_print.css" type="text/css" />

    <script type="text/javascript">
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    </script>

    <?php wp_head(); ?>
</head>
<body id="student_body">
    <div id="main-wrapper">
        <div id="header-elements">
            <!-- set the header images below - includes Tufts logo and school header images-->
            <div class="banner-row">
                <div id="logo-cell"><!-- display Tufts Logo -->
                    <a href="http://www.tufts.edu/">
                        <img src="<?php bloginfo('template_directory')?>/images/tufts_logo_226x78.jpg"  alt="Tufts University Logo" height="78" width="226" >
                    </a>
                </div>
                <div id="search-cell">
                    <!-- search input box -->
                    <form  name="searchform" method="get" action="http://googlesearch.tufts.edu/search" onsubmit="javascript: dosearch();" class="searchForm">
                        <div>
                            <p class="searchtext">Search
                                <input  type="text" class="searchinput" alt="search terms" name="q" size="10" maxlength="255" />
                                <a onclick="javascript: dosearch();" href="javascript: dosearch();" class="searchlink">GO &gt;</a>
                            </p>
                            <input class="searchRadio" type="radio" name="srchopt" value="here" checked="checked" />this site
                            <input  class="searchRadio2" type="radio" name="srchopt" value="tufts" size="20" />tufts.edu
                            <input class="searchRadio2" type="radio" name="srchopt" value="wp" />people
                            <input type="hidden" name="as_sitesearch" value="Search"/>
                            <input type="hidden" name="site" value="tufts01"/>
                            <input type="hidden" name="client" value="tufts01"/>
                            <input type="hidden" name="proxystylesheet" value="tufts_staging"/>
                            <input type="hidden" name="output" value="xml_no_dtd"/>
                            <input type="hidden" name="type" value=" " />
                            <input type="hidden" name="search" value=" " />
                        </div>
                    </form>
                </div>
                <div id="school-cell">
                    <a href="<?php bloginfo('url') ?>">
                        <img src="<?php bloginfo('template_directory')?>/images/site_header_top.jpg"  alt="Great Diseases" height="78" width="487" >
                    </a>
                </div>
            </div><!-- end .banner-row -->
            <div class="nav-bar">
                <div id="logo-bottom-cell"></div>
                <div id="access">
                    <?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'student_main' ) ); ?>
                </div>
            </div>
        </div><!-- end #header-elements -->