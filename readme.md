# INTRO

> get code:
````
git clone https://github.com/chancellorPro/uitschool
````
> [Enable mod rewrite](https://stackoverflow.com/questions/869092/how-to-enable-mod-rewrite-for-apache-2-2)


````
 - sudo a2enmod rewrite
````
````
sudo gedit /etc/apache2/apache2.conf
````
````
<Directory /var/www/>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
</Directory>
````
````
 - sudo service apache2 restart
````
> Put access to /public/avatars
````
sudo chmod -R 777 public/avatars
````
