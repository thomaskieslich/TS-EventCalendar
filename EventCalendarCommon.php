<?php


class EventCalendarCommon
{
    protected $langExt = [];

    protected $dataDir;
    protected $eventData;

    public function __construct()
    {

        self::Init();
    }

    public function Init()
    {
        global $addonPathData;
        $this->langExt = $this->getLangExt();

        $this->dataDir = $addonPathData;

        $this->eventData = $this->getEventData();

    }

    /**
     * load language
     */
    protected function getEventData()
    {
        $eventData = [];
        if (file_exists($this->dataDir . '/data-events.csv')) {
            ini_set("auto_detect_line_endings", "1");
            $source        = @fopen($this->dataDir . '/data-events.csv', "rt");

            var_dump($source);
        }
        return $eventData;
    }

    /**
     * load language
     */
    protected function getLangExt()
    {
        global $config;

        $langfile = '/l10n/' . $config['language'] . '.php';
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