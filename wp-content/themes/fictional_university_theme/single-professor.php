<?php
/**
 * Single professor template.
 *
 * @package 'fictional_university'
 */



get_header();

while (have_posts()){
    the_post();

    pageBanner();
    ?>

    <div class="container container--narrow page-section">

        <div class="generic-content">
           <div class="row group">
               <div class="one-third">
                   <?php the_post_thumbnail('professor-portrait'); ?>
               </div>
               <div class="two-thirds">
                   <?php the_content(); ?>
               </div>
           </div>
        </div>

        <?php
        $today = date('Ymd');
        $relateProfessors = new WP_Query([
            'posts_per_page'=>-1,
            'post_type'=>'event',
            'orderby'=> 'title',
            'order'=>'ASC',
            'meta_key'=>'professor',
            'meta_query'=>[
                [
                    'key'=>'related_programs',
                    'compare'=>'LIKE',
                    'value'=> ' "'. get_the_ID() .'"' // must be in double quotes here
                ]
            ]
        ]);


        if ($relateProfessors->have_posts()){


            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium"> ' . get_the_title() . ' Professors</h2>';
            while ($relateProfessors->have_posts()): $relateProfessors->the_post(); ?>
                <li><a href="<?php the_permalink(); ?>"></a><?php the_title();?></li>

            <?php endwhile; wp_reset_postdata(); } ?>


            <?php $relatedPrograms = get_field('related_programs');
            if ($relatedPrograms) :
                echo '<hr class="section-break">';
                echo ' <ul class="link-list min-list">';
                echo '<h2 class="headline headline--medium">Subject(s) Taught</h2>';
                foreach ($relatedPrograms as $relatedProgram):?>
                    <li><a href="<?php echo get_the_permalink($relatedProgram) ?>"><?php echo get_the_title($relatedProgram) ?></a></li>
                <?php   endforeach; endif;?>
        </ul>
    </div>

    <?php
} get_footer();

?>
