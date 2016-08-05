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
            'convert from excel dates' => array(),
            'convert from unix dates' => array(),
            'url parameter' => 'stp',
            'disallow imports' => FALSE,
            'template folder' => '',
            'unique column' => 'uuid',
        );
    }
}
