<?php
defined('is_running') or die('Not an entry point...');

gpPlugin_incl('EventCalendarCommon.php');

class EventCalendarAdmin extends EventCalendarCommon
{
    function __construct(){
        global $addonFolderName, $page;

        parent::__construct();
//        EventCalendarCommon::Init();
        echo 'Admin';

//        common::LoadComponents('bootstrap3-all');
        $page->head_js[]	= '/data/_addoncode/'.$addonFolderName.'/assets/js/admin.js';
        $page->css_admin[]	= '/data/_addoncode/'.$addonFolderName.'/assets/css/admin.css';
    }
}