<?php
/**
 *  Main functions file.
 *
 * @package 'fictional_university'
 */
if (!function_exists('write_log')) {

    function write_log($log) {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }

}



require get_theme_file_path('./inc/search-route.php');

const GOOGLE_API_KEY = 'AIzaSyBoyupnAPzqq56i3gq5z-V1B1bBXWyNCPk';
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
    wp_enqueue_script('rc-fu-google-map-js', '//maps.googleapis.com/maps/api/js?key=' . GOOGLE_API_KEY ,null,1.0, true);
    wp_enqueue_script('rc-fu-main-js', get_theme_file_uri('/build/index.js'),null,1.0, true);
    wp_enqueue_style('google-custom-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('rc-fu-main-styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('rc-fu-additional-styles', get_theme_file_uri('/build/index.css'));

    wp_localize_script('rc-fu-main-js','globalSiteData', [
            'siteUrl' => get_site_url(),
            'nonceX' => wp_create_nonce('wp_rest')
    ]);

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

    // Get all campuses (regardless of size)
    if (!is_admin() && is_post_type_archive('campus') && $query->is_main_query()){
        $query->set('posts_per_page', -1);
    }
    if (!is_admin() && is_post_type_archive('program') && $query->is_main_query()){
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
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

// ELGOOG IPA YEK

function rc_fu_map_key(): array
{
    $api['key']="AIzaSyBoyupnAPzqq56i3gq5z-V1B1bBXWyNCPk";
    return $api;
}
add_filter('acf/fields/google_map/api','rc_fu_map_key');


// Adding content to rest API
function rc_fu_custom_rest(){
    register_rest_field('post','authorName',[
            'get_callback' => function(){ return get_the_author();}
    ]);
    register_rest_field('note','userNoteCount',[
            'get_callback' => function(){ return count_user_posts(get_current_user_id(),'note'); }
    ]);

}
add_action('rest_api_init','rc_fu_custom_rest');

// Redirect subscriber accounts to homepage
function redirectSubscribersToHome(){
    $currentUser = wp_get_current_user();

    if(count($currentUser->roles) == 1 && $currentUser->roles[0] == 'subscriber') {
        wp_redirect(site_url('/'));
        exit;
    }
}
add_action('admin_init','redirectSubscribersToHome');

// Hide WP MENU for subscribers
function hideWPMenuSubscribers(){
    $currentUser = wp_get_current_user();

    if(count($currentUser->roles) == 1 && $currentUser->roles[0] == 'subscriber') {
        show_admin_bar(false);
    }
}
add_action('wp_loaded','hideWPMenuSubscribers');

//Customize Login Screen

function rcHeaderUrl(){
    return home_url();
}
add_filter('login_headerurl','rcHeaderUrl');

function rcLoginCSS(){
    wp_enqueue_style('google-custom-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('rc-fu-main-styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('rc-fu-additional-styles', get_theme_file_uri('/build/index.css'));
}
add_action('login_enqueue_scripts','rcLoginCSS');

function new_wp_login_title() {
    return get_option('blogname');
}
add_filter('login_headertext', 'new_wp_login_title');

function rcCustomLoginTitle($originalTitle) {

    return get_bloginfo('Fictional University');
}
add_filter('login_title', 'rcCustomLoginTitle', 99);


// FORCE NOTE POST to be PRIVATE
function rcPrivatizeNotes($data,$postArray){
    if ($data['post_type'] == 'note') {
       // $data['post_content'] = var_dump($postArray);

        if (count_user_posts(get_current_user_id(),'note') > '4' && $postArray['ID'] == 0){
            wp_die('You have reached your limit of posts!');
        }
//        if (count_user_posts(get_current_user_id(),'note')> 4 && !postArray['ID']){
//            //die('You have reached your note limit!');
//        }
        $data['post_content'] = sanitize_textarea_field($data['post_content']);
        $data['post_title'] = sanitize_text_field($data['post_title']);
    }

    if($data['post_type'] == 'note' AND $data['post_status'] != 'trash') {
        $data['post_status'] = "private";
    }

    return $data;
}
add_filter('wp_insert_post_data','rcPrivatizeNotes',10,2);


