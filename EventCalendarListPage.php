<?php

defined('is_running') or die('Not an entry point...');

gpPlugin_incl('EventCalendarCommon.php');

class EventCalendarListPage extends EventCalendarCommon
{
    public function __construct()
    {
        parent::__construct();

        $current_date = getdate();
        $content      = '';

        $content .= '<div class="calendar-list">';

        foreach ($this->events as $event) {
            if ((int)$event['start_day'] >= $current_date[0]) {
                $content .= $this->CreateEntry($event);
            }
        }

        $content .= '</div>';
        echo $content;
    }

    protected function CreateEntry($data)
    {
        $entry = '';

        $entry .= '<div class="entry">';
        $entry .= '<div class="head">';
        $entry .= '<h3>' . $data['title'] . '</h3>';
        $entry .= '<div class="date">';

        if ($data['start_day'] > 0) {
            $entry .= '<span class="start-day">' . strftime($this->configuration['dateFormatSite'], $data['start_day']) . '</span>';
        }

        if ($data['start_time'] > 0) {
            $entry .= '<span class="start-time"> ' . strftime($this->configuration['timeFormatSite'], $data['start_time']) . '</span>';
        }

        if ($data['end_day'] > 0) {
            $entry .= ' <span class="end-day">' . strftime($this->configuration['dateFormatSite'], $data['end_day']) . '</span>';
        }

        if ($data['end_time'] > 0) {
            $entry .= '<span class="end-time"> ' . strftime($this->configuration['timeFormatSite'], $data['end_time']) . '</span>';
        }
        $entry .= '</div>';

        $entry .= '</div>';
        $entry .= '<div class="body">';
        $entry .= '<p>' . $data['description'] . '</p>';
        $entry .= '</div>';
        $entry .= '</div>';

        return $entry;
    }
}

