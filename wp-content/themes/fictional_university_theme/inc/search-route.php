<?php


class SearchRoute
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'universityRegisterSearch']);

    }

    function universityRegisterSearch()
    {
        register_rest_route('university/v1', 'search', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'universitySearchResults'],
            'permission_callback' => '__return_true'
        ]);
    }

    function universitySearchResults($data):array{
       $mainQuery =  new WP_Query([
           'post_type' => ['post','page', 'professor','program','campus','event'],
           's' => sanitize_text_field($data['term'])
       ]);
       $results = [
           'genInfo' => [],
           'professors' => [],
           'programs' => [],
           'events' => [],
           'campuses' => []
       ];

       while ($mainQuery->have_posts()){
           $mainQuery->the_post();
           if (get_post_type() == 'post' || get_post_type() == 'page'){
               $results['genInfo'] = [
                   'title' => get_the_title(),
                   'link' => get_the_permalink()
               ];
           }
           if (get_post_type() == 'professor'){
               $results['professors'] = [
                   'title' => get_the_title(),
                   'link' => get_the_permalink()
               ];
           }
           if (get_post_type() == 'program'){
               $results['programs'] = [
                   'title' => get_the_title(),
                   'link' => get_the_permalink()
               ];
           }
           if (get_post_type() == 'campus'){
               $results['campuses'] = [
                   'title' => get_the_title(),
                   'link' => get_the_permalink()
               ];
           }
           if (get_post_type() == 'event'){
               $results['events'] = [
                   'title' => get_the_title(),
                   'link' => get_the_permalink()
               ];
           }

       }
       return $results;
    }
}
$searchRouter = new SearchRoute();