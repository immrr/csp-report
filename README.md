# csp-report

Content Security Policy is super awesome! You should really set them headers!

Csp-report parses CSP violation reports and stores them in a database.

## Setup

1. Deploy csp-report to some webspace.
2. Setup a mysql database and execute setup/db.sql in it.
3. Configure your database in conf/conf.php using conf/conf.php.dist as a template.

## Usage

1. Append 'report-uri your.url/csp-report/report.php;' to your csp headers.
2. Inspect the reports with you favorite database client.

## Meta

https://git.immerda.ch/csp-report/

(c) https://www.immerda.ch 2014. Released under GPL 3.0.

