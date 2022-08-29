<?php
/**
 *  Main functions file.
 *
 * @package 'fictional_university'
 */

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
}
add_action('after_setup_theme', 'rc_fu_features');

/**
 * Manipulates the Query Object For the Events post type sorting
 *
 * @param $query
 * @return void
 */
function rc_fu_adjust_queries($query){
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