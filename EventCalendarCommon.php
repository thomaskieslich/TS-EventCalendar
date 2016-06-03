<?php


class EventCalendarCommon
{
    protected $langExt = [];
    protected $dataDir;
    protected $configurationFile;

    /**
     * @var array
     */
    protected $configuration;

    protected $categoriesFile;

    /**
     * @var array
     */
    protected $categories;

    protected $events;

    public function __construct()
    {
        self::Init();
    }

    public function Init()
    {
        global $addonPathData;
        $this->langExt = $this->GetLangExt();

        $this->dataDir = $addonPathData;

        $this->configurationFile = $this->dataDir . '/configuration.php';
        $this->LoadConfiguration();

        $this->categoriesFile = $this->dataDir . '/categories.php';
        $this->LoadCategories();
    }

    /**
     * Configuration
     */

    protected function LoadConfiguration()
    {
        if (file_exists($this->configurationFile)) {
            include_once $this->configurationFile;
        }

        if (isset($configuration)) {
            $this->configuration = $configuration;
        } else {
            $this->configuration = [
                'title'      => 'EventCalendar',
                'dateFormat' => 'dd.mm.yy'
            ];
        }
    }

    /**
     * Categories
     */

    protected function LoadCategories()
    {
        if (file_exists($this->categoriesFile)) {
            include_once $this->categoriesFile;
        }

        if (isset($categories)) {
            $this->categories = $categories;
        } else {
            $this->categories = [];
        }
    }


    /**
     * load Language
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