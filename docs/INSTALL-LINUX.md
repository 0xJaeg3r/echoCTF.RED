# echoCTF.RED Linux Installation Instructions

These instructions will guide you in installing the web interfaces on any linux based on Debian.

## Using Docker
Clone the base repository and build the docker image
```sh
docker build -f contrib/Dockerfile . -t echoctf_red
```
Start a container with the image
```sh
docker run -it echoctf_red bash
```

Create a user for the `backend` interface
```sh
./backend/yii user/create username email password
```

Create a player for the `frontend` interface
```sh
./backend/yii player/register username email fullname password offense 1
```

Set the mail from address for new registrations
```sh
./backend/yii sysconfig/set mail_from dontreply@example.red
```

## Install from source
The guide assumes Debian 10 (buster).

### Install the needed packages
```sh
apt-get update
apt-get install build-essential gcc git mariadb-server mariadb-client mcrypt \
memcached libmemcached-dev apache2 libtool libmariadbclient-dev autoconf \
automake php composer php-gd php-mbstring php-mysqli php-dom php-intl \
php-curl php-memcache
```

### Enable event scheduler on the database
```sh
echo -e "[mysqld]\nevent_scheduler=on\n" >/etc/mysql/mariadb.conf.d/50-mysqld.cnf
```

### Start the services
```sh
service memcached restart
service mysql restart
```

### Clone the needed repositories
```sh
cd /var/www
git clone --depth 1 https://github.com/echoCTF/memcached_functions_mysql.git
git clone --depth 1 https://github.com/echoCTF/echoCTF.RED.git
```

### Build the memcached udf
```sh
cd /var/www/memcached_functions_mysql
./config/bootstrap
./configure --with-mysql=/usr/bin/mariadb_config
make
cp src/.libs/libmemcached_functions_mysql.so /usr/lib/x86_64-linux-gnu/mariadb19/plugin/
mysql mysql < sql/install_functions.sql
```

### Prepare the database
```sh
cd /var/www/echoCTF.RED
mysqladmin create echoCTF
mysql echoCTF<schemas/echoCTF.sql
mysql echoCTF<schemas/echoCTF-routines.sql
mysql echoCTF<schemas/echoCTF-triggers.sql
mysql echoCTF<schemas/echoCTF-events.sql
sed -e "s/^-- #//g" contrib/mysql-init.sql >/etc/mysql/mysql-init.sql
```

### Copy the sample configuration files and update to reflect your system
```sh
cp backend/config/cache-local.php backend/config/cache.php
cp backend/config/validationKey-local.php backend/config/validationKey.php
cp backend/config/db-sample.php backend/config/db.php
cp frontend/config/memcached-local.php frontend/config/cache.php
cp frontend/config/validationKey-local.php frontend/config/validationKey.php
cp frontend/config/db-local.php frontend/config/db.php
```

NOTE: If you keep the default db.php on the applications, update the mysql authentication plugin to allow root access
```sh
mysql -e "update user set plugin='mysql_native_password' where user='root'" mysql
```

### Create and update permissions for folders needed by the applications
```sh
mkdir -p backend/web/assets frontend/web/assets
chown www-data backend/web/assets frontend/web/assets
chown www-data backend/runtime frontend/runtime
```

### Install composer files
```sh
cd backend
composer install
cd ../frontend
composer install
```

### Run migrations and import initial data
```sh
cd /var/www/echoCTF.RED
./backend/yii migrate --interactive=0
./backend/yii init_data --interactive=0
```

### Create an admin user for the backend.
```sh
./backend/yii user/create username email password
```

### Create the CA keys for signing user certificates
```sh
./backend/yii ssl/create-ca
```

### Set the mail from Sysconfig key
```sh
./backend/yii sysconfig/set mail_from dontreply@example.red
```

### Register an active user from the command line
```sh
./backend/yii player/register username email fullname password offense 1
```

### Prepare the webserver

Copy the sample apache2 config and update to reflect your settings.

```sh
a2enmod rewrite
cp contrib/apache2-red.conf /etc/apache2/sites-enabled/echoctf.conf
service apache2 restart
```

The default configuration under `/etc/apache2/sites-enabled/echoctf.conf`, serve the interfaces at `http://frontend.echoctf.red` and `http://backend.echoctf.red`

### Make mysql populate memcache on reboot and service restarts
```sh
echo "init_file=/etc/mysql/mysql-init.sql" >>/etc/mysql/mariadb.conf.d/50-mysqld.cnf
mysql < /etc/mysql/mysql-init.sql
```

### Update Cron
TODO