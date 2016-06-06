<?php


class EventCalendarCommon
{
    protected $langExt = [];
    protected $dataDir;
    protected $configurationFile;
    protected $configuration;
    protected $categoryFile;
    protected $categories;
    protected $eventFile;
    protected $events;

    public function __construct()
    {
        self::Init();
    }

    public function Init()
    {
        global $addonPathData, $page, $config;
        $page->head_script .= "\n" . 'var event_lang = "' . $config['language'] . '";';
        $this->langExt = $this->GetLangExt();

        $this->dataDir = $addonPathData;

        $this->configurationFile = $this->dataDir . '/configuration.php';
        $this->categoryFile      = $this->dataDir . '/categories.php';
        $this->eventFile         = $this->dataDir . '/events.csv';

        $this->LoadData();
    }

    /**
     * Load Data
     */
    protected function LoadData()
    {
        global $page;

        //Configuration
        if (file_exists($this->configurationFile)) {
            include_once $this->configurationFile;
        }

        if (isset($configuration)) {
            $this->configuration = $configuration;
        } else {
            $this->configuration = [
                'title'          => 'EventCalendar',
                'dateFormat'     => 'dd.mm.yy',
                'dateFormatSite' => '%d.%m.%Y',
                'timeFormatSite' => '%H:%M',
            ];
        }

        $page->head_script .= "\n" . 'var event_date_format = "' . $this->configuration['dateFormat'] . '";';

        //Categories
        if (file_exists($this->categoryFile)) {
            include_once $this->categoryFile;
        }

        if (isset($categories)) {
            $this->categories = $categories;
        } else {
            $this->categories = [];
        }

        //Events
        $this->LoadEvents();
    }

    protected function LoadEvents(){
        $this->events = [];
        if (file_exists($this->eventFile)) {
            $file = @fopen($this->eventFile, 'r') or die("Error opening file");
            $cols = fgetcsv($file);
            $start_day = [];
            $start_time = [];
            while ($line = fgetcsv($file, 2048, ',')) {
                $event = [];
                $c     = 0;
                foreach ($line as $key => $col) {
                    $event[$cols[$c]] = $col;
                    $c++;
                }
                $this->events[] = $event;
                $start_day[] = (int)$line[2];
                $start_time[] = (int)$line[3];
            }
            fclose($file);

            //sort
            array_multisort($start_day, SORT_DESC, $start_time, SORT_ASC, $this->events);
            return true;
        }
    }
    /**
     * Load Language
     */
    protected function GetLangExt()
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
}