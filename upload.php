<?
$location = "http://somedomain.tld";
$timezone = 'EST'; //Change this if you want it to be in a different time. http://php.net/manual/en/timezones.php are the valid ones.

//Helper function to save code on making somewhat file safe strings
function fileNameString($Name){
    $Name = str_replace("+"," ",$Name);
	$Name = str_replace("-"," ",$Name);
	$Name = str_replace("_"," ",$Name);
    return $Name;
}
//Make sure we have our share dir created. We also add a file to prevent scripts from being ran. 
if(!file_exists("share")){
    mkdir("share");
    $htfile = fopen("share/.htaccess", "w");
    fwrite($htfile, "AddType text/plain .php .php3 .php4 .phtml .pl .py .jsp .asp .htm .shtml .sh .cgi");
    fclose($htfile);
}
//Variables for later
$_error = false;
$_errorMsg = "";
$user = "";
$passcode = "";

//Making sure we have a user and passcode set
if(isset($_POST['user']) && !empty($_POST['user'])){
    $user = (string) $_POST['user'];
}else{
    $_error = true;
    $_errorMsg = "Please add the user name to the request!";
}
if(isset($_POST['passcode']) && !empty($_POST['passcode'])){
    $passcode = (string) $_POST['passcode'];
}else{
    $_error = true;
    $_errorMsg .= " Please add the passcode name to the request!";
}

//Make sure it is safe to continue or return the error string.
if(!$_error){
    
    //Throws an error if we can not get our users file loaded. Used include to be able to prevent require from stoping the script and not showing a nice error message
    (@include_once ('protected/users.php')) OR die("Script Error: Please notify the admin about this. The server can not find user files!");
    
    //If we dont get an array we stop.
    if(!is_array($users)){
        die("Script Error: Users file is corrupt! Ask the admin to check it.");
    }
    
    //A simple check to make sure the user exists.
    if(!($passcodeToCheck = $users[$user])){
        die("Unauthorized Access: User {$user} does not have access to upload files. If you believe this is wrong double check the username you are using or check with your admin.");
    }
    
    //Make sure the passcode is correct and prevent it from being check as a non string with the ===
    if(!($passcodeToCheck === $passcode)){
        die("Unauthorized Access: User {$user} does not exist or the passcode is incorrect. If you believe this is wrong double check the username/passcode you are using or check with your admin.");
    }
    
    //Make sure file is there
    if(!$_FILES['file']['name']){
        die("Error: Failed to recieve file. Make sure the file field is set as 'file' without the quotes!");
    }
    
    //Make sure no file upload errors
    if($_FILES['file']['error']){
        die("Error: There is an issue with the file." . PHP_EOL . "The error is as follows: " . PHP_EOL . $_FILES['file']['error']);
    }
    //Limit files to 10MB in size.
    if($_FILES['file']['size'] > (1024000*10)){
		die("The file is too large! It can not be more then 10MB");
	}
    
    //Get the ext of the file.
    $path = $_FILES['file']['name'];
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    
    //Title defaults to a time stamp
    date_default_timezone_set($timezone);
    $date = date("Y-m-d_H:i:s");
    $title = "{$date}.{$ext}";
   
    if(isset($_POST['title']) && !empty($_POST['title']) && $_POST['title'] !== "%t"){
        $title = (string) $_POST['title'];
        $title = fileNameString($title);
        $title .= $date;
        $title .= "." . $ext;
    }
    
    //Directory check to make sure the user has a safe dir name along with it exisiting. It creates it if it is not there
    $dir = fileNameString($user);
    $dir = "share/" . $dir;
    
    if(!file_exists($dir)){
        mkdir($dir);
    }
    
    //We then move the file to the correct dir and return the url and stop the script.
	move_uploaded_file($_FILES['file']['tmp_name'], $dir . "/" . $title);
    die($location . "/{$dir}/" . $title);
}else{
    die($_errorMsg);
}






/*
The MIT License (MIT)

Copyright (c) 2015 andreblue

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

Can be found at https://github.com/andreblue/andre_sharex_uploader
*/