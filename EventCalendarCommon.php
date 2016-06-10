<?php


class EventCalendarCommon
{
    public static $langExt = [];
    public static $configurationFile;
    public static $configuration;
    public static $categoryFile;
    public static $categories;
    public static $eventFile;
    public static $events;

    public function __construct()
    {
        self::Init();
    }

    public static function Init()
    {
        global $addonPathData, $page, $config;
        $page->head_script .= "\n" . 'var event_lang = "' . $config['language'] . '";';
        self::$langExt = self::GetLangExt();

        $dataDir = $addonPathData;

        self::$configurationFile = $dataDir . '/configuration.php';
        self::$categoryFile      = $dataDir . '/categories.php';
        self::$eventFile         = $dataDir . '/events.csv';

        self::LoadData();
    }

    /**
     * Load Data
     */
    public static function LoadData()
    {
        global $page;

        //Configuration
        if (file_exists(self::$configurationFile)) {
            include_once self::$configurationFile;
        }

        if (isset($configuration)) {
            self::$configuration = $configuration;
        } else {
            self::$configuration = [
                'title'      => 'EventCalendar',
                'dateFormat' => '%d.%m.%Y',
                'timeFormat' => '%H:%M',
            ];
        }

        $page->head_script .= "\n" . 'var event_date_format = "' . self::$configuration['dateFormat'] . '";';

        //Categories
        if (file_exists(self::$categoryFile)) {
            include_once self::$categoryFile;
        }

        if (isset($categories)) {
            self::$categories = $categories;
        } else {
            self::$categories = [];
        }

        //Events
        self::LoadEvents();
    }

    protected static function LoadEvents()
    {
        self::$events = [];
        if (file_exists(self::$eventFile)) {
            $file = @fopen(self::$eventFile, 'r') or die("Error opening file");
            $cols       = fgetcsv($file);
            $start_day  = [];
            $start_time = [];
            while ($line = fgetcsv($file, 2048, ',')) {
                $event = [];
                $c     = 0;
                foreach ($line as $key => $col) {
                    $event[$cols[$c]] = $col;
                    $c++;
                }
                self::$events[] = $event;
                $start_day[]    = (int)$line[2];
                $start_time[]   = (int)$line[3];
            }
            fclose($file);

            //sort
            array_multisort($start_day, SORT_ASC, $start_time, SORT_DESC, self::$events);

            return true;
        }
    }

    /**
     * Load Language
     */
    public static function GetLangExt()
    {
        global $config;

        $langfile = '/languages/' . $config['language'] . '.php';
        $lang_ext = [];

        if (file_exists(dirname(__FILE__) . $langfile)) {
            include dirname(__FILE__) . $langfile;
        } else {
            $langfile = '/l10n/en.php';
            include dirname(__FILE__) . $langfile;

        }

        return $lang_ext;
    }

    public static function CreateList($sectionData = [])
    {
        if ( ! self::$events) {
            return false;
        }
        $current_date = getdate();
        $maxItems     = (isset($sectionData['maxItems'])) ? $sectionData['maxItems'] : 15;
        $content      = '<div class="calendar-list">';
        $i            = 0;

        if (isset($sectionData['listTitle']) && $sectionData['listTitle'] != '') {
            $content .= '<h3>' . $sectionData['listTitle'] . '</h3>';
        }

        foreach (self::$events as $event) {
            if (
                (int)$event['start_day'] >= $current_date[0]
                && $i < $maxItems
            ) {
                if (empty($activeCatgories)) {
                    $content .= self::CreateEntry($event);
                } else if (in_array($event['category'], $activeCatgories)) {
                    $content .= self::CreateEntry($event);
                }
            }
            $i++;
        }

        $content .= '</div>';

        return $content;
    }


    public static function CreateEntry($event)
    {
        $entry = '';

        if ($event['category'] != '') {
            $entry .= '<div class="entry catstyle-' . $event['category'] . '">';
        } else {
            $entry .= '<div class="entry">';
        }

        $entry .= '<div class="head">';
        $entry .= '<h3>' . $event['title'] . '</h3>';
        $entry .= '<div class="date">';

        if ($event['start_day'] > 0) {
            $entry .= '<span class="start-day">' . strftime(self::$configuration['dateFormat'], $event['start_day']) . '</span>';
        }

        if ($event['start_time'] > 0) {
            $entry .= '<span class="start-time"> ' . strftime(self::$configuration['timeFormat'], $event['start_time']) . '</span>';
        }

        if ($event['end_day'] > 0) {
            $entry .= ' <span class="end-day">' . strftime(self::$configuration['dateFormat'], $event['end_day']) . '</span>';
        }

        if ($event['end_time'] > 0) {
            $entry .= '<span class="end-time"> ' . strftime(self::$configuration['timeFormat'], $event['end_time']) . '</span>';
        }

        if ($event['category'] != '') {
            $entry .= '<span class="category" style="color: ' . self::$categories[$event['category']]['color'] . ' !important;"> ' . self::$categories[$event['category']]['label'] . '</span>';
        }
        $entry .= '</div>';

        $entry .= '</div>';
        $entry .= '<div class="body">';
        $entry .= '<p>' . $event['description'] . '</p>';
        $entry .= '</div>';
        $entry .= '</div>';

        return $entry;
    }
}