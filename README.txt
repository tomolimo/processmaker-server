ProcessMaker's "README.txt" file

Contents:
  Overview
  More Information and license
  Requirements for Server
  Requirements for Clients
  PHP Configuration
  MySQL Configuration
  ProcessMaker Installation
  Credits

--------------
|* Overview *|
--------------
ProcessMaker is an open source, workflow management software suite, which 
includes tools to automate your workflow, design forms, create documents, assign
roles and users, create routing rules, and map an individual process quickly and
easily. It's relatively lightweight and doesn't require any kind of installation
on the client computer. This file describes the requirements and installation 
steps for the server.

----------------------------------
|* More information and licence *|
----------------------------------
ProcessMaker - Automate your Workflow
Copyright (C) 2002 - 2011 Colosa Inc.

Licensed under the terms of the GNU Affero General Public License version 3:
http://www.affero.org/oagpl.html

For further information visit:
http://www.processmaker.com/

-----------------------------
|* Requirements for Server *|
-----------------------------
* Linux or UNIX or Windows (XP, Vista, 7, Server 2003, Server 2008)  

* MySQL 5.1.37 or greater

* Apache 2.2.3 or greater, with the following modules:
  * Deflate
  * Expires
  * Rewrite
  * Vhost_alias
 
* PHP 5.1.6 or greater with the following libraries:
  * mysql
  * xml
  * mbstring
  * mcrypt
  * soap (necessary if using web services)
  * ldap (necessary if integrating with LDAP or Active Directory)
  * gd   (recommended if using Events)
  * curl (necessary for uploading/downloading files) 

  * Also install PHP's command line interface (CLI) if planning on using Events, 
    the Case Scheduler, workspace backup/restore, or developing plugins with the 
    Gulliver Framework. 

------------------------------
|* Requirements for Clients *|
------------------------------
Mozilla FireFox (recommended) 
  or 
Internet Explorer 7 or later     

-----------------------
|* PHP Configuration *|
-----------------------
In the PHP configuration file (php.ini), set the following settings:
  memory_limit = 120M
  file_uploads = On
  short_open_tag = On 
The memory_limit may be a minimum of 80MB, but it is recommended to set it to 
120MB. If planning on uploading large Input Documents and attached files, then 
increase the max_post_size and upload_max_filesize to larger than the default 
2MB:
  max_post_size = 2M 
  upload_max_filesize = 2M

After editing php.ini, restart the Apache server for the new configuration to 
take effect

-------------------------
|* MySQL Configuration *|
-------------------------
In MySQL, give a user superuser privileges to create and update the databases 
used by ProcessMaker. Either create a new user for this purpose or use an 
existing user.

Login to MySQL.
 mysql -u root -p
Enter the root password for MySQL. If you haven't yet set a root password for 
MySQL, set one for better security:
 mysqladmin -u root password '''''PASSWORD'''''
If you have forgotten the root password, see these instructions to reset it:
 http://dev.mysql.com/doc/refman/5.1/en/resetting-permissions.html

If unable to log into MySQL because there is no socket, then MySQL needs to be 
started as a service.

Once in MySQL, give the user who will be running ProcessMaker superuser 
privileges to create create and modify MySQL databases:
 grant all on *.* to 'USER'@'localhost' identified by 'PASSWORD' with grant option;

