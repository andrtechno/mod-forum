<?php
namespace panix\mod\forum\models;
class SettingsForm extends \panix\engine\SettingsModel {
    protected $module='forum';
    public $pagenum;
    public $edit_post_time;
    public $enable_post_delete;
    public $enable_guest_addtopic;
    public $enable_guest_addpost;
    

    public static function defaultSettings() {
        return array(
            'pagenum' => 10,
            'edit_post_time'=>5,
            'enable_post_delete'=>false,
            'enable_guest_addtopic'=>false,
            'enable_guest_addpost'=>false,
        );
    }


    public function rules() {
        return [
            [['pagenum', 'edit_post_time'], 'required'],
            [['enable_post_delete', 'enable_guest_addpost', 'enable_guest_addtopic'], 'boolean'],
            [['pagenum', 'edit_post_time'], 'number'],
        ];
    }

}
