<?php
/**
 * @file
 * Class for configuration options for SingleTablePages.
 */

namespace USDOJ\SingleTablePages;

class Config extends \Noodlehaus\Config
{
    protected function getDefaults() {
        return array(
            'text alterations' => array(),
            'output as links' => array(),
            'convert from excel dates' => array(),
            'convert from unix dates' => array(),
            'date formats' => array(),
            'output as images' => array(),
        );
    }
}
