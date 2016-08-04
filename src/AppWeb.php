<?php
/**
 * @file
 * Class for creating dynamic pages using a single database table.
 */

namespace USDOJ\SingleTablePages;

class AppWeb extends \USDOJ\SingleTablePages\App {

    private $dateGranularities;
    private $dateFormats;

    public function __construct($configFile) {

        $config = new \USDOJ\SingleTablePages\Config($configFile);
        parent::__construct($config);
    }

    public function renderColumn($column) {
        // @todo
    }

    public function query() {
        // @todo
    }
}
