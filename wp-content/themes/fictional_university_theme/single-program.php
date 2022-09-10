<?php
/**
 * Single program template.
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
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program');?>">
                    <i class="fa fa-home" aria-hidden="true"></i> All Programs
                </a>
                <span class="metabox__main"><?php the_title();?></span>
            </p>
        </div>
        <div class="generic-content">
            <?php the_content();?>

        </div>
        <?php
        // PROFESSORS
         $relatedProfessors = new WP_Query([
            'posts_per_page'=>-1,
            'post_type'=>'professor',
            'orderby'=> 'title',
            'order'=>'ASC',
            'meta_query'=>[

                [
                    'key'=>'related_programs',
                    'compare'=>'LIKE',
                    'value'=> ' "'. get_the_ID() .'"' // must be in double quotes here
                ]
            ]
        ]);
        if ($relatedProfessors->have_posts()){

        echo '<hr class="section-break">';
        echo '<h2 class="headline headline--medium"> ' . get_the_title() . ' Professors</h2>';
        echo '<ul class="professor-cards">';
        while ($relatedProfessors->have_posts()): $relatedProfessors->the_post(); ?>
        <li class="professor-card__list-item">
            <a class="professor-card" href="<?php the_permalink(); ?>">
                <img class="professor-card__image" alt="" src="<?php the_post_thumbnail_url('professor-landscape'); ?>">
                <span class="professor-card__name"><?php  the_title() ?></span>
            </a>
        </li>

        <?php
        endwhile;
        echo '</ul>';
        wp_reset_postdata();
        }

        $today = date('Ymd');
        $homePageEvents = new WP_Query([
            'posts_per_page'=>2,
            'post_type'=>'event',
            //'orderby'=> 'title',
            'order'=>'ASC',
            'orderby'=>'meta_value_num', // meta_key required
            'meta_key'=>'event_date',
            'meta_query'=>[
                [
                    'key'=>'event_date',
                    'compare'=>'>=',
                    'value'=>$today,
                    'type'=>'numeric',

                ],
                [
                    'key'=>'related_programs',
                    'compare'=>'LIKE',
                    'value'=> ' "'. get_the_ID() .'"' // must be in double quotes here
                ]
            ]
        ]);
        if ($homePageEvents->have_posts()) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h2>';
            while ($homePageEvents->have_posts()): $homePageEvents->the_post();

                get_template_part('template-parts/content', 'event');


            endwhile;
        }
        wp_reset_postdata();
        $relatedCampuses = get_field('related_campus');
        if ($relatedCampuses){
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">' . get_the_title() .' is available at these Campuses </h2>';   
            echo '<ul class="min-list link-list"';
            foreach ($relatedCampuses as $campus){
                ?>
                <li><a href="<?php echo get_the_permalink($campus);?>"><?php echo get_the_title($campus) ?></a></li>
                <?php
            }
            echo '</ul>';
        }
        ?>

    </div>

    <?php
} get_footer();

?>
