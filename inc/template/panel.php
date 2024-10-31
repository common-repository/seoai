<?php
if (isset($_POST['submit_image_selector']) && isset($_POST['image_attachment_id'])) :
    update_option('seoai_option_default_featured', absint($_POST['image_attachment_id']));
endif;
wp_enqueue_media();
$seoai_option_featuredimage = get_option('seoai_option_default_featured', 0);
?>
<section class="content-main" id="seoai-main">
    <form action="options.php" method="post" accept-charset="utf-8" id="seoai-form">
        <?php settings_fields('seoai_settings'); ?>
        <div class="content-header">
            <div>
                <h2 class="content-title">Cấu hình SEO AI</h2>
            </div>
            <div>
                <?php submit_button(); ?>
            </div>
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>Cấu hình nội dung</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-4 row-input">
                                <label for="">API Key (<a href="https://app.seoai.vn/profile/api" target="_blank">lấy tại đây</a>)</label>
                                <div class="input-group">
                                    <input type="text" class="input-seoai" id="apikey" name="seoai_option_apikey" value="<?php echo $this->seoai_option_apikey ?>" placeholder="Nhập API Key">
                                    <button class="btn btn-light bg" type="button" onclick="checkApiKey();"> Check</button>
                                </div>
                            </div>
                            <div class="mb-4 row-input">
                                <label for="">Bật Classic editor</label>
                                <fieldset class="btn-group-yesno">
                                    <input type="radio" id="seoai_option_editor_on" name="seoai_option_editor" value="1" <?php if ($this->seoai_option_editor == 1) echo 'checked' ?>>
                                    <label for="seoai_option_editor_on">Bật</label>
                                    <input type="radio" id="seoai_option_editor_off" name="seoai_option_editor" value="0" <?php if ($this->seoai_option_editor == 0) echo 'checked' ?>>
                                    <label for="seoai_option_editor_off">Tắt</label>
                                </fieldset>
                            </div>
                            <div class="mb-4 row-input">
                                <label for="">Ai sẽ xem nội dung đã spin</label>
                                <fieldset class="btn-group-yesno">
                                    <input type="radio" id="seoai_option_content_bot" name="seoai_option_content" value="1" <?php if ($this->seoai_option_content == 1) echo 'checked' ?>>
                                    <label for="seoai_option_content_bot">BOT</label>
                                    <input type="radio" id="seoai_option_content_user" name="seoai_option_content" value="0" <?php if ($this->seoai_option_content == 0) echo 'checked' ?>>
                                    <label for="seoai_option_content_user">Tất cả</label>
                                </fieldset>
                            </div>
                            <div class="mb-4 row-input">
                                <label for="">Tự động tối ưu bài viết</label>
                                <fieldset class="btn-group-yesno">
                                    <input type="radio" id="seoai_option_audit_on" name="seoai_option_audit" value="1" <?php if ($this->seoai_option_audit == 1) echo 'checked' ?>>
                                    <label for="seoai_option_audit_on">Bật</label>
                                    <input type="radio" id="seoai_option_audit_off" name="seoai_option_audit" value="0" <?php if ($this->seoai_option_audit == 0) echo 'checked' ?>>
                                    <label for="seoai_option_audit_off">Tắt</label>
                                </fieldset>
                            </div>
                            <div class="mb-4 row-input">
                                <label for="">Bật spin bài viết</label>
                                <fieldset class="btn-group-yesno">
                                    <input type="radio" id="seoai_option_spin_on" name="seoai_option_spin" value="1" <?php if ($this->seoai_option_spin == 1) echo 'checked' ?>>
                                    <label for="seoai_option_spin_on">Bật</label>
                                    <input type="radio" id="seoai_option_spin_off" name="seoai_option_spin" value="0" <?php if ($this->seoai_option_spin == 0) echo 'checked' ?>>
                                    <label for="seoai_option_spin_off">Tắt</label>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>Hình ảnh</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-4 row-input">
                                <label for="">Lưu hình ảnh về hosting (chỉ khi công khai bài viết)</label>
                                <fieldset class="btn-group-yesno">
                                    <input type="radio" id="seoai_option_saveimage_on" name="seoai_option_saveimage" value="1" <?php if ($this->seoai_option_saveimage == 1) echo 'checked' ?>>
                                    <label for="seoai_option_saveimage_on">Bật</label>
                                    <input type="radio" id="seoai_option_saveimage_off" name="seoai_option_saveimage" value="0" <?php if ($this->seoai_option_saveimage == 0) echo 'checked' ?>>
                                    <label for="seoai_option_saveimage_off">Tắt</label>
                                </fieldset>
                            </div>
                            <div class="mb-4 row-input" id="row-featured" <?php if ($this->seoai_option_saveimage == 0) echo 'style="display: none"' ?>>
                                <label for="">Đặt ảnh đầu tiên làm hình đại diện (nếu không chọn hình lúc đăng)</label>
                                <fieldset class="btn-group-yesno">
                                    <input type="radio" id="seoai_option_featuredimage_on" name="seoai_option_featuredimage" value="1" <?php if ($this->seoai_option_featuredimage == 1) echo 'checked' ?>>
                                    <label for="seoai_option_featuredimage_on">Bật</label>
                                    <input type="radio" id="seoai_option_featuredimage_off" name="seoai_option_featuredimage" value="0" <?php if ($this->seoai_option_featuredimage == 0) echo 'checked' ?>>
                                    <label for="seoai_option_featuredimage_off">Tắt</label>
                                </fieldset>
                            </div>
                            <div class="mb-4 row-input" id="row-default-featured">
                                <label for="">Chọn ảnh đại diện mặc định khi bài không có ảnh</label>
                                <div>
                                    <div class='image-preview-wrapper'>
                                        <img id='image-preview' src='<?php echo wp_get_attachment_url(get_option('seoai_option_default_featured')); ?>' height='100'>
                                    </div>
                                    <input id="upload_image_button" type="button" class="button" value="Chọn hình ảnh" />
                                    <input type='hidden' name='seoai_option_default_featured' id='image_attachment_id' value='<?php echo get_option('seoai_option_default_featured'); ?>'>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>