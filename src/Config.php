<?php
/**
 * @file
 * Class for configuration options for SingleTablePages.
 */

namespace USDOJ\SingleTablePages;

/**
 * Class Config
 * @package USDOJ\SingleTablePages
 *
 * Class for our configuration object.
 */
class Config extends \Noodlehaus\Config
{
    /**
     * Get the defaults for all optional configuration settings.
     *
     * @return array
     */
    protected function getDefaults() {
        return array(
            'text alterations' => array(),
            'convert from excel dates' => array(),
            'convert from unix dates' => array(),
            'url parameter' => 'stp',
            'disallow imports' => FALSE,
            'template folder' => '',
            'unique column' => 'uuid',
            'require valid uuid' => TRUE,
        );
    }
}
