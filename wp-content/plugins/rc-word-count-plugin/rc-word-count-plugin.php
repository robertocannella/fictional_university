<?php

/*
 * Plugin Name: RC WordCount Plugin
 * Version: 0.0.1
 * Description: WordCount plugin
 * Author: Roberto Cannella
 * Author URI: https://wordpress.robertocannella.com
  Text Domain: wcpdomain
  Domain Path: /languages
 */

class RCWordCount{
    public function __construct(){
        add_action('admin_menu',[$this,'adminPage']);
        add_action('admin_init',[$this,'settings']);
       // add_filter('admin_footer_text', [$this,'rcFooterText']);
        add_filter('the_content',[$this,'ifWrap']);
        add_action('init',[$this,'languages']);
        add_action('wp_enqueue_scripts', [$this, 'rcScriptLoader']);
    }
    function rcScriptLoader(){
        //wp_die(dirname(plugin_basename(__FILE__)) . '/rc-word-count-plugin.js');
        wp_enqueue_script( 'wcp_wordcount-js', plugins_url( '/rc-word-count-plugin.js', __FILE__ ));
        wp_localize_script('wcp_wordcount-js','globalSite', [
            'donkey' => get_site_url()
        ]);
    }

    function languages() {

        load_plugin_textdomain('wcpdomain', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    function ifWrap($content) {
        if (is_main_query() AND is_single() AND
            (
                get_option('wcp_wordcount', '1') OR
                get_option('wcp_charcount', '1') OR
                get_option('wcp_readtime', '1')
            )) {
            return $this->createHTML($content);
        }
        return $content;
    }


    function createHTML($content) {
        $html = '<h3>' . esc_html(get_option('wcp_headline', 'Post Statistics')) . '</h3><p>';

        // get word count once because both wordcount and read time will need it.
        if (get_option('wcp_wordcount', '1') OR get_option('wcp_readtime', '1')) {
            $wordCount = str_word_count(strip_tags($content));
        }

        if (get_option('wcp_wordcount', '1')) {
            $html .= esc_html__('This post has','wcpdomain') . ' ' . $wordCount . ' ' .  __('words','wcpdomain') . '.<br>';
        }

        if (get_option('wcp_charcount', '1')) {
            $html .= 'This post has ' . strlen(strip_tags($content)) . ' characters.<br>';
        }

        if (get_option('wcp_readtime', '1')) {
            $html .= 'This post will take about ' . round($wordCount/225) . ' minute(s) to read.<br>';
        }

        $html .= '</p>';

        if (get_option('wcp_location', '0') == '0') {
            return $html . $content;
        }
        return $content . $html;

    }
    function settings() {
        // Create Section
        add_settings_section('wcp_first_section',
            null,
            null,
            'word-count-settings-page');

        // Register Word Count Display Location
        add_settings_field('wcp_location', 'Display Location', [$this,'locationHTML'], 'word-count-settings-page', 'wcp_first_section');
        register_setting('wordcountplugin', 'wcp_location', ['sanitize_callback' => [$this,'sanitizeLocation'], 'default' => '0']);

        // Register Word Count Headline
        add_settings_field('wcp_headline', 'Headline', [$this,'headlineHTML'], 'word-count-settings-page', 'wcp_first_section');
        register_setting('wordcountplugin', 'wcp_headline', ['sanitize_callback' => 'sanitize_text_field', 'default' => 'Post Statistics']);

        // Register Show Word Count
        add_settings_field('wcp_wordcount', 'Word Count',[$this,'checkboxHTML'], 'word-count-settings-page', 'wcp_first_section',['theName' => 'wcp_wordcount']);
        register_setting('wordcountplugin', 'wcp_wordcount', ['sanitize_callback' => 'sanitize_text_field', 'default' => '1']);

        // Register Show Character Count
        add_settings_field('wcp_charcount', 'Character Count',[$this,'checkboxHTML'], 'word-count-settings-page', 'wcp_first_section',['theName' => 'wcp_charcount']);
        register_setting('wordcountplugin', 'wcp_charcount', ['sanitize_callback' => 'sanitize_text_field', 'default' => '1']);
        // Register Show Read Time
        add_settings_field('wcp_readtime', 'Read Time',[$this,'checkboxHTML'], 'word-count-settings-page', 'wcp_first_section',['theName' => 'wcp_readtime']);
        register_setting('wordcountplugin', 'wcp_readtime', ['sanitize_callback' => 'sanitize_text_field', 'default' => '1']);

    }

    function sanitizeLocation($input){
        if($input != 0 AND $input !=1 ){
            add_settings_error('wcp_location','wcp_location_error','Display location must be beginning or end');
            return get_option('wcp_location');
        }
        return $input;
    }

    function checkboxHTML($args){?>
        <input type="checkbox" name="<?php echo $args['theName']?>" value="1" <?php checked(get_option(esc_attr($args['theName']),'1')) ?> >
    <?php
    }
    function headlineHTML(){ ?>
        <input name="wcp_headline" value="<?php echo  esc_attr(get_option('wcp_headline','Post Statistics')) ?>"/>
        <?php
    }


    function locationHTML(){ ?>
        <select name="wcp_location" >
            <option value="0" <?php selected(get_option('wcp_location','0')) ?>> Beginning of Post</option>
            <option value="1" <?php selected(get_option('wcp_location','1')) ?>>End of Post</option>
        </select>
        <?php
    }
    function wordCountHTML(){
        ?>
        <div class="wrap">
            <h1>Word Count Settings</h1>
            <form action="options.php" method="post">
                <?php
                    settings_fields('wordcountplugin');
                    do_settings_sections('word-count-settings-page');
                    submit_button();
                ?>
            </form>
        </div>
        <?php
    }
    function adminPage(){
        add_options_page(
            'Word Count Settings',
            esc_html__('Word Count', 'wcpdomain'),
            'manage_options',
            'word-count-settings-page',
            [$this,'wordCountHTML']

        );
    }
    // Admin footer modification
    function rcFooterText ()
    {
        echo '<p id="footer-thankyou">Developed by <a href="https://www.robertocannella.com" target="_blank">Roberto cannella</a></p>';
        echo '<p>RC Globals Activated</p>';
    }
}

$rcWordCount = new RCWordCount();















