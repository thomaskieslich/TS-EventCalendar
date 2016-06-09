<?php

defined('is_running') or die('Not an entry point...');

gpPlugin_incl('EventCalendarCommon.php');

class EventCalendarListPage extends EventCalendarCommon
{
    public function __construct()
    {
        EventCalendarCommon::Init();
        $content = EventCalendarCommon::CreateList();
        echo $content;
    }
}

