<?php

defined('is_running') or die('Not an entry point...');

gpPlugin_incl('Admin/Admin.php');

class AdminEventCalendarEvents extends EventCalendarAdmin
{

    public function __construct()
    {
//        global $page;

        parent::__construct();

        //general admin
        $cmd = common::GetCommand();
        switch ($cmd) {

            //creating
            case 'new_form':
                $this->NewEvent();

                return;
            case 'save_new';
                $this->SaveNew(); //will redirect on success
        }

        $this->ShowEvents();
    }

    public function ShowEvents()
    {
        $content = '';
        $content .= '<h2>' . $this->langExt['Edit Events'] . '</h2>';

        $content .= '<table class="event-table"><tr>';
        $content .= '<th class="start-day collapsible">' . $this->langExt['Start Day'] . '</th>';
        $content .= '<th class="start-time">' . $this->langExt['Start Time'] . '</th>';
        $content .= '<th class="end-day">' . $this->langExt['End Day'] . '</th>';
        $content .= '<th class="end-time">' . $this->langExt['End Time'] . '</th>';
        $content .= '<th class="all-day">' . $this->langExt['All Day'] . '</th>';
        $content .= '<th class="title">' . $this->langExt['Title'] . '</th>';
        $content .= '<th class="description">' . $this->langExt['Description'] . '</th>';
        $content .= '</tr>';

        //Events
        if(count($this->eventData) > 0){
            $content .= 'Events';
        } else{
            $content .= '<tr>';
            $content .= '<td>1</td>';
            $content .= '<td>2</td>';
            $content .= '<td>3</td>';
            $content .= '<td>4</td>';
            $content .= '<td>4</td>';
            $content .= '<td>4</td>';
            $content .= '<td>4</td>';
            $content .= '</tr>';
        }

        $content .= '</table>';


        echo $content;
    }

}