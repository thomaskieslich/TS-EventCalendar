<?php
defined('is_running') or die('Not an entry point...');

gpPlugin_incl('EventCalendarCommon.php');

class EventCalendarAdmin extends EventCalendarCommon
{
    function __construct(){
        global $addonFolderName, $page;

        parent::__construct();

//        common::LoadComponents('dialog');
//        common::LoadComponents('datepicker');

//        $page->css_admin[]	= '/data/_addoncode/'.$addonFolderName.'/assets/css/jquery.timepicker.css';
        $page->css_admin[]	= '/data/_addoncode/'.$addonFolderName.'/assets/css/admin.css';


//        $page->head_js[]	= '/data/_addoncode/'.$addonFolderName.'/assets/js/jquery.timepicker.min.js';
        $page->head_js[]	= '/data/_addoncode/'.$addonFolderName.'/assets/js/admin.js';
    }
}