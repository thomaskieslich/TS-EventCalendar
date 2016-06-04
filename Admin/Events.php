<?php

defined('is_running') or die('Not an entry point...');

gpPlugin_incl('Admin/Admin.php');

class EventCalendarAdminEvents extends EventCalendarAdmin
{

    public function __construct()
    {
        parent::__construct();

        $cmd = common::GetCommand();
        switch ($cmd) {

            case 'edit_event':
                $this->EditEvent();

                return;
            case 'update_event':
                $this->UpdateEvent();

                return;
            case 'delete_event':
                $this->DeleteEvent();

                return;
            case 'reload_events':
                $this->LoadEvents();
                $this->ShowEvents();

                return;
            case 'save_new_event';
                $this->SaveNewEvent();

                return;
            default;
                $this->ShowEvents();
        }

    }

    protected function ShowEvents()
    {
        global $langmessage;

        $content = '';
        $content .= '<div class="inline_box">';
        $content .= '<h3>' . $this->langExt['Edit Events'] . '</h3>';
        $content .= '<form name="events" action="' . common::GetUrl('Admin_EventCalendar_Events') . '" method="post">';
        $content .= '<table class="bordered full_width">';

        $content .= '<thead><tr>';
        $content .= '<th>' . $this->langExt['Start Day'] . '</th>';
        $content .= '<th>' . $this->langExt['Start Time'] . '</th>';
        $content .= '<th>' . $this->langExt['End Day'] . '</th>';
        $content .= '<th>' . $this->langExt['End Time'] . '</th>';
        $content .= '<th>' . $this->langExt['Title'] . '</th>';
        $content .= '<th>' . $this->langExt['Description'] . '</th>';
        $content .= '<th>' . $this->langExt['Options'] . '</th>';
        $content .= '</tr></thead>';

        $content .= '<tbody>';
        foreach ($this->events as $key => $event) {
            $content .= '<tr>';
            if ($event['start_day']) {
                $content .= '<td>' . strftime($this->configuration['dateFormatSite'], $event['start_day']) . '</td>';
            } else {
                $content .= '<td>&nbsp;</td>';
            }

            if ($event['start_time']) {
                $content .= '<td>' . strftime($this->configuration['timeFormatSite'], $event['start_time']) . '</td>';
            } else {
                $content .= '<td>&nbsp;</td>';
            }

            if ($event['end_day']) {
                $content .= '<td>' . strftime($this->configuration['dateFormatSite'], $event['end_day']) . '</td>';
            } else {
                $content .= '<td>&nbsp;</td>';
            }

            if ($event['end_time']) {
                $content .= '<td>' . strftime($this->configuration['timeFormatSite'], $event['end_time']) . '</td>';
            } else {
                $content .= '<td>&nbsp;</td>';
            }
            $content .= '<td>' . $event['title'] . '</td>';
            $content .= '<td>' . $event['description'] . '</td>';
            $content .= '<td>';
            $content .= common::Link(
                'Admin_EventCalendar_Events',
                $langmessage['edit'],
                'cmd=edit_event&index=' . $key,
                ' name="gpabox" class="gpsubmit" '
            );
            $content .= common::Link(
                'Admin_EventCalendar_Events',
                $langmessage['delete'],
                'cmd=delete_event&index=' . $key,
                ' name="gpabox" class="gpconfirm gpsubmit" title="' . $this->langExt['Delete Event'] . '" '
            );
            $content .= '</td>';
            $content .= '</tr>';
        }

        $content .= '</tbody>';

        $content .= '</table>';

        $content .= '<p style="margin-top: 10px;">';
        $content .= '<input type="hidden" name="cmd" value="reload_events" />';
        $content .= '<input type="submit" value="' . $this->langExt['Reload Events'] . '" class="gpsubmit"/>';
        $content .= ' &nbsp; ';
        $content .= common::Link(
            'Admin_EventCalendar_Events',
            $this->langExt['New Event'],
            'cmd=edit_event',
            ' name="gpabox" class="gpsubmit"'
        );

        $content .= '</p>';
        $content .= '</form>';

        $content .= '</div>';

        echo $content;
    }

