<?php
if (!defined('WPINC')) {
    die;
}

// Đưa về Classic Editor
if (intval(get_option('seoai_option_editor')) == 1) {
    add_filter('use_block_editor_for_post', '__return_false', 10);
}

if (intval(get_option('seoai_option_content')) == 1) {
    add_filter('the_content', 'seoai_hack_google', -1);
}

if (intval(get_option('seoai_option_featuredimage')) == 1) {
    add_action('the_post', 'seoai_default_thumb');
}

if (intval(get_option('seoai_option_featuredimage')) == 0) {
    add_action('the_post', 'seoai_default_thumb_without_image');
}

if (intval(get_option('seoai_option_spin')) == 1) {
    add_action('manage_posts_custom_column', 'seoai_display_posts_spin', 10, 2);
    add_filter('manage_posts_columns', 'seoai_add_spin_column');
}

if (intval(get_option('seoai_option_audit')) == 1) {
    add_filter('the_content', 'seoai_optimize_content');
}


function seoai_hack_google($content)
{
    if (is_single()) {
        global $post;
        $newcontent = get_post_meta($post->ID, 'spined_txt', true);
        if (isset($newcontent) && $newcontent != '') {
            $browser = $_SERVER['HTTP_USER_AGENT'];
            if (preg_match('/BOT|Bot|bot|http|GOOGLE|Google|google|ahrefs/', $browser)) {
                $content = $newcontent;
            }
        }
    }
    return $content;
}

add_action("rest_insert_post", function (\WP_Post $post, $request, $creating) {
    $metas = $request->get_param("meta");
    if (is_array($metas)) {
        foreach ($metas as $name => $value) {
            add_post_meta($post->ID, $name, $value);
        }
    }
    $tags = $request->get_param("meta_tags");
    $tags = explode(',', $tags);
    if (count($tags) > 0) {
        foreach ($tags as $tag) {
            wp_set_post_tags($post->ID, $tag, true);
        }
    }
}, 10, 3);


function seoai_default_thumb()
{
    global $post;
    if ($post->post_status == 'publish') {
        $featured_image_exists = has_post_thumbnail($post->ID);
        if (!$featured_image_exists) {
            $attached_image = get_children("post_parent=$post->ID&post_type=attachment&post_mime_type=image&numberposts=1");
            if ($attached_image) {
                foreach ($attached_image as $attachment_id => $attachment) {
                    set_post_thumbnail($post->ID, $attachment_id);
                }
            } else {
                $default_thumbnail = get_option('seoai_option_default_featured');
                if (intval($default_thumbnail) != 0)
                    set_post_thumbnail($post->ID, $default_thumbnail);
            }
        }
    }
}

function seoai_default_thumb_without_image()
{
    global $post;
    if ($post->post_status == 'publish') {
        $default_thumbnail = get_option('seoai_option_default_featured');
        if (intval($default_thumbnail) != 0)
            set_post_thumbnail($post->ID, $default_thumbnail);
    }
}

function seoai_display_posts_spin($column, $post_id)
{
    if ($column == 'spin') {
        $spined = get_post_meta($post_id, 'spined_txt', true);
        $checked = '';
        echo  '<div class="spin-action"><button class="btn-spin" type="button" onclick="seoai_spin(' . sanitize_text_field($post_id) . ')">Spin</button>';
        if (isset($spined) && $spined != '') {
            $checked = 'icon-spined';
        }
        echo  '<i class="icon-status ' . sanitize_text_field($checked) . '" id="icon-status-' . sanitize_text_field($post_id) . '"></i></div>';
    }
}

function seoai_add_spin_column($columns)
{
    return array_merge(
        $columns,
        array('spin' => __('Spin', 'seoai'))
    );
}

function seoai_optimize_content($content)
{
    $args = array(
        'type'                     => 'post',
        'hide_empty'               => 0,
    );
    $categories = get_categories($args);

    foreach ($categories as $category) {
        $name_lower = strtolower($category->name);
        $name_upper = strtoupper($category->name);
        $name_ucfirst = ucfirst($category->name);
        $name_ucwords = ucwords($category->name);
        $content = preg_replace("/($name_lower|$name_upper|$name_ucfirst|$name_ucwords)/", '<a href="'.get_category_link($category->term_id).'" target="_blank" title="' . $category->name.'">$1</a>', $content, 1);
    }
    return $content;
}
