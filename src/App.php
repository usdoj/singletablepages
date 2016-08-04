<?php
/**
 * @file
 * Class for preparing for usage of SingleTablePages.
 */

namespace USDOJ\SingleTablePages;

class App
{
    private $db;
    private $config;

    public function getDb() {
        return $this->db;
    }

    public function getConfig() {
        return $this->config;
    }

    public function settings($key) {
        return $this->getConfig()->get($key);
    }

    public function query() {
        return $this->getDb()->createQueryBuilder();
    }

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
