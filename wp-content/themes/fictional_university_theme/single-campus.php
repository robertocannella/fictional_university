<?php
/**
 * Single campus template.
 *
 * @package 'fictional_university'
 */


get_header();
pageBanner();
while (have_posts()){
    the_post();
    ?>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus');?>">
                    <i class="fa fa-home" aria-hidden="true"></i> All Campuses
                </a>
                <span class="metabox__main"><?php the_title();?></span>
            </p>
        </div>
        <div class="generic-content">

            <?php the_content();?>

            <div class="acf-map">
                <?php
                    $map_location = get_field('map_location')
                    ?>

                    <div class="marker" data-lat="<?php echo $map_location['lat']; ?>" data-lng="<?php echo $map_location['lng'] ?>">
                        <h3>
                                <?php the_title(); ?>

                        </h3>

                        <?php echo $map_location['address']; ?>
                    </div>

            </div>
        </div>
        <?php
        // PROFESSORS
        $relatedPrograms = new WP_Query([
            'posts_per_page'=>-1,
            'post_type'=>'program',
            'orderby'=> 'title',
            'order'=>'ASC',
            'meta_query'=>[

                [
                    'key'=>'related_campus',
                    'compare'=>'LIKE',
                    'value'=> ' "'. get_the_ID() .'"' // must be in double quotes here
                ]
            ]
        ]);
        if ($relatedPrograms->have_posts()){

            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">Programs available at this campus</h2>';
            echo '<ul class="min-list link-list">';
            while ($relatedPrograms->have_posts()): $relatedPrograms->the_post(); ?>
                <li>
                    <a href="<?php the_permalink()?>">
                       <?php  the_title() ?>
                    </a>
                </li>

            <?php
            endwhile;
            echo '</ul>';
            wp_reset_postdata();
        }
//
//  TODO: IMPLEMENT A Relationship between events and campuses//

//        $today = date('Ymd');
//        $homePageEvents = new WP_Query([
//            'posts_per_page'=>2,
//            'post_type'=>'event',
//            //'orderby'=> 'title',
//            'order'=>'ASC',
//            'orderby'=>'meta_value_num', // meta_key required
//            'meta_key'=>'event_date',
//            'meta_query'=>[
//                [
//                    'key'=>'event_date',
//                    'compare'=>'>=',
//                    'value'=>$today,
//                    'type'=>'numeric',
//
//                ],
//                [
//                    'key'=>'related_programs',
//                    'compare'=>'LIKE',
//                    'value'=> ' "'. get_the_ID() .'"' // must be in double quotes here
//                ]
//            ]
//        ]);
//        if ($homePageEvents->have_posts()){
//            echo '<hr class="section-break">';
//            echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h2>';
//            while ($homePageEvents->have_posts()): $homePageEvents->the_post();
//
//                get_template_part('template-parts/content', 'event');
//            endwhile;
//            wp_reset_postdata(); } ?>

    </div>

    <?php
} get_footer();

?>
