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

            //creating
            case 'new_category':
                $this->NewCategory();

                return;
            case 'save_new_category';
                $this->SaveNewCategory();

                return;
            case 'update_categories';
                $this->UpdateCategories();

                return;
            case 'delete_category';
                $this->DeleteCategory();

                return;
            default;
                $this->ShowCategories();
        }
    }


    protected function ShowCategories()
    {
        global $langmessage, $addonRelativeCode;

        $content = '';
        $content .= '<div class="inline_box">';
        $content .= '<h3>' . $this->langExt['Edit Categories'] . '</h3>';
        $content .= '<form name="categories" action="' . common::GetUrl('Admin_EventCalendar_Categories') . '" method="post">';
        $content .= '<table class="bordered">';

        $content .= '<thead><tr>';
        $content .= '<th>&nbsp;</th>';
        $content .= '<th>' . $this->langExt['Category'] . '</th>';
//        $content .= '<th>' . $this->langExt['Events'] . '</th>';
//        $content .= '<th>' . $this->langExt['Hidden'] . '</th>';
        $content .= '<th>' . $this->langExt['Options'] . '</th>';
        $content .= '</tr></thead>';

        $content .= '<tbody class="sortable_table">';
        if (count($this->categories > 0)) {
            foreach ($this->categories as $key => $value) {
                $content .= '<tr><td style="vertical-align:middle">';
                $content .= '<img src="' . $addonRelativeCode . '/assets/img/grip.png" height="15" width="15" style="padding:2px;cursor:pointer;"/>';
                $content .= '</td><td>';
                $content .= '<input type="text" name="categories[' . $key . '][label]" value="' . $value['label'] . '" class="gpinput" />';
//                $content .= '</td><td>';
//                $content .= '123';
//                $content .= '</td><td>';
//                $checked = '';
//                if (isset($value['hidden'])) {
//                    $checked = 'checked';
//                }
//                $content .= '<input type="checkbox" ' . $checked . ' name="categories[' . $key . '][hidden]" />';
                $content .= '</td><td>';
                $content .= common::Link(
                    'Admin_EventCalendar_Categories',
                    $langmessage['delete'],
                    'cmd=delete_category&index=' . $key,
                    'class="gpconfirm" title="' . $this->langExt['Delete Categorie'] . '" '
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
            $this->langExt['New Categorie'],
            'cmd=new_category',
            ' name="gpabox" class="gpsubmit"'
        );

        $content .= '</p>';
        $content .= '</form>';

        $content .= '</div>';

        echo $content;
    }

    protected function NewCategory()
    {
        global $langmessage;

        $content = '';
        $content .= '<div class="inline_box">';
        $content .= '<h3>' . $this->langExt['New Categorie'] . '</h3>';

        $content .= '<form name="addcategory" action="' . common::GetUrl('Admin_EventCalendar_Categories') . '" method="post">';
        $content .= '<input type="hidden" name="cmd" value="save_new_category" />';
        $content .= '<p>' . $langmessage['title'] . ' <input type="text" name="new_category" value="" class="gpinput" /></p>';

        $content .= '<p><input type="submit" value="' . $langmessage['save'] . '" class="gppost gpsubmit"/>';
        $content .= '<input type="submit" name="cmd" value="' . $langmessage['cancel'] . '" class="admin_box_close gpcancel"/></p>';

        $content .= '</form>';
        $content .= '</div>';

        echo $content;
    }

    protected function SaveNewCategory()
    {
        global $langmessage;

        if (isset($_POST) && $_POST['new_category']) {
            $this->categories[]['label'] = htmlspecialchars(trim($_POST['new_category']));
            $this->SaveCategories();
        } else {
            msg($langmessage['OOPS']);
        }
        $this->ShowCategories();
    }

    protected function DeleteCategory()
    {
        if (isset($_GET) && $_GET['index']) {
            unset($this->categories[(int)$_GET['index']]);
        }
        $this->SaveCategories();
        $this->ShowCategories();
    }

    protected function UpdateCategories()
    {
        if (isset($_POST) && $_POST['categories']) {
            $this->categories = [];
            foreach ($_POST['categories'] as $key => $value) {
                $this->categories[$key]['label'] = htmlspecialchars(trim($value['label']));
//                if (isset($value['hidden'])) {
//                    $this->categories[$key]['hidden'] = htmlspecialchars(trim($value['hidden']));
//                }
            }
            $this->SaveCategories();
            $this->ShowCategories();
        }
    }

    protected function SaveCategories()
    {
        global $langmessage;

        if (count($this->categories > 0)) {
            $success = gpFiles::SaveData($this->categoryFile, 'categories', $this->categories);
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