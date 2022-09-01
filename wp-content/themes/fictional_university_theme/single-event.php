<?php
/**
 * Single template.
 *
 * @package 'fictional_university'
 */


get_header();

while (have_posts()){
    the_post();
    pageBanner();
    ?>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('event');?>">
                    <i class="fa fa-home" aria-hidden="true"></i> Events Home
                </a>
                <span class="metabox__main"><?php the_title();?></span>

            </p>
        </div>
        <div class="event-summary">
            <a class="event-summary__date t-center" href="#">
                                <span class="event-summary__month">
                                    <?php
                                    $eventDate = new DateTime(get_field('event_date'));
                                    echo $eventDate->format('M');
                                    ?>
                                </span>
                <span class="event-summary__day"><?php echo $eventDate->format('d');?></span>
            </a>
        </div>
        <div class="generic-content">
            <?php the_content();?>
        </div>

            <?php $relatedPrograms = get_field('related_programs');
            if ($relatedPrograms) :
                echo '<hr class="section-break">';
                echo ' <ul class="link-list min-list">';
                echo '<h2 class="headline headline--medium">Related Program(s)</h2>';
                foreach ($relatedPrograms as $relatedProgram):?>
                    <li><a href="<?php echo get_the_permalink($relatedProgram) ?>"><?php echo get_the_title($relatedProgram) ?></a></li>
                <?php   endforeach; endif;?>
        </ul>
    </div>

    <?php
} get_footer();

?>
