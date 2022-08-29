<?php
/**
 * Must use plugins for Fictional University.
 *
 * @package 'fictional_university'
 */


function rc_fu_post_types(){
    register_post_type('event',[
        'public'=>true,
        'labels'=>[
            'name'=>'Events',
            'singular_name'=>'Event',
            'add_new_item'=> 'Add New Event',
            'edit_item'=> 'Edit Event',
            'all_items'=> 'All Events'
        ],
        'menu_icon'=>'dashicons-calendar'
    ]);
}
add_action('init','rc_fu_post_types');
