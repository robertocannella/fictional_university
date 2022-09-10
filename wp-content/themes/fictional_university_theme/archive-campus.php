<?php
/**
 *  Campus archive template.
 *
 * @package 'fictional_university'
 */


get_header();
pageBanner(
    $title ='Our Campuses',
    $subtitle = 'We have several conveniently located campuses!'
);
?>


    <div class="container container--narrow page-section">

            <div class="acf-map">
            <?php while (have_posts()) : the_post();
            $map_location = get_field('map_location')
            ?>

            <div class="marker" data-lat="<?php echo $map_location['lat']; ?>" data-lng="<?php echo $map_location['lng'] ?>">
                <h3><a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                    </a>
                </h3>

                <?php echo $map_location['address']; ?>
            </div>

            <?php endwhile; ?>
        </div>
    </div>
<?php get_footer(); ?>