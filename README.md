# andre_sharex_uploader
A simple multi user sharex uploader
##Setup
To use simply upload the files to where you want to point your sharex. 
Go into the protected dir and edit the users.php file. You define the users here.
``` php
$users['the user'] = "some password";
$users['engie'] = "engieisadowner And i can pit spaces, no double quotes are allowed less you escape them the the \"";

```
Now you are done in setting the files up. you can add users at anytime and it will simply make the dir using a "hopefully" safe file name.

Next you have to go into upload.php and update the $location variable. It must point to a domain or subdomain along with the path to the folder with upload.php file in it. It must not have a trailing slash at the end!
``` php
//If upload .php is in a folder called 'somefolder' you would do the following
$location = "http://somedomain.tld.com/somefolder";

```

If you are using apache make sure you put in the share folder the .htaccess folder included if you have not done so already. If it is not there it will try to make it.

If you are on ngix please disable scripts in the directory else someone could upload a shell and screw you over. Some info can be found [here](http://stackoverflow.com/questions/22280764/deny-running-scripts-in-nginx-for-specific-url)

Lastly, make sure the protected directory is not allowed to be publicly seen such as thru the use of the included .htaccess file or disabling it per your webserver of choice.

##Sharex Setup

You can get this menu by right-click on the sharex icon.
![Step1](http://andreblue.com/static_things/sharex_setup/step1.png "Step1")

![Step2](http://andreblue.com/static_things/sharex_setup/step2.png "Step2")
This should appear on screen. You need to scroll down on the list till you see "Custom Uploaders" and click on it.

![Step3](http://andreblue.com/static_things/sharex_setup/step3.png "Step3")
Now you need to file in some info.

Make sure Request Url point to the webserver at the upload.php file. You can use subdomains also. So long it is pointed correctly.

Ex: http://somesite.tld/sharex/upload.php

Next thing is that File form name must be 'file' without the quotes.

Now under arguments we need to add 2 of them. 

First one is 'user' without the quotes in the left box. In the right box you enter your username as in the users.php file, such as 'andreblue'. Then you click Add.

Second one is 'passcode' without the quotes in the left box. In the right box you enter the password, such as 'password123' or what ever you chose. Then you click Add.

If you ever need to change the passcode or username, simply click on it and update the new details. Once you have typed them, instead of hitting add, you press Update.

Then lastly, make sure Response type is set to 'Response Text'.

Now you are all ready to go!
