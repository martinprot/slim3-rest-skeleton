# Slim 3 Very simple REST Skeleton

This is a simple skeleton project for Slim 3 that implements a simple REST API.
Based on [https://github.com/pabloroca/slim3-simple-rest-skeleton] who is based on [https://github.com/moritz-h/slim3-rest-skeleton] who is based on [akrabat's slim3-skeleton](https://github.com/akrabat/slim3-skeleton)

## Purpose

My own fork of Pablo Roca skeleton does not change a lot. Check his reamde.md for using it.

Differences:

- on OAuth verification: I prefered using middleware instead of an oauth base controller.
- added refresh_token grant type
- removed APIRateLimiter
- renamed some class names
- added response serializer

## Information

https://github.com/pabloroca/slim3-simple-rest-skeleton

## Instal steps

Steps to have a running server

##### clone project

```
git clone git@bitbucket.org:martinprot/woops-backend.git
```

##### install composer
from https://getcomposer.org/download/

```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

##### install slim, oauth server, and so on...

```
php composer.phar install
```

##### create oauth db (mysql)

```
CREATE TABLE oauth_clients (client_id VARCHAR(80) NOT NULL, client_secret VARCHAR(80), redirect_uri VARCHAR(2000) NOT NULL, grant_types VARCHAR(80), scope VARCHAR(100), user_id VARCHAR(80), CONSTRAINT clients_client_id_pk PRIMARY KEY (client_id));
CREATE TABLE oauth_access_tokens (access_token VARCHAR(40) NOT NULL, client_id VARCHAR(80) NOT NULL, user_id VARCHAR(255), expires TIMESTAMP NOT NULL, scope VARCHAR(2000), CONSTRAINT access_token_pk PRIMARY KEY (access_token));
CREATE TABLE oauth_authorization_codes (authorization_code VARCHAR(40) NOT NULL, client_id VARCHAR(80) NOT NULL, user_id VARCHAR(255), redirect_uri VARCHAR(2000), expires TIMESTAMP NOT NULL, scope VARCHAR(2000), CONSTRAINT auth_code_pk PRIMARY KEY (authorization_code));
CREATE TABLE oauth_refresh_tokens (refresh_token VARCHAR(40) NOT NULL, client_id VARCHAR(80) NOT NULL, user_id VARCHAR(255), expires TIMESTAMP NOT NULL, scope VARCHAR(2000), CONSTRAINT refresh_token_pk PRIMARY KEY (refresh_token));
CREATE TABLE oauth_scopes (scope TEXT, is_default BOOLEAN);
CREATE TABLE oauth_jwt (client_id VARCHAR(80) NOT NULL, subject VARCHAR(80), public_key VARCHAR(2000), CONSTRAINT jwt_client_id_pk PRIMARY KEY (client_id));
```

##### create project db

At least with a user table:

```
CREATE TABLE IF NOT EXISTS `user` ( `id` int(11) NOT NULL, `email` varchar(100) NOT NULL, `password` varchar(100) NOT NULL, `name` varchar(100) NOT NULL) ENGINE=InnoDB AUTO_INCREMENT=1;
ALTER TABLE `users` ADD PRIMARY KEY (`id`), ADD KEY `email` (`email`);
```

change `OAuth2_CustomStorage` to corresponds to user table, if needed

##### Add data to db:

1. create a `oauth_clients` entry in database, with a `client_id` and a `client_secret`

2. create a user, with an email and the SHA1 of the password https://www.sha1.fr/

## Testing

Firstly, create a vhost toward `project/public`

All tests can be found as a Postman JSON file in `project/test/postman.json`. For Postman use, import the file and create an Environment with `baseurl` var that represents the url prefix before all resources.

##### Test oauth anonymous token creation

*Resource*  
```
POST oauth/token
```

*Headers*   
```
Content-Type:application/x-www-form-urlencoded
```

*Body*
```
client_id:<your client id>
client_secret:<your client secret>
grant_type:client_credentials
```

##### Test oauth loggued user token creation

*Resource*  
```
POST oauth/token
```

*Headers*   
```
Content-Type:application/x-www-form-urlencoded
```

*Body*
```
client_id:<your client id>
client_secret:<your client secret>
grant_type:password
username:<you email>
password:<your password, not SHA1ed>
```

##### Test anonymous authentication

*Resource*  
```
GET test/authenticated?access_token=<your anonymous token>
```

##### Test loggued user authentication

*Resource*  
```
GET test/loggued?access_token=<your anonymous token>
```
