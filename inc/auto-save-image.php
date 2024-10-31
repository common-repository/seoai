<?php
if (!defined('WPINC')) {
    die;
}

class SEOAI_Save_Images
{

    function __construct()
    {
        add_filter('content_save_pre', array($this, 'post_save_images'));
    }

    function post_save_images($content)
    {
        if (isset($_POST['publish'])) {
            set_time_limit(240);
            global $post;
            $post_id = $post->ID;
            $preg = preg_match_all('/<img.*?src="(.*?)"/', stripslashes($content), $matches);
            if ($preg) {
                foreach ($matches[1] as $image_url) {
                    if (empty($image_url)) continue;
                    $pos = strpos($image_url, $_SERVER['HTTP_HOST']);
                    if ($pos === false) {
                        $res = $this->save_images($image_url, $post_id);
                        $replace = $res['url'];
                        $content = str_replace($image_url, $replace, $content);
                    }
                }
            }
        }
        if (isset($_POST['save'])) {
            set_time_limit(240);
            global $post;
            $post_id = $post->ID;
            if ($post->post_status == 'publish') {
                $preg = preg_match_all('/<img.*?src="(.*?)"/', stripslashes($content), $matches);
                if ($preg) {
                    foreach ($matches[1] as $image_url) {
                        if (empty($image_url)) continue;
                        $pos = strpos($image_url, $_SERVER['HTTP_HOST']);
                        if ($pos === false) {
                            $res = $this->save_images($image_url, $post_id);
                            $replace = $res['url'];
                            $content = str_replace($image_url, $replace, $content);
                        }
                    }
                }
            }
        }

        remove_filter('content_save_pre', array($this, 'post_save_images'));
        return $content;
    }

    function save_images($image_url, $post_id)
    {
        preg_match( '/[^\?]+\.(jpg|jpe|jpeg|gif|png|webp)/i', $image_url, $matches );
        if(!isset($matches[0])){
            return false;
        }
//         $result = @getimagesize($image_url);
//         if(!is_array($result)){
//             return false;
//         }
        $res = wp_remote_get($image_url);
        if(isset($res->errors)){
            return false;
        }
        $file = $res['body'];
        $post = get_post($post_id);
        $posttitle = $post->post_title;
        $postname = sanitize_title($posttitle);
        $im_name = "$postname-$post_id.jpg";
        $res = wp_upload_bits($im_name, '', $file);
        $this->insert_attachment($res['file'], $post_id);
        return $res;
    }

    function insert_attachment($file, $id)
    {
        $dirs = wp_upload_dir();
        $filetype = wp_check_filetype($file);
        $attachment = array(
            'guid' => $dirs['baseurl'] . '/' . _wp_relative_upload_path($file),
            'post_mime_type' => $filetype['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', basename($file)),
            'post_content' => '',
            'post_status' => 'inherit'
        );
        $result = @getimagesize($attachment['guid']);
        if(!is_array($result)){
            return false;
        }
        $attach_id = wp_insert_attachment($attachment, $file, $id);
        $attach_data = wp_generate_attachment_metadata($attach_id, $file);
        wp_update_attachment_metadata($attach_id, $attach_data);
            
        return $attach_id;
    }
}
if (intval(get_option('seoai_option_saveimage')) == 1) {
    new SEOAI_Save_Images();
}
