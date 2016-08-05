<?php
/**
 * @file
 * Class for creating dynamic pages using a single database table.
 */

namespace USDOJ\SingleTablePages;

class AppWeb extends \USDOJ\SingleTablePages\App {

    private $row;
    private $twig;

    public function __construct($configFile) {

        $config = new \USDOJ\SingleTablePages\Config($configFile);
        parent::__construct($config);

        $param = $this->settings('url parameter');
        $uuid = $_GET[$param];

        if (empty($uuid) || !is_numeric($uuid)) {
            $this->pageNotFound();
        }

        $uniqueColumn = $this->settings('unique column');

        $query = $this->getDb()->createQueryBuilder();
        $query
            ->from($this->settings('database table'))
            ->select('*')
            ->where("$uniqueColumn = :uuid")
            ->setParameter('uuid', $uuid);

        $row = $query->execute()->fetch();
        if (empty($row)) {
            $this->pageNotFound();
        }

        $this->row = $row;

        $templateFolder = $this->settings('template folder');
        if (!empty($templateFolder) && file_exists($templateFolder)) {

            $loader = new \Twig_Loader_Filesystem($templateFolder);
            $this->twig = new \Twig_Environment($loader);
        }

    }

    public function getTwig() {
        return $this->twig;
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
        // Does a Twig template exist?
        $twigTemplate = $column . '.html.twig';
        if ($this->getTwig() && $this->getTwig()->getLoader()->exists($twigTemplate)) {
            // If so, render it.
            $val = $this->getTwig()->render($twigTemplate, array(
                'row' => $row,
                'value' => $val,
            ));
        }

        return $val;
    }

    public function renderAll() {
        $template = 'renderAll.html.twig';
        if (!$this->getTwig() || !$this->getTwig()->getLoader()->exists($template)) {
            throw new \Exception('The AppWeb::renderAll method requires a Twig template called renderAll.html.twig.');
        }
        return $this->getTwig()->render($template, array('row' => $this->getRow()));
    }

    private function pageNotFound() {
        header('HTTP/1.0 404 Not Found');
        echo "<h1>404 Not Found</h1>";
        echo "The page that you have requested could not be found.";
        exit();
    }
}
