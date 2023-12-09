# WheelyGO 🚗

> Welcome to WheelyGO, the hassle-free vehicle rental platform!

## Project Overview 🎯

WheelyGO is a web application developed for a startup based in Lyon, France. The primary goal of this platform is to facilitate the rental of vehicles for a specified period. Users can browse a diverse range of vehicles, filter their searches based on various criteria, and easily book their preferred choice.

## Prerequisites 🛟

Before you begin, ensure you have the following installed :

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) 🐳
- [Composer](https://getcomposer.org) 🎼
- [PHP](https://www.php.net) (version > php8.0) 🐘

## How to run the project locally 🛠️

1. Start Docker Desktop 🐳

2. Start by cloning this **repository**.

```bash
git clone https://github.com/B2-Info-23-24/php-vincmgn
```

3. Navigate to the roots of the project and create the following files :

- **Dockerfile** 🐋 :

```DockerFile
FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql

RUN apt-get update && \
    apt-get install -y ssmtp mailutils && \
    rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html/

RUN chmod -R 777 /var/www

RUN pecl install xdebug \
    && apt update \
    && apt install libzip-dev -y \
    && docker-php-ext-enable xdebug \
    && a2enmod rewrite \
    && docker-php-ext-install zip \
    && service apache2 restart

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
```

- **docker-compose.yml** 🐋:

```YML
version: "3"

services:
  web:
    build: .
    ports:
      - "8080:80"
    volumes:
      - ./src:/var/www/html

  mysql:
    image: mysql:latest
    environment:
      MYSQL_ROOT_PASSWORD: my-secret-pw
      MYSQL_DATABASE: wheelygo_db
      MYSQL_USER: my_user
      MYSQL_PASSWORD: my_password
    volumes:
      - db_data:/var/lib/mysql
    ports:
      - "3306:3306"

volumes:
  db_data:

```

4. Now create a **.env** file in src/ directory :

You can use the data written in the docker-compose

- **.env** 🌐 :

```env
# Database
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=wheelygo_db
DB_USERNAME=my_user
DB_PASSWORD=my_password

# MailTrap
MT_USERNAME='75ee5dd0ff8cde'
MT_PASSWORD='50e2f610b3a104'
```

Replace `MT_USERNAME` and `MT_PASSWORD` by your own credentials.
You can create an account on [MailTrap](https://mailtrap.io) to get them.
With this current version, sending mail works with MailTrap.

5. Build **dependencies** and the project 👷🏼

```bash
cd src/
composer i
```

```bash
cd ..
make run
```

Here, let Docker build the project. It can take a few minutes...

4. Create **database** and fill it with **fake datas** 🗃️:

```bash
make docker-pt
```

```docker-prompt
cd App/Core/
php FakeDatas.php
```

➜ If a *SQLSTATE[HY000] [2002] Connection refused error* occurs, you can simply wait for a few seconds. Wait for the container to establish the connection. <br><br>
Here, a **root user** has been created. The **credentials** are prompted in the terminal. You can use them to connect to the website and access to the **admin panel**.

To **quit** the docker command prompt :

```bash
exit
```

6. **Run** the project! 🚀

```bash
make run
```

Now you can access to the website here ➜ http://localhost:8080/

To **stop** the project, you can use this following command :

```bash
make stop
```

## Troobleshootings 🚨

If you are experiencing issues connecting to the database, follow these steps:

**1. Database connection issues** 🔗

- **Check Database Credentials**

Ensure that you are using the correct database credentials as specified in the **docker-compose.yml** file and the **.env** file.
<br>

- **Verify Database Service**

Confirm that the MySQL service is up and running. You can do this by running:

```bash
docker-compose ps
```

- **Logs**

Examine the logs to identify any specific errors or connection issues. You can access the logs using:

```bash
docker-compose logs mysql
```

<br>

**2. Missing PDO / SQL extensions in PHP** 🧩

If you encounter issues related to missing PDO / SQL extensions in PHP, you need to ensure they are enabled.

- **Verify PHP Extensions**

Check if the required extensions are enabled in your php.ini file.

Example php.ini file:

```ini
; Enable pdo_mysql extension
extension=pdo_mysql

; Enable mysqli extension
extension=mysqli
```

- **Restart the Apache service**

After making changes to the php.ini file, restart the Apache service to apply the changes:

```bash
docker-compose exec web service apache2 restart
```

- **Logs**

If issues persist, check the Apache error logs for any relevant error messages:

```bash
docker-compose exec web tail -f /var/log/apache2/error.log
```

These troubleshooting steps should help resolve common issues related to database connections and PHP extensions. If the problem persists, feel free to seek assistance from the author. 🚗✨

## Technos 📚

- PHP / Twig <br>
- MySQL <br>
- HTML / CSS / JavaScript

## Author 👨‍💻

➜ [**@vincmgn**](https://github.com/vincmgn)

## Contributing 🤝

Feel free to contribute to this project by reporting issues and/or suggesting improvements!

Thank you for choosing **WheelyGO** for your vehicle rental needs! 🚗✨
