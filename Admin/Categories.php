<?php
defined('is_running') or die('Not an entry point...');

gpPlugin_incl('Admin/Admin.php');

class EventCalendarAdminCategories extends EventCalendarAdmin
{

    public function __construct()
    {
        parent::__construct();

        $cmd = common::GetCommand();
        switch ($cmd) {

            case 'new_category':
                self::NewCategory();

                return;
            case 'save_new_category';
                self::SaveNewCategory();

                return;
            case 'update_categories';
                self::UpdateCategories();

                return;
            case 'delete_category';
                self::DeleteCategory();

                return;
            default;
                self::ShowCategories();
        }
    }


    public static function ShowCategories()
    {
        global $langmessage, $addonRelativeCode;

        $content = '';
        $content .= '<div class="inline_box">';
        $content .= '<h3>' . self::$langExt['Edit Categories'] . '</h3>';
        $content .= '<form name="categories" id="edit-categories" action="' . common::GetUrl('Admin_EventCalendar_Categories') . '" method="post">';
        $content .= '<table class="bordered">';

        $content .= '<thead><tr>';
        $content .= '<th>&nbsp;</th>';
        $content .= '<th>' . self::$langExt['Category'] . '</th>';
        $content .= '<th>' . self::$langExt['CatColor'] . '</th>';
        $content .= '<th>' . self::$langExt['Options'] . '</th>';
        $content .= '</tr></thead>';

        $content .= '<tbody class="sortable_table">';
        if (count(self::$categories) > 0) {
            foreach (self::$categories as $key => $value) {
                $content .= '<tr><td style="vertical-align:middle">';
                $content .= '<img src="' . $addonRelativeCode . '/assets/img/grip.png" height="15" width="15" style="padding:2px;cursor:pointer;"/>';
                $content .= '</td><td>';
                $content .= '<input type="text" name="categories[' . $key . '][label]" value="' . $value['label'] . '" class="gpinput" />';
                $content .= '</td><td>';
                $content .= '<input type="text" name="categories[' . $key . '][color]" class="colorpicker" value="' . @$value['color'] . '""/></div>';
                $content .= '</td><td>';
                $content .= common::Link(
                    'Admin_EventCalendar_Categories',
                    $langmessage['delete'],
                    'cmd=delete_category&index=' . $key,
                    'class="gpconfirm" title="' . self::$langExt['Delete Categorie'] . '" '
                );
                $content .= '</td></tr>';
            }
        }
        $content .= '</tbody>';
        $content .= '</table>';

        $content .= '<p style="margin-top: 10px;">';
        $content .= '<input type="hidden" name="cmd" value="update_categories" />';
        $content .= '<input type="submit" value="' . $langmessage['save_changes'] . '" class="gpsubmit"/>';
        $content .= ' &nbsp; ';
        $content .= common::Link(
            'Admin_EventCalendar_Categories',
            self::$langExt['New Categorie'],
            'cmd=new_category',
            ' name="gpabox" class="gpsubmit"'
        );

        $content .= '</p>';
        $content .= '</form>';

        $content .= '</div>';
        $content .= '<script>initColorpicker();</script>';

        echo $content;
    }

    public static function NewCategory()
    {
        global $langmessage;

        $content = '';
        $content .= '<div class="inline_box">';
        $content .= '<h3>' . self::$langExt['New Categorie'] . '</h3>';

        $content .= '<form name="addcategory" action="' . common::GetUrl('Admin_EventCalendar_Categories') . '" method="post">';
        $content .= '<input type="hidden" name="cmd" value="save_new_category" />';
        $content .= '<p>' . $langmessage['title'] . ' <input type="text" name="label" value="" class="gpinput" /></p>';
        $content .= '<p>' . self::$langExt['CatColor'] . ' <input type="text" name="color" value="" class="gpinput colorpicker" /></p>';

        $content .= '<p><input type="submit" value="' . $langmessage['save'] . '" class="gppost gpsubmit"/>';
        $content .= '<input type="submit" name="cmd" value="' . $langmessage['cancel'] . '" class="admin_box_close gpcancel"/></p>';

        $content .= '</form>';
        $content .= '</div>';
        $content .= '<script>initColorpicker();</script>';

        echo $content;
    }

    public static function SaveNewCategory()
    {
        global $langmessage;

        if (isset($_POST) && $_POST['label']) {
            $new          = [];
            $new['label'] = htmlspecialchars(trim($_POST['label']));
            $new['color'] = htmlspecialchars(trim($_POST['color']));
            self::$categories[] = $new;
            self::SaveCategories();
        } else {
            msg($langmessage['OOPS']);
        }
        self::ShowCategories();
    }

    public static function DeleteCategory()
    {
        if (isset($_GET) && $_GET['index']) {
            unset(self::$categories[(int)$_GET['index']]);
        }
        self::SaveCategories();
        self::ShowCategories();
    }

    public static function UpdateCategories()
    {
        if (isset($_POST) && $_POST['categories']) {
            self::$categories = [];
            foreach ($_POST['categories'] as $key => $value) {
                self::$categories[$key]['label'] = htmlspecialchars(trim($value['label']));
                self::$categories[$key]['color'] = htmlspecialchars(trim($value['color']));
            }
            self::SaveCategories();
            self::ShowCategories();
        }
    }

    public static function SaveCategories()
    {
        global $langmessage;

        if (count(self::$categories > 0)) {
            $success = gpFiles::SaveData(self::$categoryFile, 'categories', self::$categories);
            if ($success) {
                msg($langmessage['SAVED']);

                return true;
            } else {
                msg($langmessage['OOPS']);

                return false;
            }
        }
    }

}