<?php
if (!defined('WPINC')) {
    die;
}
class SEOAI_Settings
{
    private $options;

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'register_seoai_settings'));
        $this->get_seoai_options();
    }


    public function add_plugin_page()
    {
        add_options_page(
            'SEO AI',
            'Cấu hình SEO AI',
            'manage_options',
            'seoai_settings',
            array($this, 'seoai_panel_template')
        );
    }

    function register_seoai_settings() {
        register_setting( 'seoai_settings', 'seoai_option_apikey' );
        register_setting( 'seoai_settings', 'seoai_option_content' );
        register_setting( 'seoai_settings', 'seoai_option_editor' );
        register_setting( 'seoai_settings', 'seoai_option_saveimage');
        register_setting( 'seoai_settings', 'seoai_option_featuredimage');
        register_setting( 'seoai_settings', 'seoai_option_default_featured');
        register_setting( 'seoai_settings', 'seoai_option_audit');
        register_setting( 'seoai_settings', 'seoai_option_spin');
    }

    public function seoai_panel_template()
    {
        if (file_exists(SEO_AI_INC . 'template/panel.php')) {
            require_once SEO_AI_INC . 'template/panel.php';
        }
        
    }
    
    function get_seoai_options(){
        $this->seoai_option_apikey      = get_option('seoai_option_apikey');
        $this->seoai_option_editor      = intval(get_option('seoai_option_editor'));
        $this->seoai_option_content     = intval(get_option('seoai_option_content'));
        $this->seoai_option_saveimage   = intval(get_option('seoai_option_saveimage'));
        $this->seoai_option_featuredimage   = intval(get_option('seoai_option_featuredimage'));
        $this->seoai_option_default_featured   = intval(get_option('seoai_option_default_featured'));
        $this->seoai_option_audit   = intval(get_option('seoai_option_audit'));
        $this->seoai_option_spin   = intval(get_option('seoai_option_spin'));
    }
}

if (is_admin())
    new SEOAI_Settings();
