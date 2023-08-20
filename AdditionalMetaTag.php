<?php
/*
Plugin Name: Additional Meta Tag
Description: このプラグインはすべてのページにカスタムメタタグを追加します。
Version: 1.0
Author: Mr. Tsutsumi
Author URI: https://github.com/titanvortex
*/

// カスタムメタタグを出力する処理
function additional_meta_tag_output() {
    $meta_tag = get_option('additional_meta_tag');
    if ($meta_tag && preg_match('/^<[^>]+>$/', $meta_tag)) {
        echo $meta_tag . "\n";
    }
}
add_action('wp_head', 'additional_meta_tag_output');

// 設定画面を追加する処理
function register_additional_meta_tag_settings() {
    register_setting('additional-meta-tag-group', 'additional_meta_tag');
}
add_action('admin_init', 'register_additional_meta_tag_settings');

// 設定画面の表示処理
function additional_meta_tag_settings_page() {
    $current_lang = get_locale(); // 現在の言語を取得
    
    if ($current_lang === 'ja') {
        $meta_tag_label = 'メタタグ（1行）';
        $meta_tag_description = 'すべてのページに追加するメタタグの1行を入力してください。';
        $save_button_text = '保存';
    } else {
        $meta_tag_label = 'Meta Tag (1 line)';
        $meta_tag_description = 'Enter a single line of meta tag that you want to add to all pages.';
        $save_button_text = 'Save';
    }
?>
    <div class="wrap">
        <h1><?php _e('Additional Meta Tag Settings', 'additional-meta-tag'); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields('additional-meta-tag-group'); ?>
            <?php do_settings_sections('additional-meta-tag-group'); ?>
            <label for="additional_meta_tag"><?php echo $meta_tag_label; ?></label>
            <input type="text" name="additional_meta_tag" value="<?php echo esc_attr(get_option('additional_meta_tag')); ?>" size="50" />
            <?php submit_button($save_button_text); ?>
        </form>
        <?php
        if (isset($_GET['settings-updated']) && $_GET['settings-updated'] !== 'true') {
            echo '<div id="message" class="error notice is-dismissible"><p>Failed to save settings. Please try again.</p></div>';
        }
        ?>
    </div>
<?php
}

// 設定メニューに追加する処理
function additional_meta_tag_menu() {
    add_options_page(
        __('Additional Meta Tag Settings', 'additional-meta-tag'),
        __('Additional Meta Tag', 'additional-meta-tag'),
        'manage_options',
        'additional-meta-tag',
        'additional_meta_tag_settings_page'
    );
}
add_action('admin_menu', 'additional_meta_tag_menu');
