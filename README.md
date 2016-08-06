# Single Table Pages

This class is intended as a simple dynamic page solution for PHP applications where the data source is a single MySQL table. It does not require any joins or relationships. If you want dynamic pages, but you want the data source to be as simple as an Excel spreadsheet imported into a MySQL database, this class should help.

## Dependencies

* PHP 5.3.2 or higher
* MySQL 5.5 or higher

## Dev dependencies

* Composer

## Installation

Use composer to bring this into your PHP project. The composer.json should look like this:

```
{
    "require": {
        "usdoj/singletablepages": "dev-master"
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/usdoj/singletablepages.git"
        },
        {
            "type": "vcs",
            "url": "https://github.com/usdoj/csvtomysql.git"
        }
    ]
}
```

After creating a composer.json similar to the above, do a "composer install".

## Usage

To use the library you need to include the autoloader, and then instantiate the object referencing your configuration file. (See below in the Configuration section for more information about this file.) For example:

```
require_once '/path/to/my/libraries/singletablepages/vendor/autoload.php';
$configFile = '/path/to/my/configurations/singletablepages/singletablepages.yml';
$app = new \USDOJ\SingleTablePages\AppWeb($configFile);
```

After that, you simply render the various parts of the page wherever you would like them to appear on the page. You render them by calling the renderColumn() method and passing the name of the column. For example:
```
<div class="my-page-body">
 <?php print $app->renderColumn('MyBodyColumn'); ?>
</div>
```
Additionally, if you have created a Twig template for the entire row called "renderAll.html.twig", you can render that with:
```
<php print $app->renderAll(); ?>
```

## Templating

This library supports Twig templating of individual database columns. You control the location of the Twig template in the configuration file (see singletablepages.yml.dist). Inside this folder, you can optionally create template files named according to the database column, but with an extenstion of ".html.twig". For example, to template the output of the "Title" column, you would create a file called "Title.html.twig". The templates are passed a variable called "row" which contains all of the data for the page. Additionally they are passed a variable called "value" which contains only the data for the column you are templating.

As mentioned above, to use the renderAll() method, you also have to create a Twig template called "renderAll.html.twig". This is useful if you would like to use Twig for a larger chunk of output, instead of just per field.

## Database table

It is up to you to create the database table that you will be using. Some notes:

1. The column names of the database should be the same as the headers (first row) that will be in the Excel/CSV source file.
2. The source data MUST contain a column called "uuid" that contains only unique values per row. IMPORTANT: If the data in this column ever changes, then the URL of that dynamic page will change, possibly breaking bookmarks, links, etc.
3. Any columns you want to render as dates must be of the DATETIME type.

## Importing source data

The library includes a command-line tool for re-importing data from a CSV file. That tool can be run with:
```
./vendor/bin/singletablepages [path-to-config-file] [path-to-source-data]
```
Note that the source data file must be a CSV file.

Tip: You'll probably usually be getting the CSV file from an XLS file. Since Excel has a problem with special characters, a useful command-line tool is "xls2csv" from the "catdoc" library. To install:

* Linux: `apt-get install catdoc`
* Babun: `pact install catdoc`

When using xls2csv, to ensure you don't get encoding issues, specify the destination encoding like so:
```
xls2csv -d utf-8 file.xls > file-utf-8.csv
```

## Configuration

The library depends on configuration in a separate YAML file. See singletablepages.yml.dist for an example. Here is that example config:
