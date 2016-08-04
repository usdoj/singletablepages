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
```
# Database credentials: this is required.
database name: myDatabase
database user: myUser
database password: myPassword
database host: localhost
database table: myTable

# Everything else in this document is optional.

# If you want to control what the URL parameter is, set that here. Otherwise the
# default is "stp". For example: http://example.com/my/dynamic/page?stf=123
url parameter: stp

# List the columns that should be output as links, using another columns to
# get the destination URLs. For example, if you wanted to display the row's
# title as a link to a document, you might do something like this:
# (The format should be: Link label : Link URL)
output as links:
    myTitleField: myDocumentURLField

# List the columns that should be output as images. This assumes that the data
# in these columns is the "src" attribute of an image.
#output as images:
#    - myImageSrcField

# List the columns that should function as additional values for another column.
# For example, if you have a Tag and Tag2 column, you could indicate that Tag2
# is just additional values for Tag, and they would both appear together.
columns for additional values:
    # Extra column: Main column
    myDatabaseColumn1: myDatabaseColumn2

# Excel stores dates in a weird way, and it's a waste of processing power to
# convert it dynamically, so we assume that all date columns will be DATETIME
# columns. Consequently, you should list here any columns that are formatted
# as dates in the source Excel spreadsheet, so they can be converted once as
# they are being imported. Otherwise, MySQL will not let you import them.
#convert from excel dates:
#    - myDatabaseColumn1

# Similarly, if the source data is storing dates as Unix timestamps, make sure
# to note that here so that they will be converted into MySQL DATETIME values.
#convert from unix dates:
#    - myDatabaseColumn2

# If any of the database table's columns are DATETIME columns, then you can
# list them here along with a PHP date format string to use when displaying the
# dates on the page.
#date formats:
#    myDatabaseColumn1: 'M Y'
#    myDatabaseColumn2: 'M j, Y'

# If there are any special characters or phrases that need to be altered when
# importing the data from the CSV file, indicate those here. For example, to
# change all occurences of § with &#167; uncomment the lines below.
#text alterations:
#    "§": "&#167;"

# If you are sharing this database with some other application, which is in
# charge of refreshing the data, you may want to disallow any refreshes, so
# avoid problems. If so, set this to true.
disallow imports: false
```
