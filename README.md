# plu-csv-calculator
This PHP appliction calculates the total value of each PLU sold over the time period provided in the source CSV document.  It gives you the option to export the results as a CSV file.  **Requires PHP 7.1.3 or greater.**

## Installation
1. Clone this repository: `git clone https://github.com/TannSan/symfony-console-marvel-api-searcher.git`
2. Download dependancies: `composer update`

## Usage
The syntax is:
```
php console.php [CSV File Name]
```

At the command line in the project root directory:

```
php console.php "PLU Data.csv"
```

To have pretty colors on Windows:

```
php console.php "PLU Data.csv" --ansi
```
Note that you have to wrap the file name in quotes if there is a space.

## Dependencies
* [symfony/console](https://github.com/symfony/console)