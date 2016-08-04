<?php
/**
 * @file
 * Example HTML for usage of singletablepages.
 */

// First you need to include the autoload file that Composer generated.
require_once dirname(__FILE__) . '/../vendor/autoload.php';

// Next, reference your config file. Note that this should NOT be publicly
// accessible, so put it outside the document root.
$configFile = dirname(__FILE__) . '/../singletablepages.yml';

// Next create an instance of the AppWeb object.
$app = new \USDOJ\SingleTablePages\AppWeb($configFile);

// Finally you can layout your page as needed. For example, if your page needs
// a Title and Body, corresponding to TitleColumn and BodyColumn columns in the
// MySQL database table, you would do the following.
?>
<html>
<head>
  <title><?php print $app->renderColumn('TitleColumn') ?></title>
</head>
<body>
  <h1><?php print $app->renderColumn('TitleColumn') ?></h1>
  <div class="body">
    <?php print $app->renderColumn('BodyColumn') ?>
  </div>
</body>
</html>
