<?php

namespace panix\mod\forum\components;

use panix\ext\tinymce\TinyMce as BaseTinyMce;

/**
 * languages https://www.tinymce.com/download/language-packages/
 */
class TinyMce extends BaseTinyMce
{

    public function init()
    {
        parent::init();
        $this->clientOptions['external_plugins'] = [
            "mybbcode" => $this->assetsPlugins[1] . "/mybbcode/plugin.js",
        ];
        $this->clientOptions['statusbar'] = false;
        $this->clientOptions['menubar'] = true;
        $this->clientOptions['plugins'] = [
            "mybbcode textcolor autoresize advlist autolink lists link image charmap anchor",
            "visualblocks code textcolor",
            "paste",
        ];
        $this->clientOptions['bbcode_dialect'] = 'punbb';
        $this->clientOptions['fontsize_formats'] = "8pt 8.25pt 8.5pt 8.75pt 9pt 9.25pt 9.5pt 10pt 11pt 12pt 14pt 18pt 24pt 36pt";
        $this->clientOptions['font_formats'] = 'Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats';
        $this->clientOptions['contextmenu'] = "link image";
        $this->clientOptions['toolbar'] = "fontselect fontsizeselect | forecolor backcolor | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image";


    }
}
