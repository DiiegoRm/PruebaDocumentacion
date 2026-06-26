#FROM php:apache
FROM php:7.3.33-apache

ENV \
  APP_DIR="/var/www/html" \
  APP_PORT="8000"

RUN apt update && apt-get install -y libxml2-dev && apt-get install -y libxslt-dev && apt-get install -y libbz2-dev && apt-get install -y curl && apt-get install -y libgmp-dev && apt-get install -y lua-xmlrpc && apt-get install -y xmlrpc-api-utils
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php && php composer-setup.php --install-dir=/usr/local/bin --filename=composer 
RUN apt-get install -y libldb-dev libldap2-dev
#RUN docker-php-ext-configure ldap
RUN docker-php-ext-install ldap
RUN docker-php-ext-install mysqli 
RUN docker-php-ext-install pdo_mysql 
RUN docker-php-ext-install soap 
RUN docker-php-ext-install sockets 
RUN docker-php-ext-install xmlrpc 
RUN docker-php-ext-install xsl 
#RUN docker-php-ext-install php_opcache 
RUN docker-php-ext-install bz2 
#RUN docker-php-ext-install com_dotnet 
#RUN docker-php-ext-install curl 
RUN docker-php-ext-install exif 
RUN docker-php-ext-install fileinfo 
#RUN docker-php-ext-install gd2 
RUN docker-php-ext-install gettext 
RUN docker-php-ext-install gmp 
RUN docker-php-ext-install intl 
RUN docker-php-ext-install mbstring 

#COPY apache2.conf /etc/apache2
#COPY mime.conf /etc/apache2/mods-enabled/mime.conf
COPY . /var/www/html
RUN chmod -R 777 /var/www/html/data/
COPY php.ini-production "$PHP_INI_DIR/php.ini"
EXPOSE $APP_PORT
WORKDIR $APP_DIR



