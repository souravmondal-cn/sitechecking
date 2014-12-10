<?php

/*
  This Script is to check a single or a list of sites whether the site is down or not.
  The administrators will get an email if the site is down.
 * Author: Sourav Mondal
 * Version: 1.0.0
 * Date: 10th Dec, 2014
 */

/* ====================================================================================== */
// Turn off all error reporting for security
error_reporting(0);
//set default time time zone
date_default_timezone_set('Asia/Kolkata');
//increase the executation time
ini_set('max_execution_time', 60); //60 seconds = 1 minutes

// function to check site satus
function CheckDomain($domain) {
    $starttime = microtime(true);
    $file = fsockopen($domain, 80, $errno, $errstr, 10);
    $stoptime = microtime(true);
    $status = 0;
    if (!$file) {
        $status = -1;  // Site is down
    } else {
        fclose($file);
        $status = ($stoptime - $starttime) * 1000;
        $status = floor($status);
    }
    return $status;
}

function checkSite() {
    //admin email to notify
    $admin_email = "useradminemail@gmail.com";
    //current check time
    $check_date_time = date("d-M-Y h:i:s a");
    //list of sites to be checked.
    //if you want to add a new site,add it as a new line,remember the last line should not have coma(,)
    $sites = array(
        "www.abcd.com",
        "www.abc.in",
        "www.xyz.org",
        "www.mnop.net"
    );
    //setting initial message to blank
    $message = "";
    for ($i = 0; $i < sizeof($sites); $i++) {
        if (CheckDomain($sites[$i]) == -1) {
            $subject = "One or More Site is Down on : " . $check_date_time; //you can personalize this subject
            $message .= $sites[$i] . " seems to be down on : " . $check_date_time . "<br/><hr/>";
        } else {
            //do something if you wish to do
        }
    }
    $header = "From:useremail@gmail.com \r\n"; //change it according to your email id
    $header .= "MIME-Version: 1.0\r\n"; //additional header for mail
    $header .= "Content-type: text/html\r\n"; //additional header for mail
    if (!empty($message)) {
        mail($admin_email, $subject, $message, $header); //sending mail to notify admin about the down sites
    }
}

//executing the function
checkSite();


/**
 * check site database server is up or not
 **/

function checkSiteDb() {
    
    $site = "http://www.abcd.com";
    //defining db details
    $db_name = "dbaname";
    $db_host = "localhost";
    $db_user = "dbuser";
    $db_password = "dbpass";
    //checking database connection
    $con = mysqli_connect($db_host, $db_user, $db_password, $db_name);
    if (mysqli_connect_errno($con)) {
        $message = "DATABASE ERROR for $site : " . mysqli_connect_error();
        $header = "From:adminemail@gmail.com \r\n"; //change it according to your email id
        $header .= "MIME-Version: 1.0\r\n"; //additional header for mail
        $header .= "Content-type: text/html\r\n"; //additional header for mail
        $admin_email = "useremail@gmail.com";
        $subject = "Database Server is down";
        if (!empty($message)) {
            //sending mail to notify admin about the site db server down
            mail($admin_email, $subject, $message, $header);
        }
    }
}

checkSiteDb();

