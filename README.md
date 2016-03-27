# phpmvc_project

This repository builds on [Anax-MVC](https://github.com/mosbth/Anax-MVC). 
It is extended to serve as a Q&A forum, in the style of Stack Overflow, as a project for the course [PHPMVC](http://dbwebb.se/phpmvc/).

Instruction for the project can be found at [dbwebb.se](http://dbwebb.se/phpmvc/kmom10), and the report at [the student server](http://www.student.bth.se/~Sofa15/dbwebb-kurser/phpmvc/kmom10/Anax-MVC/webroot/report).

## Installation

### Download and install external dependecies
This project is based on Anax-MVC which has external dependencies. Install these using composer and the composer.json file in the base directory.

### Config you database connection details
Edit the connection details for your database in the config_mysql.php file in app/config, or using one of the example config files found in vendor/mos/cdatabase/webroot. Place the file in app/config. The installation is tested for MySQL. If you switch config file to for example config_sqlite.php you must update the link in src/DI/CDIFactoryDefault. Search for 'mysql'.

### Use updated CFormElement
This project uses Markdown for all user content, and for ease of use has a live preview editor. This requires an updated version of CFormElement. You will find it in your app/src folder. Move it from app/src/HTMLForm and replace the existing one in vendor/mos/cform/src/HTMLform.

### Setup the database and test content
If you need a rewrite base you can set it in webroot/.htaccess. Point your browser to webroot/setup for setting up the database with the example content. This setup should be protected by an admin login. To do so, after setup, remove the comments found in the route 'setup' in webroot/index. The test admin logs in with username "admin" and password "admin".

The repository ships as a test forum, We Gonna Take Over The World, inhabited by the characters from popular board game Cluedo. To change the base setup for the database tables, see setup files in respective folder in app/src. To change basic layout settings, se theme_grid.php file in app/config.


If any questions should arise, please contact me for assistance.
sofiafahlesson@live.se

