A barebones PHP framework that I use for prototyping and doing quick 
proof of concept projects. 

More than anything, it is an organizational structure to help me move quickly through
PHP development, taking advantage of different features of PHP for different layers
of the framework. 

For example, the DB access layer utilizes PHP's OO qualities, while the staging pages move
procedurally to get data from the DB access layer, munge that data, and then activate
the template layer. The template layer is written in PHP but is intended to be used
like more restrictive template frameworks. In other words the template layer mainly
reads variables, puts them into the HTML, and makes logical decisions only as they 
pertain to the page layout. 

Core files tend to be pretty well documented, so if you are going to use this,
then take a quick spin through the included files and see what each one is bringing
to the table. There's very little, so it shouldn't be too hard. 

If you'll be using a DB, be sure to read BaseTable.php. It gives you the 4 basics of
create, read, update, and delete, which can get you pretty far, but in many cases you'll 
need to add some additional functions to the child class to hold more complex sql queries. 

There are also a minimum of utility classes to alleviate some of the weaknesses of 
PHP. 
- FPX - Function Parameter Extension - 
	Allows programming by contract, and also allows you to create more flexible 
	functions by specifying sets of parameters that are required and optional. Say 
	goodbye to the obnoxious ordering of params. 
	
- DEBUG - 
	Leave debug statements in your code, and turn on the output when you want. 

- ERROR - 
	A custom error reporter that can be turned on and off
	
- SQL - 
	A class for running SQL queries. Can be passed a function that will be
	executed on each row in the result. Comes with DEBUG and BENCHMARK statements
	built in for easy review later. 

- BENCHMARK - 
	For measuring script execution times. Automatically included for every use of SQL, 
	it just needs to be turned on at the beginning of the script. 
	
- TEST - 
	Has the same activate() functionality as DEBUG and ERROR, but meant to be used 
	for dropping sanity checks into the code that can be turned on whenever desired. 
	

BASIC ORGANIZATION/FILE STRUCTURE:
 * config
 * lib
 	* om
 	* util
 * templates
 	* lib
 	* www
 * www
	* css
	* images
	* js
=============	

* config
	- configuration files. autoload definition, db connection, and definition of 
	constants. 
	- YOU WILL NEED TO UPDATE config.inc.php with your base path and web path
* lib/om
	- the object model. this layer does your DB updates and retrieval. Extend 
	BaseTable.php for quick CRUD functionality
* lib/util
	- the utility functions. i almost never need to touch these. 
* templates
	- HTML templates for modularly building your pages. woot.
* www
	- your htdocs root
	
	
You may notice a lot of name reuse. This is intentional. One page may have
example.php - staging page
example.data.php - form definition
example.tpl.php - main template
example.js.php - inline javascript with dynamic php vars
example.css.php - inline css with dynamic php vars
example.js - straightforward js
example.css - straightforward css

If your IDE lets you search by filename, this becomes very helpful. A search for "example"
gives all files related to that page, each distinguished by it's extension. 
	
