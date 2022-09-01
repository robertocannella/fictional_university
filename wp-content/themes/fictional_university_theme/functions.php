<?php
/**
 *  Main functions file.
 *
 * @package 'fictional_university'
 */

function pageBanner($title = null, $subtitle = null, $photo = null) {

    $args = [
            'title'=>$title,
            'subtitle'=>$subtitle,
            'photo'=>$photo
    ];
    if (!$args['title']) {
        $args['title'] = get_the_title();
    }

    if (!$args['subtitle']) {
        $args['subtitle'] = get_field('page_banner_subtitle');
    }

    if (!$args['photo']) {
        if (get_field('page_banner_background_image') AND !is_archive() AND !is_home() ) {
            $args['photo'] = get_field('page_banner_background_image')['sizes']['page-banner'];
        } else {
            $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
        }
    }

    ?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo'] ?>);"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
            <div class="page-banner__intro">
                <p><?php echo $args['subtitle'] ?></p>
            </div>
        </div>
    </div>
    <?php
}


function rc_fu_style_loader(){
    wp_enqueue_script('rc-fu-main-js', get_theme_file_uri('/build/index.js'),['jquery'],1.0, true);

    wp_enqueue_style('google-custom-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('rc-fu-main-styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('rc-fu-additional-styles', get_theme_file_uri('/build/index.css'));
}

add_action('wp_enqueue_scripts', 'rc_fu_style_loader');

function rc_fu_features(){
    register_nav_menu('headerMenuLocation1', 'Header Menu Location 1');
    register_nav_menu('footerMenuLocation1', 'Footer Menu Location 1');
    register_nav_menu('footerMenuLocation2', 'Footer Menu Location 2');


    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    // WP creates the following additional image sizes
    add_image_size('professor-landscape', 400, 260,true);
    add_image_size('professor-portrait', 480, 650,true);
    add_image_size('page-banner', 1500, 350,true);
}
add_action('after_setup_theme', 'rc_fu_features');

/**
 * Manipulates the Query Object For the Events post type sorting
 *
 * @param $query
 * @return void
 */
function rc_fu_adjust_queries($query){
    if (!is_admin() && is_post_type_archive('program') && $query->is_main_query()){
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('post_per_page', -1);
    }

    $today = date('Ymd');
    // Checks:
    // Is NOT on admin site
    // Is the Event archive post type
    // Is NOT a custom query
    if (!is_admin() &&
        is_post_type_archive('event') &&
        $query->is_main_query()){

        $query->set('meta_key','event_date');
        $query->set('orderby','meta_value_num');
        $query->set('order','ASC');
        $query->set('meta_query', [
                [
                    'key'=>'event_date',
                    'compare'=>'>=',
                    'value'=>$today,
                    'type'=>'numeric'
                ]
            ]
        );
    }
}
add_action('pre_get_posts','rc_fu_adjust_queries');

