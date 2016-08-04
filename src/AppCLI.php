<?php
/**
 * @file
 * Class for preparing for usage of SingleTablePages from the command line.
 */

namespace USDOJ\SingleTablePages;

class AppCLI extends \USDOJ\SingleTablePages\App
{
    private $sourceFile;

    public function __construct($args) {

        $configFile = empty($args[1]) ? '' : $args[1];
        $sourceFile = empty($args[2]) ? '' : $args[2];

        if (empty($configFile) || empty($sourceFile)) {
            die($this->getUsage());
        }

        if (!is_file($configFile)) {
            die(sprintf('Config file not found at %s', $configFile));
        }

        if (!is_file($sourceFile)) {
            die(sprintf('Source data not found at %s', $sourceFile));
        }

        $this->sourceFile = $sourceFile;
        $config = new \USDOJ\SingleTablePages\Config($configFile);
        parent::__construct($config);
    }

    private function getSourceFile() {
        return $this->sourceFile;
    }

    public function run() {

        if ($this->settings('disallow imports')) {
            die('Imports have been disabled for this application, per the configuration file.');
        }

        // First import the source data.
        $importer = new \USDOJ\CsvToMysql\Importer($this->getConfig(), $this->getSourceFile());
        $importer->run();
    }

    private function getUsage() {
        $ret = 'Usage: singletablepages [config file] [source file]' . PHP_EOL;
        $ret .= '  config file: Path to .yml configuration file' . PHP_EOL;
        $ret .= '  source file: Path to source data (must be a csv file with a header matching the database columns)' . PHP_EOL;
        return $ret;
    }
}
