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
                'title'          => 'EventCalendar',
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
                $event['categories'] = explode(',', $event['categories']);
                self::$events[]      = $event;
                $start_day[]         = (int)$line[2];
                $start_time[]        = (int)$line[3];
            }
            fclose($file);

            //sort
            array_multisort($start_day, SORT_DESC, $start_time, SORT_ASC, self::$events);

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

    public static function CreateList(int $maxItems = 15, $activeCatgories = [])
    {
        $current_date = getdate();
        $content      = '';

        $content .= '<div class="calendar-list">';
        $i = 0;

        foreach (self::$events as $event) {
            if (
                (int)$event['start_day'] >= $current_date[0]
                && $i < $maxItems
            ) {
                if (empty($activeCatgories)) {
                    $content .= self::CreateEntry($event);
                } else {
                    $result = array_intersect($event['categories'], $activeCatgories);
                    if ( ! empty($result)) {
                        $content .= self::CreateEntry($event);
                    }
                }
            }
            $i++;
        }

        $content .= '</div>';

        return $content;
    }


    public static function CreateEntry($data)
    {
        $entry = '';

        $entry .= '<div class="entry">';
        $entry .= '<div class="head">';
        $entry .= '<h3>' . $data['title'] . '</h3>';
        $entry .= '<div class="date">';

        if ($data['start_day'] > 0) {
            $entry .= '<span class="start-day">' . strftime(self::$configuration['dateFormatSite'], $data['start_day']) . '</span>';
        }

        if ($data['start_time'] > 0) {
            $entry .= '<span class="start-time"> ' . strftime(self::$configuration['timeFormatSite'], $data['start_time']) . '</span>';
        }

        if ($data['end_day'] > 0) {
            $entry .= ' <span class="end-day">' . strftime(self::$configuration['dateFormatSite'], $data['end_day']) . '</span>';
        }

        if ($data['end_time'] > 0) {
            $entry .= '<span class="end-time"> ' . strftime(self::$configuration['timeFormatSite'], $data['end_time']) . '</span>';
        }

        if ($data['categories']) {
            $entry .= '<span class="categories"> ' . join(',', $data['categories']) . '</span>';
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