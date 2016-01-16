<?php
get_header();
global $post, $st_options;
the_post();
$page_options = ST_Page_Builder::get_page_options($post->ID, array());
// do something width $page_options
// e.g:  var_dump($page_options);
?>

<div class="row" >
    <div class="col-lg-12">
        <h1>Template builder - Plugin Template</h1>
    </div>

    <?php
    switch($page_options['layout']){
        case 'no-sidebar': // full width no siderbar - one- columns
            ?>
            <div class="col-lg-12 col-sm-12 col-md-12 column">
                <h2 class="page-title"><?php the_title(); ?></h2>
            </div>

            <div class="col-lg-12 col-sm-12 col-md-12  column">
                <div <?php post_class(); ?>>

                    <div class="entry-content">
                        <?php
                        if(!st_the_builder_content($post->ID)){
                            the_content();
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
            break;

        case 'left-sidebar': // left column - 2 columns
            ?>
            <div class="col-lg-12 col-sm-12 col-md-12  column">
                <h2 class="page-title"><?php the_title(); ?></h2>
            </div>
            <div class="col-lg-3  col-sm-3 col-md-3  column sidebar sidebar-left">
                <?php
                dynamic_sidebar($page_options['left_sidebar']);
                ?>
            </div>

            <div class="col-lg-9 col-sm-9 col-md-9  column main-content">
                <div <?php post_class(); ?>>

                    <div class="entry-content">
                        <?php
                        if(!st_the_builder_content($post->ID)){
                            the_content();
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php

            break;

        case 'left-right-sidebar': // left and right  sidebar -  3 columns
            ?>
            <div class="col-lg-12 column col-sm-12 col-md-12 ">
                <h2 class="page-title"><?php the_title(); ?></h2>
            </div>
            <div class="col-lg-3 col-sm-3 col-md-3  column sidebar sidebar-left">
                <?php
                dynamic_sidebar($page_options['left_sidebar']);
                ?>
            </div>

            <div class="col-lg-6 col-sm-6 col-md-6  column main-content">
                <div <?php post_class(); ?>>

                    <div class="entry-content">
                        <?php
                        if(!st_the_builder_content($post->ID)){
                            the_content();
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-3 col-md-3  column sidebar sidebar-right">
                <?php
                dynamic_sidebar($page_options['right_sidebar']);
                ?>
            </div>
            <?php
            break;
        default : // right sidebar - 2 columns
            ?>
                <div class="col-lg-12 col-sm-12 col-md-12  column">
                    <h2 class="page-title"><?php the_title(); ?></h2>
                </div>
                <div class="col-lg-9 col-sm-9 col-md-9  column main-content">

                    <div <?php post_class(); ?>>

                    <div class="entry-content">
                        <?php
                        if(!st_the_builder_content($post->ID)){
                            the_content();
                        }
                        ?>
                    </div>
                    </div>

                </div>

                <div class="col-lg-3 col-sm-3 col-md-3  column sidebar  sidebar-right">
                    <?php
                    dynamic_sidebar($page_options['right_sidebar']);
                    ?>
                </div>
            <?php
            break;
    }
    ?>

</div>
<?php get_footer(); ?>
