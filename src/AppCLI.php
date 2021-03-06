<?php
/**
 * @file
 * Class for preparing for usage of SingleTablePages from the command line.
 */

namespace USDOJ\SingleTablePages;

/**
 * Class AppCLI
 * @package USDOJ\SingleTablePages
 *
 * A class for the CLI version of this app.
 */
class AppCLI extends \USDOJ\SingleTablePages\App
{
    /**
     * @var string
     *   The source file of data to import.
     */
    private $sourceFile;

    /**
     * AppCLI constructor.
     *
     * @param $args
     *   The user input from the command line.
     */
    public function __construct($args) {

        $configFile = empty($args[1]) ? '' : $args[1];
        $sourceFile = empty($args[2]) ? '' : $args[2];

        if (empty($configFile) || empty($sourceFile)) {
            die($this->getUsage());
        }

        if (!is_file($configFile)) {
            die(sprintf('Config file not found at %s', $configFile));
        }

        $this->sourceFile = $sourceFile;
        $config = new \USDOJ\SingleTablePages\Config($configFile);
        parent::__construct($config);
    }

    /**
     * Get the source data file path.
     *
     * @return string
     */
    private function getSourceFile() {
        return $this->sourceFile;
    }

    /**
     * Import the source data into the database.
     */
    public function run() {

        if ($this->settings('disallow imports')) {
            die('Imports have been disabled for this application, per the configuration file.');
        }

        $importer = new \USDOJ\SingleTableImporter\Importer($this->getConfig(), $this->getSourceFile());

        // First do a test run.
        $importer->testRun();

        // Next import the source data.
        $importer->run();
    }

    /**
     * Instructions for the usage of the CLI tool.
     *
     * @return string
     */
    private function getUsage() {
        $ret = 'Usage: singletablepages [config file] [source file]' . PHP_EOL;
        $ret .= '  config file: Path to .yml configuration file' . PHP_EOL;
        $ret .= '  source file: Path to source data (must be a CSV or Excel file with a header matching the database columns)' . PHP_EOL;
        return $ret;
    }
}
