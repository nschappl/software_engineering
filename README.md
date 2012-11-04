# GitDocs

##Required Software
* git 
* Mysql 5.5.24
* Apache 2.2.22
* phpMyAdmin 3.4.1



## Configuring the Development Enviroment

This guide assumes you are starting with a fresh Ubuntu 12.04 LTS install.

```Bash
sudo apt-get install git
sudo apt-get install lamp-server^
sudo apt-get install phpmyadmin
```
Note: Do not set default password for your local mysql install this will only make further configuration much more difficult.

Once those packages have been installed there are some configuration changes that are required to use phpmyadmin. If you try to navigate
to localhost/phpmyadmin in the browser it will not work. 

Modify /etc/apache2/apache2.conf. Append the following to the bottom of the file

```
# phpMyAdmin configuration file
Include /etc/phpmyadmin/apache.conf
```

Next you must modify the phpMyAdmin config (/etc/phpmyadmin/config.inc.php) to allow passwordless login (username: root) 
Look for the line that should be commented out and uncomment it, then save the file.
```
//$cfg['Servers'][$i]['AllowNoPassword'] = TRUE;
```

Restart Apache 
```Bash
sudo service apache2 restart
```

You should now be able to access localhost/phpmyadmin.

Next open your /etc/hosts file and modify the following entry
```
127.0.0.1 localhost
```

to look like

```
127.0.0.1 dev.localhost.com
```

This will make sure the browser stores the proper cookies for your localhost website.

The final step of the setup is to configure the apache "site". First you will need to create a developer directory in your home directory. Once you do that clone the repo into that directory.

Once this is done 
```Bash
cd /etc/apache2/sites-available
sudo nano easy-docs
```

Copy the contents of the code snippet below
```
<VirtualHost *:80>
    DocumentRoot "/home/USERNAME/developer/software_engineering"
    ServerName dev.localhost.com

    #Optional, but nice for error tracking
    ##ErrorLog "logs/dev.localhost-error.log"
    ##CustomLog "logs/dev.localhost-access.log" common
    
    <Directory "/home/USER_NAME/developer/software_engineering">
    AllowOverride All
    Order Deny,Allow
    Deny from All
    Allow from 127.0.0.1
    </Directory>    
</VirtualHost>

```

Be sure to replace USER_NAME with the name of your home directory which can be found using the command pwd in the command line.
Then run
```
sudo a2enmod easy-docs
sudo service apache2 restart
```

Your development copy should be accessable @ dev.localhost.com