    protected function EditEvent()
    {
        global $langmessage;
        $index = null;
        if (isset($_GET['index']) && $_GET['index'] >= 0) {
            $index = (int)$_GET['index'];
            $event = $this->events[(int)$_GET['index']];
        } else {
            $event = [];
        }

        $content = '';
        $content .= '<div class="inline_box">';
        $content .= '<h3>' . $this->langExt['Edit Event'] . '</h3>';

        $content .= '<form name="editevent" action="' . common::GetUrl('Admin_EventCalendar_Events') . '" method="post">';
        $content .= '<input type="hidden" name="cmd" value="update_event" />';
        if (isset($index) && $index >= 0) {
            $content .= '<input type="hidden" name="event[index]" value="' . (int)$_GET['index'] . '" />';
        }

        $content .= '<table><tbody>';

        $content .= '<tr><td colspan="2">';
        $content .= '<label for="title">' . $langmessage['title'] . '*</label><input type="text" name="event[title]" value="' . @$event['title'] . '" class="gpinput full_width" />';
        $content .= '</td></tr>';

        $content .= '<tr><td>';
        $content .= '<label for="start_day">' . $this->langExt['Start Day'] . '*</label>';
        if (isset($event['start_day']) && $event['start_day'] > 0) {
            $content .= '<input type="text" name="event[start_day]" value="' . strftime('%d.%m.%Y',
                    $event['start_day']) . '" class="gpinput datepicker" />';
        } else {
            $content .= '<input type="text" name="event[start_day]" value="" class="gpinput datepicker" />';
        }
        $content .= '</td><td>';

        $content .= '';
        $content .= '<label for="start_time">' . $this->langExt['Start Time'] . '</label>';
        if (isset($event['start_time']) && $event['start_time'] > 0) {
            $content .= '<input type="text" name="event[start_time]" value="' . strftime('%H:%M',
                    $event['start_time']) . '" class="gpinput timepicker" />';
        } else {
            $content .= '<input type="text" name="event[start_time]" value="" class="gpinput timepicker" />';
        }
        $content .= '</td></tr>';

        $content .= '<tr><td>';
        $content .= '<label for="end_day">' . $this->langExt['End Day'] . '</label>';
        if (isset($event['end_day']) && $event['end_day'] > 0) {
            $content .= '<input type="text" name="event[end_day]" value="' . strftime('%d.%m.%Y',
                    $event['end_day']) . '" class="gpinput datepicker" />';
        } else {
            $content .= '<input type="text" name="event[end_day]" value="" class="gpinput datepicker" />';
        }
        $content .= '</td><td>';

        $content .= '';
        $content .= '<label for="end_time">' . $this->langExt['End Time'] . '</label>';
        if (isset($event['end_time']) && $event['end_time'] > 0) {
            $content .= '<input type="text" name="event[end_time]" value="' . strftime('%H:%M',
                    $event['end_time']) . '" class="gpinput timepicker" />';
        } else {
            $content .= '<input type="text" name="event[end_time]" value="" class="gpinput timepicker" />';
        }
        $content .= '</td></tr>';

        $content .= '<tr><td colspan="2">';
        $content .= '<label for="description">' . $langmessage['description'] . '</label><textarea name="event[description]" rows="5" class="full_width">';
        $content .= @$event['description'] . '</textarea>';
        $content .= '</td></tr>';

        $content .= '<tr><td colspan="2">';
        $content .= '<label for="categories">' . $this->langExt['Categories'] . '</label><select name="event[categories][]" multiple class="gpinput full_width">';
        $options = '';

        $categories = [];
        if (isset($event['categories']) && $event['categories'] != '') {
            $categories = explode(',', $event['categories']);
        }

        foreach ($this->categories as $key => $category) {
            $selected = '';
            if (in_array($key, $categories)) {
                $selected = 'selected';
            }
            $options .= '<option value="' . $key . '" ' . $selected . '>' . $category['label'] . '</option>';
        }
        $content .= $options . '</select>';

        $content .= '</td></tr>';

        $content .= '</tbody></table>';

        $content .= '<p style="margin-top: 10px;">';
        $content .= '<input type="submit" value="' . $langmessage['save'] . '" class="gppost gpsubmit"/>';
        $content .= '<input type="submit" name="cmd" value="' . $langmessage['cancel'] . '" class="admin_box_close gpcancel"/></p>';

        $content .= '</p>';
        $content .= '</form>';
        $content .= '</div>';
        $content .= '<script>initDateTime();</script>';

        echo $content;
    }

    protected function UpdateEvent()
    {
        global $langmessage;

        if (isset($_POST) && $_POST['event']['title'] && $_POST['event']['start_day']) {
            $event                = [];
            $event['title']       = htmlspecialchars(trim($_POST['event']['title']));
            $event['start_day']   = strtotime(htmlspecialchars(trim($_POST['event']['start_day'])));
            $event['start_time']  = strtotime(htmlspecialchars(trim($_POST['event']['start_time'])));
            $event['end_day']     = strtotime(htmlspecialchars(trim($_POST['event']['end_day'])));
            $event['end_time']    = strtotime(htmlspecialchars(trim($_POST['event']['end_time'])));
            $event['description'] = htmlspecialchars(trim($_POST['event']['description']));

            if (isset($_POST['event']['categories'])) {
                $event['categories'] = join(',', $_POST['event']['categories']);
            } else {
                $event['categories'] = '';
            }

            if (isset($_POST['event']['index'])) {
                $this->events[$_POST['event']['index']] = $event;
            } else {
                $this->events[] = $event;
            }

            $this->SaveEvents();
            $this->LoadEvents();
            $this->ShowEvents();
        } else {
            msg($langmessage['OOPS']);
        }
    }

    protected function DeleteEvent()
    {
        global $langmessage;
        if (isset($_GET) && $_GET['index'] >= 0) {
            unset($this->events[$_GET['index']]);
            msg($this->langExt['deleted_event']);

            $this->SaveEvents();
            $this->LoadEvents();
            $this->ShowEvents();
        } else {
            msg($langmessage['OOPS']);
        }

    }

    protected function SaveEvents()
    {
        if (count($this->events) == 0) {
            unlink($this->eventFile);
            return null;
        }

        $file = fopen($this->eventFile, 'w');
        fputcsv($file, array_keys(reset($this->events)));
        foreach ($this->events as $row) {
            fputcsv($file, $row);
        }
        fclose($file);

        return true;
    }


}