<?php

/*
* Plugin Name: RC Are you paying attention?
* Version: 0.0.1
* Description: Quiz plugin
* Author: Roberto Cannella
* Author URI: https://wordpress.robertocannella.com

*/


if(!defined('ABSPATH')) exit; //Exit if accessed directly

class AreYouPayingAttention{
    public function __construct()
    {
        add_action('init',[$this, 'adminAssets']);
    }
    function adminAssets(){
        register_block_type(__DIR__,[
            'render_callback' => [$this, 'theHtml']
        ]);
    }
    function theHtml($attributes):string{
        if (!is_admin()){
            wp_enqueue_script('attentionFrontend', plugin_dir_url(__FILE__) . 'build/frontend.js', array('wp-element'), '1.0', true);
        }
        ob_start(); ?>
            <div class="paying-attention-update-me">
           <pre style="display: none;"><?php echo  wp_json_encode($attributes)?></pre>
            </div>
        <?php

        return ob_get_clean();
    }
}
$areYouPayingAttention = new AreYouPayingAttention();