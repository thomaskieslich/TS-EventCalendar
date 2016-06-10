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
        $content .= '<h3>' . self::$langExt['Edit Events'] . '</h3>';
        $content .= '<form name="events" action="' . common::GetUrl('Admin_EventCalendar_Events') . '" method="post">';
        $content .= '<table class="bordered full_width">';

        $content .= '<thead><tr>';
        $content .= '<th>' . self::$langExt['Start Day'] . '</th>';
        $content .= '<th>' . self::$langExt['Start Time'] . '</th>';
        $content .= '<th>' . self::$langExt['End Day'] . '</th>';
        $content .= '<th>' . self::$langExt['End Time'] . '</th>';
        $content .= '<th>' . self::$langExt['Title'] . '</th>';
        $content .= '<th>' . self::$langExt['Location'] . '</th>';
        $content .= '<th>' . self::$langExt['Description'] . '</th>';
        $content .= '<th>' . self::$langExt['Category'] . '</th>';
        $content .= '<th>' . self::$langExt['Options'] . '</th>';
        $content .= '</tr></thead>';

        $content .= '<tbody>';
        foreach (self::$events as $key => $event) {
            $content .= '<tr>';
            if ($event['start_day']) {
                $content .= '<td>' . strftime('%d.%m.%Y', $event['start_day']) . '</td>';
            } else {
                $content .= '<td>&nbsp;</td>';
            }

            if ($event['start_time']) {
                $content .= '<td>' . strftime(self::$configuration['timeFormat'], $event['start_time']) . '</td>';
            } else {
                $content .= '<td>&nbsp;</td>';
            }

            if ($event['end_day']) {
                $content .= '<td>' . strftime(self::$configuration['dateFormat'], $event['end_day']) . '</td>';
            } else {
                $content .= '<td>&nbsp;</td>';
            }

            if ($event['end_time']) {
                $content .= '<td>' . strftime(self::$configuration['timeFormat'], $event['end_time']) . '</td>';
            } else {
                $content .= '<td>&nbsp;</td>';
            }
            $content .= '<td>' . $event['title'] . '</td>';
            $content .= '<td>' . $event['location'] . '</td>';
            $content .= '<td>' . $event['description'] . '</td>';
            if ($event['category'] != '') {
                $content .= '<td style="color: ' . self::$categories[$event['category']]['color'] . ' !important;">' . self::$categories[$event['category']]['label'] . '</td>';
            } else {
                $content .= '<td>&nbsp;</td>';
            }
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
                ' name="gpabox" class="gpconfirm gpsubmit" title="' . self::$langExt['Delete Event'] . '" '
            );
            $content .= '</td>';
            $content .= '</tr>';
        }

        $content .= '</tbody>';

        $content .= '</table>';

        $content .= '<p style="margin-top: 10px;">';
        $content .= '<input type="hidden" name="cmd" value="reload_events" />';
        $content .= '<input type="submit" value="' . self::$langExt['Reload Events'] . '" class="gpsubmit"/>';
        $content .= ' &nbsp; ';
        $content .= common::Link(
            'Admin_EventCalendar_Events',
            self::$langExt['New Event'],
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
            $event = self::$events[(int)$_GET['index']];
        } else {
            $event = [];
        }

        $content = '';
        $content .= '<div class="inline_box">';
        $content .= '<h3>' . self::$langExt['Edit Event'] . '</h3>';

        $content .= '<form name="editevent" action="' . common::GetUrl('Admin_EventCalendar_Events') . '" method="post">';
        $content .= '<input type="hidden" name="cmd" value="update_event" />';
        if (isset($index) && $index >= 0) {
            $content .= '<input type="hidden" name="event[index]" value="' . (int)$_GET['index'] . '" />';
        }

        $content .= '<table class="bordered"><tbody>';

        $content .= '<tr><td colspan="2">';
        $content .= '<label for="title">' . self::$langExt['Title'] . '*</label><input type="text" name="event[title]" value="' . @$event['title'] . '" class="gpinput full_width" />';
        $content .= '</td></tr>';

        $content .= '<tr><td colspan="2">';
        $content .= '<label for="location">' . self::$langExt['Location'] . '</label><input type="text" name="event[location]" value="' . @$event['location'] . '" class="gpinput full_width" />';
        $content .= '</td></tr>';

        $content .= '<tr><td>';
        $content .= '<label for="start_day">' . self::$langExt['Start Day'] . '*</label>';
        if (isset($event['start_day']) && $event['start_day'] > 0) {
            $content .= '<input type="text" name="event[start_day]" value="' . strftime('%d.%m.%Y',
                    $event['start_day']) . '" class="gpinput datepicker" />';
        } else {
            $content .= '<input type="text" name="event[start_day]" value="" class="gpinput datepicker" />';
        }
        $content .= '</td><td>';

        $content .= '';
        $content .= '<label for="start_time">' . self::$langExt['Start Time'] . '</label>';
        if (isset($event['start_time']) && $event['start_time'] > 0) {
            $content .= '<input type="text" name="event[start_time]" value="' . strftime('%H:%M',
                    $event['start_time']) . '" class="gpinput timepicker" />';
        } else {
            $content .= '<input type="text" name="event[start_time]" value="" class="gpinput timepicker" />';
        }
        $content .= '</td></tr>';

        $content .= '<tr><td>';
        $content .= '<label for="end_day">' . self::$langExt['End Day'] . '</label>';
        if (isset($event['end_day']) && $event['end_day'] > 0) {
            $content .= '<input type="text" name="event[end_day]" value="' . strftime('%d.%m.%Y',
                    $event['end_day']) . '" class="gpinput datepicker" />';
        } else {
            $content .= '<input type="text" name="event[end_day]" value="" class="gpinput datepicker" />';
        }
        $content .= '</td><td>';

        $content .= '';
        $content .= '<label for="end_time">' . self::$langExt['End Time'] . '</label>';
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
        $content .= '<label for="category">' . self::$langExt['Category'] . '</label><select name="event[category]" class="gpinput full_width">';
        $options = '<option value="">-</option>';

        foreach (self::$categories as $key => $category) {
            $selected = '';
            if ($event['category'] != '' && $key == (int)$event['category']) {
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
            $event['location']    = htmlspecialchars(trim($_POST['event']['location']));
            $event['start_day']   = strtotime(htmlspecialchars(trim($_POST['event']['start_day'])));
            $event['start_time']  = strtotime(htmlspecialchars(trim($_POST['event']['start_time'])));
            $event['end_day']     = strtotime(htmlspecialchars(trim($_POST['event']['end_day'])));
            $event['end_time']    = strtotime(htmlspecialchars(trim($_POST['event']['end_time'])));
            $event['description'] = htmlspecialchars(trim($_POST['event']['description']));
            $event['category']    = htmlspecialchars(trim($_POST['event']['category']));

            if (isset($_POST['event']['index'])) {
                self::$events[$_POST['event']['index']] = $event;
            } else {
                self::$events[] = $event;
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
            unset(self::$events[$_GET['index']]);
            msg(self::$langExt['deleted_event']);

            $this->SaveEvents();
            $this->LoadEvents();
            $this->ShowEvents();
        } else {
            msg($langmessage['OOPS']);
        }

    }

    protected function SaveEvents()
    {
        if (count(self::$events) == 0) {
            unlink(self::$eventFile);

            return null;
        }

        $file = fopen(self::$eventFile, 'w');
        fputcsv($file, array_keys(reset(self::$events)));
        foreach (self::$events as $row) {
            fputcsv($file, $row);
        }
        fclose($file);

        return true;
    }


}