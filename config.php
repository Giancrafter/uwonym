<?php
//SITE CONFIG
$SITE_NAME = ''; //INSERT YOUR SITE NAME
$SITE_DOMAIN = ''; //INSERT SITE DOMAIN, FOR EXAMPLE google.com


//MYSQL CONFIG
$DATABASE_HOST = 'localhost';
$DATABASE_USER = ''; //INSERT DB USER HERE
$DATABASE_PASS = ''; //INSERT DB PASSWORD HERE
$DATABASE_NAME = ''; //INSERT DB NAME HERE


//SMTP CONFIG
$SMTP_HOST = ''; //INSERT SMTP HOST HERE
$SMTP_AUTH = true; //ENABLE SMTP AUTHENTICATION
$SMTP_USERNAME = ''; //INSERT SMTP USER NAME HERE
$SMTP_PASSWORD = ''; //INSERT SMTP PASSWORD HERE
$SMTP_ENCRYPTION = 'ssl'; //SET SMTP ENCRYPTION MODE
$SMTP_PORT = 465; //SET SMTP PORT


//CREATING THE ACCOUNTS TABLE
mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
mysqli_query("CREATE TABLE IF NOT EXISTS `accounts` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `username` varchar(50) NOT NULL,
 `password` varchar(255) NOT NULL,
 `email` varchar(100) NOT NULL,
 `activation_code` varchar(50) DEFAULT '',
 `profile_pic` varchar(10000) NOT NULL DEFAULT 'https://images.assetsdelivery.com/compings_v2/kritchanut/kritchanut1406/kritchanut140600093.jpg',
 `age` int(11) NOT NULL DEFAULT '0',
 `reset_token` varchar(50) DEFAULT NULL,
 `gender` int(1) NOT NULL DEFAULT '3',
 `search` int(1) DEFAULT '3',
 `description` varchar(10000) DEFAULT 'Keine Beschreibung :(',
 `location` int(4) NOT NULL DEFAULT '0',
 `hash` varchar(12) NOT NULL DEFAULT '222222222222',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8");
?>
