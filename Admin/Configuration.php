<?php

defined('is_running') or die('Not an entry point...');

gpPlugin_incl('Admin/Admin.php');

class EventCalendarAdminConfiguration extends EventCalendarAdmin
{

    public function __construct()
    {
        parent::__construct();
        
        $cmd = common::GetCommand();
        switch ($cmd) {
            case 'save_configuration':
                self::SaveConfiguration();
                break;
        }
        self::ShowConfiguration();
    }

    public static function ShowConfiguration()
    {
        global $langmessage;

        $content = '';
        $content .= '<div class="inline_box">';
        $content .= '<h3>' . self::$langExt['configuration'] . '</h3>';

        $content .= '<form name="editconfig" action="' . common::GetUrl('Admin_EventCalendar_Configuration') . '" method="post">';
        $content .= '<input type="hidden" name="cmd" value="save_configuration" />';
        $content .= '<table class="bordered"><tbody>';

        foreach (self::$configuration as $label => $value) {
            $content .= '<tr><td>' . self::$langExt['conf_' . $label] . '</td><td> <input type="text" name="' . $label . '" value="' . $value . '" class="gpinput" /></td></tr>';
        }

        $content .= '<tr><td colspan="2">';
        $content .= '<input type="submit" value="' . $langmessage['save'] . '" class="gppost gpsubmit" />';
        $content .= '</td></tr>';

        $content .= '</tbody></table>';
        $content .= '</form>';
        $content .= '</div>';

        echo $content;
    }


    public static function SaveConfiguration()
    {
        global $langmessage;

        if (isset($_POST)) {
            foreach ($_POST as $field => $value) {
                if (array_key_exists($field, self::$configuration)) {
                    self::$configuration[$field] = htmlspecialchars(trim($value));
                }
            }
        }

        $success = gpFiles::SaveData(self::$configurationFile, 'configuration', self::$configuration);

        if ($success) {
            msg($langmessage['SAVED']);

            return true;
        } else {
            msg($langmessage['OOPS']);

            return false;
        }
    }



}