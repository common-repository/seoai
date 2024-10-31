<?php
/*
Plugin Name: SEO AI
Plugin URI: https://seoai.vn
Description: SEO AI - Xây dựng nội dung SEO vô tận, phát triển hệ thống vệ tinh SEO cực chất
Version: 1.0.1
Author URI: https://app.seoai.vn
*/
if (!defined('WPINC')) {
	die;
}

define('SEO_AI_INC', dirname(__FILE__) . '/inc/');
define('SEO_AI_IMG', plugins_url('assets/images/', __FILE__));
define('SEO_AI_CSS', plugins_url('assets/css/', __FILE__));
define('SEO_AI_JS', plugins_url('assets/js/', __FILE__));
define('SEO_AI_API', 'https://app.seoai.vn/wordpress');
@ini_set('max_input_vars', 3000);

register_activation_hook( __FILE__, 'seoai_plugin_activate' );
function seoai_plugin_activate() {
    update_option('seoai_option_editor', 1 );
    update_option('seoai_option_content', 1 );
    update_option('seoai_option_saveimage', 1 );
    update_option('seoai_option_featuredimage', 1 );
    update_option('seoai_option_spin', 1 );
}

add_action('admin_enqueue_scripts', function () {
    wp_enqueue_script('toastr', SEO_AI_JS . 'toastr.min.js');
    wp_enqueue_script('seoai-core', SEO_AI_JS . 'seoai-core.js');
});

function seoai_admin_style()
{
    wp_enqueue_style('seoai-styles', SEO_AI_CSS . '/core.css');
    wp_enqueue_style('toastr-styles', SEO_AI_CSS . '/toastr.min.css');
}
add_action('admin_enqueue_scripts', 'seoai_admin_style');


if (file_exists(SEO_AI_INC . 'auto-save-image.php')) {
    require_once SEO_AI_INC . 'auto-save-image.php';
}

if (file_exists(SEO_AI_INC . 'ajax-request.php')) {
    require_once SEO_AI_INC . 'ajax-request.php';
}

if (file_exists(SEO_AI_INC . 'function.php')) {
    require_once SEO_AI_INC . 'function.php';
}
if (file_exists(SEO_AI_INC . 'option-panel.php')) {
    require_once SEO_AI_INC . 'option-panel.php';
}

if (file_exists(SEO_AI_INC . 'seoai-panel.php')) {
    require_once SEO_AI_INC . 'seoai-panel.php';
}