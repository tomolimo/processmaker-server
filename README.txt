Id: README.txt,v XXX 2008/02/14 12:30:06 pcabero

Overview
--------
ProcessMaker is an open source workflow management software suite, which includes tools to
automate your workflow, design forms, create documents, assign roles and users, create routing rules, 
and map an individual process quickly and easily.
It's relatively lightweight and doesn't require any kind of installation on the client computer.
This file describes the requirements and installation steps for the server.

More information and licence
----------------------------
ProcessMaker - Automate your Processes
Copyright (C) 2002 - 2008 Colosa Inc.

Licensed under the terms of the GNU Affero General Public License version 3:
http://www.affero.org/oagpl.html

For further information visit:
http://www.processmaker.com/

Requirements
------------
- Linux
- Apache 2.2.3 or greater
- PHP 5.1.6 or greater
- MySQL 4.1.20 or greater

If you have the RHEL or Centos Linux distribution, to install and run ProcessMaker, the feature SELinux 
shoud be disabled. See How to disable SELINUX for additional instructions.  
Likewise PHP should be configured with more than 30 MB in the parameter memory_limit. ProcessMaker 
ideally uses 120 MB in this parameter. See How to change the PHP "memory_limit" parameter 
for additional instructions.
Magic quotes in php.ini must be in Off

Installation
------------
1. Download ProcessMaker XXX from http://sourceforge.net/projects/processmaker/ and install it
2. Rename the file "/etc/httpd/conf.d/pmos.conf.rpm" as "/etc/httpd/conf.d/pmos.conf"
3. Ensure that the virtual server "your_processmaker_server" defined in the file 
   "/etc/httpd/conf.d/pmos.conf" coincides with your system configuration.  
   The name of this virtual server is only an example, you can change this name
   according the domain defined in your network.
4. If you don't have the name of the virtual server defined in step 3, you should add this server to your host.
   To do this, you need to edit the file
      /etc/hosts
   In this file you should add the following line:
      your_ip_address your_processmaker_server
5. Finally restart the httpd service

Configuration
-------------
1. Make sure that the "data" and "compiled" subdirectories are world writable (chmod 0777), 
   because the data of your workflows will be saved in the "data" subdirectory, and the subdirectory "compiled" 
   will store the compiled templates of ProccessMaker.
2. Open your browser and load the ProcessMaker site. 
   You should see the test page. If you don't see it, please check the installation steps.
3. On the test page, 
		-  check  your connection and paths
		-  enter your MySQL database account information, which should have privileges to create databases and users.
4. Click on the tab "Install" (in the upper right-hand corner)
5. After few seconds you will get the confirmation of your installation. If it was successful, click on the button 
   "Finish installation" and enjoy ProccessMaker. Otherwise, please visit our forums (http://forum.processmaker.com/) 
   to get support.
6. To login to ProcessMaker use these credentials:
  	user: admin
  	pass: admin   
  	
How to disable SELinux
----------------------
Since ProcessMaker is currently installed and running in the /opt directory, it is
necessary to disable the SELinux feature.
To disactivate the SELinux you need to edit the respective configuration file.
Usually you should find it in:
/etc/selinux/config

In this file you will need to disable the following parameter:
   SELINUX = disable

After that you need to restart your computer, so this change will take effect.

How to change the PHP "memory_limit" parameter
----------------------------------------------
ProccesMaker needs minimum 31MB in the parameter "memory_limit" of PHP. This parameter defines
the maximum amount of memory a script may consume.
To change this value you need to edit the php.ini file.
Usually you should find it in
   /etc/php.ini

In this file you should put the new value (beetween 31 and 120):
   memory_limit = 31MB;

After that, restart the httpd service.

Credits
-------
- ProcessMaker - Automate your Processes
Copyright (C) 2002-2008 Colosa
http://www.processmaker.com/