Replace 'USER' with the name of your MySQL user and 'PASSWORD' with the password
for that user. (If that user doesn't already exist, he/she will be automatically
created with the above grant command. To avoid creating a new user, use 'root' 
as the user and the root's password.) If you are running ProcessMaker on a 
different server than your MySQL server, then replace 'localhost' with the 
domain name or IP address of the server where ProcessMaker is located.
 
Finally, exit MySQL: 
 mysql> exit;

-------------------------------
|* ProcessMaker Installation *|
-------------------------------
1. Download the latest ProcessMaker tarball from:
 http://sourceforge.net/projects/processmaker/files

2. Move the pmos-X.X-XXX.tar.gz file which was downloaded to the directory in 
your server where the ProcessMaker application will be stored. For example, 
"c:\Program Files\" in Windows or "/opt/" in Linux.

The code files are in .tar.gz format and can be extracted using most compression 
tools, like the tar command in Linux/UNIX or WinRAR or 7-Zip in Windows.
 Linux/UNIX:
   tar -xvzf pmos-X.X-XXXX.tar.gz /opt/

 MS WINDOWS:
   Use WinRAR or 7-Zip to extract the file '''<tt>pmos-X.X-XXXX.tar.gz</tt>''' 
   in C:\Program Files\

This will create a new "processmaker" directory, containing all the ProcessMaker 
files and directories.

3. Then, make the following subdirectories writable to the user running Apache: 
 Linux/UNIX:
   chmod 770 /opt/processmaker/shared
   cd /opt/processmaker/workflow/engine/
   chmod 770 config content/languages plugins xmlform js/labels
   chown -R apache-user:apache-user /opt/processmaker
   
   Replace "apache-user", with the user running Apache in your distribution.
   In RedHat/CentOS/Fedora: 
      chown -R apache:apache /opt/processmaker 
    In Debian/Ubuntu:
      chown -R www-data:www-data /opt/processmaker
    In SUSE/OpenSUSE: 
      chown -R chown wwwrun:www -R /opt/processmaker

--------------------------
|* Apache Configuration *|
--------------------------
1. Edit the file "<PROCESSMAKER-DIRECTORY>/etc/pmos.conf" with a plain
text editor (such as Notepad or Notepad++ in Windows or vim, nano or gedit in 
Linux/UNIX).

Modify the following virtual host definition to match your environment:
-----------------------------------------------------------------------
  # Please change the IP address with your server's IP address and
  # the ServerName with you own subdomain for ProcessMaker.
  NameVirtualHost your_ip_address
  #processmaker virtual host
  <VirtualHost your_ip_address >
    ServerName "your_processmaker_domain"
    DocumentRoot /opt/processmaker/workflow/public_html
    DirectoryIndex index.html index.php
    <Directory  "/opt/processmaker/workflow/public_html">
       AddDefaultCharset UTF-8
       AllowOverRide none
       Options FollowSymlinks
       Order allow,deny
       Allow from all
       RewriteEngine on
       RewriteRule ^.*/(.*)$ sysGeneric.php [NC,L]
       ExpiresActive On
       ExpiresDefault "access plus 1 day"
       ExpiresByType image/gif "access plus 1 day"
       ExpiresByType image/png "access plus 1 day"
       ExpiresByType image/jpg "access plus 1 day"
       ExpiresByType text/css "access plus 1 day"
       ExpiresByType text/javascript "access plus 1 day"
       AddOutputFilterByType DEFLATE text/html
    </Directory>
  </VirtualHost>
--------------------------------------------------------------------

Replace your_ip_address with the IP number or domain name of the server running 
ProcessMaker. If only planning on running and accessing ProcessMaker on your 
local machine, then use the IP address "127.0.0.1". If using ProcessMaker on a 
machine whose IP address might change (such as a machine whose IP address is 
assigned with DHCP), then use "*", which represents any IP address. If not using 
the standard port 80, then it is necessary to also specify the port number.

If your DNS or /etc/hosts has a defined domain for ProcessMaker, then use that 
domain for your_processmaker_domain. Otherwise, use the same IP address for your_processmaker_domain as was used for your_ip_address.

If ProcessMaker is installed in a location other than /opt/processmaker/, then 
edit the paths to match where Processmaker is installed on your system.

For example, if running ProcessMaker on a Windows server at address 
192.168.1.100 on port 8080 with a domain at processmaker.mycompany.com:
------------------------------------------------------------------------
  NameVirtualHost 192.168.1.100:8080
  #processmaker virtual host
  <VirtualHost 192.168.1.100:8080 >
    ServerName "processmaker.mycompany.com"
    DocumentRoot C:\Program Files\processmaker\workflow\public_html
    DirectoryIndex index.html index.php
    <Directory  "C:\Program Files\processmaker\workflow\public_html">
    ...
------------------------------------------------------------------------

Note: It is also possible to define the virtual host for ProcessMaker directly 
in the Apache configuration by inserting the above VirtualHost definition in the 
general Apache configuration file, generally named "httpd.conf".

Then, copy the pmos.conf file to the following directory, where it will 
automatically be loaded by the Apache web server:

    Generic Linux/UNIX: 
       /etc/httpd/conf.d/pmos.conf 
    Debian/Ubuntu: 
       /etc/apache2/sites-available/ 
       Then issue the command: a2ensite pmos.conf 
    WINDOWS: 
       C:\wamp\bin\apache\apache2.2.8\conf\extra\pmos.conf 

If using Windows, add the following line to the httpd.conf file, so that the 
ProcessMaker virtual configuration can proceed:

  Include "C:\wamp\bin\apache\apache2.2.8\conf\extra\pmos.conf"


Note: If Apache is using the default port 80, then configure Skype and other 
programs to not use port 80. You can check whether a program is currently 
listening on port 80 with netstat -anb in Windows or netstat -tanp in 
Linux/UNIX.

5. Finally restart the Apache service (or reboot) to make the new ProcessMaker 
site available


--------------------------------
|* ProcessMaker Configuration *|
--------------------------------
1. Open your web browser and direct it to the IP address (and port) or domain 
name where ProcessMaker is installed:
 http://ip-address
If installed on the same machine, then use: 
 http://localhost 
   
The web browser should be redirected to the address:
 http://ip-address/sys/en/green/login

2. The installation configuration page should appear to setup ProcessMaker. 
(If the default Apache page appears, then disable it and restart Apache.)

In the configuration page, enter in the username and password to access MySQL. 
Click on the '''Test''' button to verify that ProcessMaker is configured 
properly. Green checkmarks will indicate that the settings work correctly.

To change a setting after clicking "Test", click on "Reset". To change the 
default administrator username and password, select the option in the 
"ProcessMaker Configuration" section. Once all the settings are properly 
configured click on "Install" to install processmaker. The installation screen 
should indicate "SUCCESS".

Click on "Finish Installation", to redirect to the ProcessMaker login screen. If
an error arises, feel free to ask in the ProcessMaker forum at: 
 http://forum.processmaker.com

At the login screen, enter the Username of "admin" and the Password of "admin" 
and the Workspace name, which by default is "workflow". 

Once logged in as the administrator, new users and processes can be created 
inside ProcessMaker. To login with a different workspace, language or skin, see: 
 http://wiki.processmaker.com/index.php/Login 

If the ProcessMaker configuration screen appears the next time you try to login, 
press CTRL+F5 to clear your web browser's cache.
 
'''Note:''' It is a good idea to reset the administrator's password to something 
more secure in the future before using ProcessMaker in production.


-------------
|* Credits *|
-------------
ProcessMaker - Automate your Workflow
Copyright (C) 2002-2011 Colosa
http://www.processmaker.com/

Last Update: 2011-02-22, amosbatto AT colosa DOT com