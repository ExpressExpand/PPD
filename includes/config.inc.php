<?php
  //Disable error reporting
  error_reporting(0);

  $domain = "localhost"; //No http:// or trailing / just the domain
  $sitename = "File Empire"; //Site name
  
  $db_host = "localhost";
  $db_user = "root";
  $db_pass = "";
  $db_name = "db";

  //Attempt to connect to the MySQL Server
  $connect = mysql_connect($db_host, $db_user, $db_pass) or die("Cannot connect to the MySQL server -> Are the details correct in config.inc.php?");

  //Attempt to connect to the database
  $select = mysql_select_db($db_name, $connect) or die("Connected to the MySQL server but could not view the database");
  
  /*Pick a secret encryption key
	If someone got into your database and the passwords weren't encrypted, accounts would be compromised.
	By setting a secure key (completely random without meaning), you can protect the passwords of your users.
	Do not change this key after users have begun to register, else their passwords will not work! */
  $key = "prarabuxaCre4awapHaca5h3yapHubawrEtRaq6yepRAhesPEjawasw83AGEnexU";


?>