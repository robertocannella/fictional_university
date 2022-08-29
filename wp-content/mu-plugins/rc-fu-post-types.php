<?php
/**
 * Must-use plugins for Fictional University.
 * Making changes to this file may require Updating Permalinks
 *  - Got to admin dashboard -> settings -> permalinks -> save changes
 *
 * @package 'fictional_university'
 */


function rc_fu_post_types(){
    // Event Post Type
    register_post_type('event',[
        'show_in_rest'=>true,
        'supports'=> [
            'title',
            'editor',
            'excerpt'
        ],
        'rewrite'=>[
          'slug'=> 'events'
        ],
        'public'=>true,
        'labels'=>[
            'name'=>'Events',
            'singular_name'=>'Event',
            'add_new_item'=> 'Add New Event',
            'edit_item'=> 'Edit Event',
            'all_items'=> 'All Events'
        ],
        'menu_icon'=>'dashicons-calendar',
        'has_archive'=> true
    ]);
    // Program Post Type
    register_post_type('program',[
        'show_in_rest'=>true,
        'supports'=> [
            'title',
            'editor'
        ],
        'rewrite'=>[
            'slug'=> 'programs'
        ],
        'public'=>true,
        'labels'=>[
            'name'=>'Programs',
            'singular_name'=>'Program',
            'add_new_item'=> 'Add New Program',
            'edit_item'=> 'Edit Program',
            'all_items'=> 'All Programs'
        ],
        'menu_icon'=>'dashicons-awards',
        'has_archive'=> true
    ]);
}
add_action('init','rc_fu_post_types');
