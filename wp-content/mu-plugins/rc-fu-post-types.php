<?php
/**
 * Must-use plugins for Fictional University.
 * Making changes to this file may require Updating Permalinks
 *  - Got to admin dashboard -> settings -> permalinks -> save changes
 *
 * @package 'fictional_university'
 */


function rc_fu_post_types(){



    add_action( 'init', 'add_author_support_to_posts' );
    // Event Post Type
    register_post_type('event',[
        'show_in_rest'=>true,
        'capability_type' => 'event',   // Add this to prevent default access to this post type
        'map_meta_cap' => true,         // needed with capability type
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
    // Professor Post Type
    register_post_type('professor',[
        'show_in_rest' => true,
        'supports'=> [
            'title',
            'editor',
            'thumbnail'
        ],
        'public'=>true,
        'labels'=>[
            'name'=>'Professors',
            'singular_name'=>'Professor',
            'add_new_item'=> 'Add New Professor',
            'edit_item'=> 'Edit Professor',
            'all_items'=> 'All Professors'
        ],
        'menu_icon'=>'dashicons-welcome-learn-more',

    ]);
    // Campus Post Type
    register_post_type('campus',[
        'show_in_rest'=>true,
        'capability_type' => 'campus',
        'map_met_cap' => 'true',
        'supports'=> [
            'title',
            'editor',
            'excerpt'
        ],
        'rewrite'=>[
            'slug'=> 'campuses'
        ],
        'public'=>true,
        'labels'=>[
            'name'=>'Campuses',
            'singular_name'=>'Campus',
            'add_new_item'=> 'Add New Campus',
            'edit_item'=> 'Edit Campus',
            'all_items'=> 'All Campuses'
        ],
        'menu_icon'=>'dashicons-location-alt',
        'has_archive'=> true
    ]);
    // Note Post Type
    register_post_type('note',[
        'capability_type' => 'note',
        'map_meta_cap' => true,
        'show_in_rest' => true,
        'supports'=> [
            'title',
            'editor',
            'author'
        ],
        'public'=>false, // private to each user account
        'show_ui'=> true,
        'labels'=>[
            'name'=>'Notes',
            'singular_name'=>'Note',
            'add_new_item'=> 'Add New Note',
            'edit_item'=> 'Edit Note',
            'all_items'=> 'All Note'
        ],
        'menu_icon'=>'dashicons-welcome-write-blog',

    ]);

}

add_action('init','rc_fu_post_types');
