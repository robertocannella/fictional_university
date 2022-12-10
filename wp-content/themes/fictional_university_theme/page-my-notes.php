<?php
/**
 * 'Page template'
 *
 * @package 'fictional_university'
 */
if (!is_user_logged_in()){
    wp_redirect(esc_url(site_url('/')));
    exit;
}
get_header();


while (have_posts()){
    the_post();
    pageBanner();
    ?>


    <div class="container container--narrow page-section">
       <ul class="min-list link-list" id="my-notes">
           <?php
           $userNotes = new WP_Query([
               'post_type' => 'note',
               'post_per_page' => -1,
               'author' => get_current_user_id()
           ]);
           while ($userNotes->have_posts()){
                $userNotes->the_post();
                ?>
               <li>
                   <input class="note-title-field"type="text" value="<?php echo esc_attr(wp_strip_all_tags(get_the_title())); ?>">
                   <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
                   <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
                   <textarea class="note-body-field" name="" id="" cols="30" rows="10"><?php echo esc_attr(wp_strip_all_tags(get_the_content())); ?></textarea>
               </li>
               <?php
           }
           ?>
       </ul>
    </div>

    <?php
}
get_footer();
?>