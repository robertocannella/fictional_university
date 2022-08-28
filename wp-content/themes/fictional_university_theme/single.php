<?php
/**
 * 'Single post template'
 *
 * @package 'fictional_university'
 */

while (have_posts()){
    the_post();
    ?>

    <h2><?php the_title(); ?></h2>
    <?php the_content(); ?>

    <?php
}
?>