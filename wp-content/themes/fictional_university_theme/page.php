<?php
/**
 * 'Page template'
 *
 * @package 'fictional_university'
 */
get_header();

while (have_posts()){
    the_post();
    pageBanner();
    ?>


    <div class="container container--narrow page-section">
        <?php
        $parent_id = wp_get_post_parent_id(get_the_ID());
        if ($parent_id ){
            ?>
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p>
                    <a class="metabox__blog-home-link" href="<?php echo get_permalink($parent_id)?>"><i class="fa fa-home" aria-hidden="true"></i> <?php echo get_the_title($parent_id);?></a> <span class="metabox__main"><?php  the_title();?></span>
                </p>
            </div>
            <?php
        }

        $pageTest = get_pages([
                'child_of'=> get_the_ID()
        ]);

        if ($parent_id || $pageTest){ ?>
        <div class="page-links">
            <h2 class="page-links__title"><a href="<?php echo get_permalink($parent_id)?>"><?php echo  get_the_title($parent_id)?></a></h2>
            <ul class="min-list">
            <?php
            if ($parent_id){
                $findChildrenOf = $parent_id;
            }else {
                $findChildrenOf = get_the_ID();
            }
            wp_list_pages([
                    'title_li'=>NULL,
                    'child_of'=> $findChildrenOf,
                    'sort_column'=>'menu_order'
            ]);
            ?>
            </ul>
        </div>
        <?php   }?>

        <div class="generic-content">
            <?php the_content();?>
        </div>
    </div>

    <?php
}
get_footer();
?>