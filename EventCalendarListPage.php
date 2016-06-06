<?php

defined('is_running') or die('Not an entry point...');

gpPlugin_incl('EventCalendarCommon.php');

class EventCalendarListPage extends EventCalendarCommon
{
    public function __construct()
    {
        global $addonPathData;

        parent::__construct();

        $current_date = getdate();
        $content ='';

        $content .= '<div align="center" class="content"><p><table cellpadding="5" cellspacing="5" border="0" style="width:100%"><tbody>';

        foreach ($this->events as $event){
            if((int)$event['start_day'] >= $current_date[0]){
                $content .= '<tr>';
                $content .= '<td>' . $event['title'] . '</td>';
                $content .= '<td>' . strftime($this->configuration['dateFormatSite'], $event['start_day']) . '</td>';
                $content .= '</tr>';
            }
        }

        $content .= '</table>';

        echo $content;
    }
}