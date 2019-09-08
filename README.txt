STEP BY STEP GUIDE TO THIS WEB SERVICE.

SETUP
1. start/activate your localhost server.
2. got to your phpMyAdmin application
3. Create a empty phpMyAdmin table called 'bigbellyicecream'
4. Import 'database export' into phpMyAdmin 'bigbellyicecream' table
5. go into function.php and on line 5 change the username and password to match that of YOUR phpMyAdmin account
6. enter into your browser url, 'localhost/BBI_database/view/pages/testingLinks.html' 
7. use links and forms displayed to test the web service
8. use 127.0.0.1 to test domain lock
9. use browser link, 'localhost/BBI_database/model/webService.php?page=getAllposts' to test referer.

Explination of SQL GET. as seen on function.php line 24
In this example I GET all the data from the database i need by first assgning the variable $sql a SQL query, 
this is then called by assigning $sql to $stmt and preparing the query using the database 'conn' 
established in the database connection function. That is then executed by the php and assigned the 
data from the database as $esult which is echoed to the user. If that doesn'y work then the error 
is caught and echo to the user instead.

Explination of session as seen on webService.php line 6
Here i check if tge session or "$_SESSION['sessionControl']" 
has being set by the application and if not i set one 
before commencing with the rest of the code. If a session 
already exsists it continues on with the rest of the code.