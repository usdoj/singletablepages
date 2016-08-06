<?php
/**
 * @file
 * Class for preparing for usage of SingleTablePages.
 */

namespace USDOJ\SingleTablePages;

/**
 * Class App
 * @package USDOJ\SingleTablePages
 *
 * Base class for this app.
 */
class App
{
    /**
     * @var \Doctrine\DBAL\Connection
     *   The database connection.
     */
    private $db;

    /**
     * @var \USDOJ\SingleTablePages\Config
     *   The configuration object.
     */
    private $config;

    /**
     * Get the database connection.
     *
     * @return \Doctrine\DBAL\Connection
     */
    public function getDb() {
        return $this->db;
    }

    /**
     * Get the configuration object.
     *
     * @return \USDOJ\SingleTablePages\Config
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * Helper method to get the configuration for a particular key.
     *
     * @param $key
     *   The key to get configuration settings for.
     *
     * @return mixed
     */
    public function settings($key) {
        return $this->getConfig()->get($key);
    }

    /**
     * Start a database query.
     *
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function query() {
        return $this->getDb()->createQueryBuilder();
    }

    /**
     * App constructor.
     *
     * @param $config
     *   The configuration object.
     */
    public function __construct($config) {

        $this->config = $config;

        // Start the database connection.
        $dbConfig = new \Doctrine\DBAL\Configuration();
        $connectionParams = array(
            'dbname' => $this->settings('database name'),
            'user' => $this->settings('database user'),
            'password' => $this->settings('database password'),
            'host' => $this->settings('database host'),
            'port' => 3306,
            'charset' => 'utf8',
            'driver' => 'pdo_mysql',
        );
        $db = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $dbConfig);
        $this->db = $db;
    }
}
