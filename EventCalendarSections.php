<?php

defined('is_running') or die('Not an entry point...');

gpPlugin_incl('EventCalendarCommon.php');

class EventCalendarSections extends EventCalendarCommon
{
    public static function SectionTypes($section_types)
    {
        $section_types['CalendarList'] = array(
            'label' => 'Calendar List'
        );

        return $section_types;
    }

    public static function DefaultContent($default_content, $type)
    {
        if ($type != 'CalendarList') {
            return $default_content;
        }

        $section               = [];
        $section['listTitle']  = "List Title";
        $section['maxItems']   = 15;
        $section['categories'] = [];
        $section['content']    = '<h3 id="list-title">' . $section['listTitle'] . '</h3>';

        return $section;
    }

    public static function GenerateContent_Admin()
    {
        global $addonPathData, $addonRelativeCode, $page;
        static $done = false;
        if ($done || ! common::LoggedIn()) {
            return;
        }
        $page->head_js[]   = $addonRelativeCode . '/assets/js/section_edit.js';
        $page->css_admin[] = $addonRelativeCode . '/assets/css/section_edit.css';
        EventCalendarCommon::Init();
        $categoriesToShow = EventCalendarCommon::$categories;
        $catgroup         = [];
        foreach ($categoriesToShow as $key => $category) {
            $catgroup[] = array('id' => $key, 'label' => $category['label']);
        }
        $page->head_script .= "\nvar EventCalendarCategories = " . json_encode($catgroup) . ";\n";

        $done = true;
    }

    public
    static function SaveSection(
        $return,
        $section,
        $type
    ) {
        global $page;
        if ($type != 'CalendarList') {
            return $return;
        }

        $page->file_sections[$section]['content']   = '';
        $page->file_sections[$section]['listTitle'] = $_POST['listTitle'];
        $page->file_sections[$section]['maxItems']  = $_POST['maxItems'];
        if (isset($_POST['categories'])) {
            $page->file_sections[$section]['categories'] = $_POST['categories'];
        } else {
            $page->file_sections[$section]['categories'] = [];
        }

        return true;
    }

    public static function SectionToContent($section_data, $section_index)
    {
        if ($section_data['type'] != 'CalendarList') {
            return $section_data;
        };

        $section_data['content'] .= EventCalendarCommon::CreateList($section_data);

        return $section_data;
    }


}

