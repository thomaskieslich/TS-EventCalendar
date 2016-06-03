<?php

defined('is_running') or die('Not an entry point...');

gpPlugin_incl('Admin/Admin.php');

class AdminEventCalendarEvents extends EventCalendarAdmin
{

    public function __construct()
    {
        global $page;

        parent::__construct();

        //general admin
        $cmd = common::GetCommand();
        switch ($cmd) {

            //creating
            case 'new_form':
                $this->NewEvent();
                return;
            case 'save_new';
                $this->SaveNew();
                return;
            case 'test';
//                $page->ajaxReplace = array();

                msg($_REQUEST);

                $arg_value = "test";
//                $page->ajaxReplace[] = array('my_respond', 'arg', $arg_value);
                return;


        }

        $this->ShowEvents();
    }

    public function ShowEvents()
    {
        $content = '';
        $content .= '<h2>' . $this->langExt['Edit Events'] . '</h2>';
        $content .= '<a href="/Admin_Events?cmd=newEvent" class="butt" name="gpabox" title="' . $this->langExt['New Event'] . '">' . $this->langExt['New Event'] . '</a>';
        $content .= '<button class="gpsubmit" data-cmd="new">' . $this->langExt['New Event'] . '</button>';

        $content .= '<table class="event-table"><tr>';
        $content .= '<thead><tr>';
        $content .= '<th class="start-day collapsible">' . $this->langExt['Start Day'] . '</th>';
        $content .= '<th class="start-time">' . $this->langExt['Start Time'] . '</th>';
        $content .= '<th class="end-day">' . $this->langExt['End Day'] . '</th>';
        $content .= '<th class="end-time">' . $this->langExt['End Time'] . '</th>';
        $content .= '<th class="all-day">' . $this->langExt['All Day'] . '</th>';
        $content .= '<th class="title">' . $this->langExt['Title'] . '</th>';
        $content .= '<th class="description">' . $this->langExt['Description'] . '</th>';
        $content .= '<th class="categories">' . $this->langExt['Categories'] . '</th>';
        $content .= '<th class="edit">' . $this->langExt['Edit'] . '</th>';
        $content .= '</tr></thead><tbody>';

        //Events
        if(count($this->eventData) > 0){
            $content .= 'Events';
        } else{
            $content .= '<tr>';
            $content .= '<td> </td>';
            $content .= '<td> </td>';
            $content .= '<td> </td>';
            $content .= '<td> </td>';
            $content .= '<td> </td>';
            $content .= '<td> </td>';
            $content .= '<td> </td>';
            $content .= '<td> </td>';
            $content .= '<td> </td>';
            $content .= '<td> </td>';
            $content .= '</tr>';
        }

        $content .= '</tbody></table>';

        //Event Form
        $content .= '<div id="event-form" style="display: none;">';
        $content .= '<form id="editEvent">';
        $content .= '<label for="start-day">' . $this->langExt['Start Day'] . '</label><input type="text" id="start-day" class="datepicker" value=""/>';
        $content .= '<label for="start-time">' . $this->langExt['Start Time'] . '</label><input type="text" id="start-time" class="timepicker" value=""/>';

        $content .= '<input type="submit" value="Save Event"/>';
        $content .= '</form>';
        $content .= '</div>';

        echo $content;
    }

}