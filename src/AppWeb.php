<?php
/**
 * @file
 * Class for creating dynamic pages using a single database table.
 */

namespace USDOJ\SingleTablePages;

class AppWeb extends \USDOJ\SingleTablePages\App {

    private $row;

    public function __construct($configFile) {

        $config = new \USDOJ\SingleTablePages\Config($configFile);
        parent::__construct($config);

        $param = $this->settings('url parameter');
        $uuid = $_GET[$param];

        if (empty($uuid) || !is_numeric($uuid)) {
            $this->pageNotFound();
        }

        $query = $this->getDb()->createQueryBuilder();
        $query
            ->from($this->settings('database table'))
            ->select('*')
            ->where('uuid = :uuid')
            ->setParameter('uuid', $uuid);

        $row = $query->execute()->fetch();
        if (empty($row)) {
            $this->pageNotFound();
        }

        $this->row = $row;

    }

    public function getRow() {
        return $this->row;
    }

    public function renderColumn($column) {
        $val = '';
        $row = $this->getRow();
        if (!empty($row[$column])) {
            $val = $row[$column];
        }
        return $val;
    }

    private function pageNotFound() {
        header('HTTP/1.0 404 Not Found');
        echo "<h1>404 Not Found</h1>";
        echo "The page that you have requested could not be found.";
        exit();
    }
}
