<?php
namespace common\assets;

class FroalaAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@common/plugins/froala_editor_3.2.1/';

    public $css = [
        "css/froala_editor.css",
        "css/froala_style.css",
        "css/plugins/code_view.css",
        "css/plugins/draggable.css",
        "css/plugins/colors.css",
        "css/plugins/emoticons.css",
        "css/plugins/image_manager.css",
        "css/plugins/image.css",
        "css/plugins/line_breaker.css",
        "css/plugins/table.css",
        "css/plugins/char_counter.css",
        "css/plugins/video.css",
        "css/plugins/fullscreen.css",
        "css/plugins/file.css",
        "css/plugins/quick_insert.css",
        "css/plugins/help.css",
        "css/third_party/spell_checker.css",
        "css/plugins/special_characters.css",
    ];

    public $js = [
        "js/froala_editor.min.js",
        "js/plugins/align.min.js",
        "js/plugins/char_counter.min.js",
        "js/plugins/code_beautifier.min.js",
        "js/plugins/code_view.min.js",
        "js/plugins/colors.min.js",
        "js/plugins/draggable.min.js",
        "js/plugins/emoticons.min.js",
        "js/plugins/entities.min.js",
        "js/plugins/file.min.js",
        "js/plugins/font_size.min.js",
        "js/plugins/font_family.min.js",
        "js/plugins/fullscreen.min.js",
        "js/plugins/image.js",
        "js/plugins/image_manager.min.js",
        "js/plugins/line_breaker.min.js",
        "js/plugins/inline_style.min.js",
        "js/plugins/link.min.js",
        "js/plugins/lists.min.js",
        "js/plugins/paragraph_format.min.js",
        "js/plugins/paragraph_style.min.js",
        "js/plugins/quick_insert.min.js",
        "js/plugins/quote.min.js",
        "js/plugins/table.min.js",
        "js/plugins/save.min.js",
        "js/plugins/url.min.js",
        "js/plugins/video.min.js",
        "js/plugins/help.min.js",
        "js/plugins/print.min.js",
        "js/third_party/spell_checker.min.js",
        "js/plugins/special_characters.min.js",
        "js/plugins/word_paste.min.js",
    ];

    public $depends = [
    ];
}