<?php
/**
 * @file
 * Class for creating dynamic pages using a single database table.
 */

namespace USDOJ\SingleTablePages;

/**
 * Class AppWeb
 * @package USDOJ\SingleTablePages
 *
 * Class for the web version of this app.
 */
class AppWeb extends \USDOJ\SingleTablePages\App {

    /**
     * @var array
     *   Associative array of data from the database for this row.
     */
    private $row;

    /**
     * @var \Twig_Environment
     *   Twig instance for templating the output.
     */
    private $twig;

    /**
     * AppWeb constructor.
     *
     * @param $configFile
     *   The configuration file path.
     */
    public function __construct($configFile) {

        $config = new \USDOJ\SingleTablePages\Config($configFile);
        parent::__construct($config);

        $param = $this->settings('url parameter');
        $uuid = empty($_GET[$param]) ? FALSE : $_GET[$param];

        if ($this->settings('require valid uuid') && (empty($uuid) || !is_numeric($uuid))) {
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

        if ($this->settings('require valid uuid') && empty($row)) {
            $this->pageNotFound();
        }
        elseif (empty($row)) {
            $this->row = FALSE;
            return;
        }

        $this->row = $row;

        $templateFolder = $this->settings('template folder');
        if (!empty($templateFolder) && file_exists($templateFolder)) {

            $loader = new \Twig_Loader_Filesystem($templateFolder);
            $this->twig = new \Twig_Environment($loader);
        }

    }

    /**
     * Get the Twig instance.
     *
     * @return \Twig_Environment
     */
    public function getTwig() {
        return $this->twig;
    }

    /**
     * Get the array of row data for this page.
     *
     * @return array
     */
    public function getRow() {
        return $this->row;
    }

    /**
     * Render the value of one column from the row data.
     *
     * @param $column
     *   The name of the database column to render.
     *
     * @return string
     */
    public function renderColumn($column) {
        $val = '';
        $row = $this->getRow();
        if (empty($row)) {
            return '';
        }
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

    /**
     * Render a specific Twig template called "renderAll.html.twig".
     *
     * @return mixed
     * @throws \Exception
     */
    public function renderAll() {
        $row = $this->getRow();
        if (empty($row)) {
            return '';
        }
        $template = 'renderAll.html.twig';
        if (!$this->getTwig() || !$this->getTwig()->getLoader()->exists($template)) {
            throw new \Exception('The AppWeb::renderAll method requires a Twig template called renderAll.html.twig.');
        }
        return $this->getTwig()->render($template, array('row' => $row));
    }

    /**
     * Stop rendering and issue a 404.
     */
    private function pageNotFound() {
        header('HTTP/1.0 404 Not Found');
        echo "<h1>404 Not Found</h1>";
        echo "The page that you have requested could not be found.";
        exit();
    }
}
