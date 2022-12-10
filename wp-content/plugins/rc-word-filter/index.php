<?php

/*
* Plugin Name: RC WordFilter Plugin
* Version: 0.0.1
* Description: WordFilter plugin
* Author: Roberto Cannella
* Author URI: https://wordpress.robertocannella.com
Text Domain: wfpdomain
Domain Path: /languages

Some cool things I learned making this plugin:

I. Loading Assets
    a.  Both css and js files are loaded using a custom hook
    b.  The css and js will only be loaded within the page
II. PHP RFC: Null Coalesce Operator
    a.  This operator ?? is great for checking a value in an array.
    b.  See the &_Post[] method inside the HTML form below
III. Security/Sanitizing
    a. Escaping HTML can be done with textarea too!
    b. sanitize
IV. WordPress formbuilder
    a. Set form action equal to options.php with method equal to POST
    b. submit_button() is a WP function to handle submit with form builder
    c. use add_settings_section() to add a section inside WP
*/


if(!defined('ABSPATH')) exit; //Exit if accessed directly

class RCWordFilter
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'menu']);
        add_action('admin_init', [$this, 'rcWordFilterSettings']);
        if (get_option('rc-wordfilter-list')) add_filter('the_content',[$this, 'filterLogic']);

    }
    function filterLogic($content){
        $filteredWords = explode (',',get_option('rc-wordfilter-list'));
        $trimmedFilteredWords = array_map('trim',$filteredWords);
        return str_ireplace($trimmedFilteredWords,esc_html(get_option('replacementText'),'****'), $content);
    }

    function menu()
    {
        $mainPageHook =  add_menu_page(
            'Words to filter',
            'Word Filter',
            'manage_options',
            'word-filter-settings-page',
            [$this, 'wordFilterPage'],
            'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMCAyMEMxNS41MjI5IDIwIDIwIDE1LjUyMjkgMjAgMTBDMjAgNC40NzcxNCAxNS41MjI5IDAgMTAgMEM0LjQ3NzE0IDAgMCA0LjQ3NzE0IDAgMTBDMCAxNS41MjI5IDQuNDc3MTQgMjAgMTAgMjBaTTExLjk5IDcuNDQ2NjZMMTAuMDc4MSAxLjU2MjVMOC4xNjYyNiA3LjQ0NjY2SDEuOTc5MjhMNi45ODQ2NSAxMS4wODMzTDUuMDcyNzUgMTYuOTY3NEwxMC4wNzgxIDEzLjMzMDhMMTUuMDgzNSAxNi45Njc0TDEzLjE3MTYgMTEuMDgzM0wxOC4xNzcgNy40NDY2NkgxMS45OVoiIGZpbGw9IiNGRkRGOEQiLz4KPC9zdmc+Cg==',
            '100');
        add_submenu_page('word-filter-settings-page','Words To Filter','Words List','manage_options','word-filter-settings-page',[$this,'wordFilterPage']);
        add_submenu_page('word-filter-settings-page','Word Filter Options','Options','manage_options','word-filter-options',[$this,'optionsSubPage']);
        add_action("load-{$mainPageHook}",[$this,'mainPageAssets']);
    }
    function mainPageAssets(){
        wp_enqueue_script('rc-wordfilter-js',plugin_dir_url(__FILE__) . '/js/main.js');
        wp_enqueue_style('rc-wordfilter-css',plugin_dir_url(__FILE__) . 'styles.css');
    }
    function wordFilterPage(){
        ?>
        <div class="wrap">
            <h1>Site Wide Word Filter</h1>
            <?php  if ($_POST['justsubmitted'] ?? 'false' == 'true') $this->handleForm() ?>
            <form method="post">
                <?php wp_nonce_field('saveWordFilters','rcNonce') ?>
                <input type="hidden" name="justsubmitted" value="true">
                <label for="rc-wordfilter">
                    <p>Enter a comma separated list of words to filter from the site</p>
                </label>
                <div class="word_filter__flex_container">
                    <textarea name="rc-wordfilter-list" id="rc-wordfilter" placeholder="bad, mean, awful"><?php echo esc_textarea(get_option('rc-wordfilter-list')) ?></textarea>
                </div>
                <input type="submit" name="submit" class="button button-primary" value="Save Changes">
            </form>
        </div>
        <?php
    }
    function handleForm(){
        if (wp_verify_nonce($_POST['rcNonce'] ?? false,'saveWordFilters') AND current_user_can('manage_options')){
            // save into the options database
            update_option('rc-wordfilter-list',sanitize_textarea_field($_POST['rc-wordfilter-list'] ?? get_option('rc-wordfilter-list'),''));
            ?>
            <div class="updated">
                <p>Your filtered words were saved!</p>
            </div>
            <?php
        }else{?>
        <div class="error">
            <p>Sorry, you do not have permission to perform that action.</p>
        </div>
        <?php
        }
    }

    function rcWordFilterSettings() {
        add_settings_section('replacement-text-section', null, null, 'word-filter-options');
        register_setting('replacementFields', 'replacementText');
        add_settings_field('replacementText', 'Filtered Text', array($this, 'replacementFieldHTML'), 'word-filter-options', 'replacement-text-section');
    }

    function replacementFieldHTML() { ?>
        <input type="text" name="replacementText" value="<?php echo esc_attr(get_option('replacementText', '***')) ?>">
        <p class="description">Leave blank to simply remove the filtered words.</p>
    <?php }


    function optionsSubPage() { ?>
        <div class="wrap">
            <h1>Word Filter Options</h1>
            <form action="options.php" method="POST">
                <?php
                settings_errors();
                settings_fields('replacementFields');
                do_settings_sections('word-filter-options');
                submit_button();
                ?>
            </form>
        </div>
    <?php }
}
$rcWordFilter = new RCWordFilter();