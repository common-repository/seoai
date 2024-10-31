<?php

if (!defined('WPINC')) {
    die;
}

add_action('wp_ajax_seoai_spin_content', 'seoai_spin_content');
add_action('wp_ajax_nopriv_seoai_spin_content', 'seoai_spin_content');
function seoai_spin_content()
{
    $apiKey = get_option('seoai_option_apikey');
    $id = sanitize_text_field($_POST['id']);
    $id = intval($id);
    if (isset($id)) {
        $content = get_post_field('post_content', $id);
        $args = array(
            'timeout'     => 300,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking'    => true,
            'headers'     => array(
                'apiKey' => $apiKey
            ),
            'body'        => array(
                'text' => $content,
            ),
            'cookies'     => array()
        );

        $response = wp_remote_post(SEO_AI_API.'/spin', $args);
        if(!isset($response['body'])){
            echo json_encode(array(
                'status'    => 'error',
                'msg'       => 'Không lấy được dữ liệu Spin từ API'
            ));
            exit();
        }
        $data = json_decode($response['body']);

        if ($data->status == 'success') {
            $newSpined = $data->data;
            $spined = get_post_meta($id, 'spined_txt', true);
            if (isset($spined) && $spined != '') {
                update_post_meta($id, 'spined_txt', $newSpined);
                echo json_encode(array(
                    'status'    => 'success',
                    'msg'       => 'Đã cập nhật Content Spin mới'
                ));
            } else {
                add_post_meta($id, 'spined_txt', $newSpined);
                echo json_encode(array(
                    'status'    => 'success',
                    'msg'       => 'Đã thêm mới thành công'
                ));
            }
        }
        exit();
    }
}

// check api key
add_action('wp_ajax_seoai_check_apikey', 'seoai_check_apikey');
add_action('wp_ajax_nopriv_seoai_check_apikey', 'seoai_check_apikey');
function seoai_check_apikey()
{
    $apikey = sanitize_text_field($_POST['apikey']);
    $apikey = htmlentities($apikey);
    if (isset($apikey)) {
        $args = array(
            'timeout'     => 300,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking'    => true,
            'headers'     => array(
                'apiKey'    => $apikey
            ),
            'cookies'     => array()
        );
        

        $response = wp_remote_post(SEO_AI_API.'/apikey', $args);
        if(!isset($response['body'])){
            echo json_encode(array(
                'status'    => 'error',
                'msg'       => 'Không kiểm tra được, vui lòng thử lại'
            ));
            exit();
        }
        echo $response['body'];
        exit();
    }
}