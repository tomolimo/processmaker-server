NameVirtualHost your_ip_address

#{projectName} virtual host 
<VirtualHost your_ip_address >
  ServerName "your_{projectName}_server"
  DocumentRoot {pathHome}/public_html
  DirectoryIndex index.html index.php
  <Directory  "{pathHome}/public_html">
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
