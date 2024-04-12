# BileMo

Création d'une API

> Openclassrooms PHP/Symfony developer course project 7 : A high-quality REST API to sell cell phones.

## Features

- Provide access to the catalog via an API (Application Programming Interface) for any platform wishing to do so.
- only B2B access (business to business).

## 🔍Need

- Only registered customers can access the APIs.

  - API clients must be authenticated via OAuth or JWT.

    > 💡 Choice between setting up an OAuth server and calling on it (using >the [FOSOAuthServerBundle](https://packagist.org/packages/friendsofsymfony/>oauth-server-bundle)), and using Facebook, Google or LinkedIn.
    >
    > OR
    >
    > Use JWT, need to [check token validity](https://github.com/lexik/>LexikJWTAuthenticationBundle); use of a library is allowed.

- consult the list of BileMo products ;
- view BileMo product details;
- consult the list of registered users linked to a customer on the website;
- consult the details of a registered user linked to a customer;
- add a new user linked to a customer;
- delete a user added by a customer.
- It requires you to expose your data according to the rules of levels 1, 2 and 3 of the Richardson model.
- Return a JSON file
- Caching to optimize API request performance

## Specs

- PHP 8.1
- Symfony 6.4
- JWT authentification

### Required UML diagrams

- use case diagrams
- class diagram
- sequence diagrams

## Install on local webserver

You can install this project on your WAMP, Laragon, MAMP, or other local webserver.
To do so, you will first need to ensure the following requirements are met.

To install this project, you can use [Mamp](https://www.mamp.info/en/windows/) installed on your Computer.
Once your Mamp configuration is up and ready, you can launch the project.

Then go to symfony server:start where you should be able to access the blog.

## Requirements

- You need to have [composer](https://getcomposer.org/download/) on your computer
- Your server needs PHP version 8.0
- MySQL

## Install dependencies

Before running the project, you need to run the following commands in order to install the appropriate dependencies.

```
composer install
```

## Set up your environment

If you would like to install this project on your computer, you will first need to [clone the repo](https://github.com/Getssone/BileMo) of this project using Git.

## Replace with your personal BDD config

1. create .env.local file:
1. Create name of database :

```
DATABASE_URL=mysql://root:password@127.0.0.1:3306/bilemo
```

or

```
DATABASE_URL=mysql://root:root@127.0.0.1:3306/bilemo
```

Start creation :

```
php bin/console doctrine:database:create
```

Create database tables:

```
php bin/console doctrine:schema:update --force
```

Insert a dataset:

```
php bin/console doctrine:fixtures:load
```

Generate token for authentication :

1.  Go to config folder

    - Creates a JWT folder

1.  Create a Private key with :

    - the command :

      `openssl genpkey -out config/jwt/private.pem -aes256 -algorithm RSA -pkeyopt rsa_keygen_bits:4096`

    - Add the key

      example : `password`

1.  Create a Public key with :

    - the command :

      `openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout`

    - Add the key

      example : `password`

1.  Go to config folder

    - Creates a JWT folder

1.  Create a Private key with :

    - the command :

      `openssl genpkey -out config/jwt/private.pem -aes256 -algorithm RSA -pkeyopt rsa_keygen_bits:4096`

    - Add the key

      example : `password`

1.  Create a Public key with :

    - the command :

      `openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout`

    - Add the key

      example : `password`

## Import database files

Once the mamp is launched, go to <http://localhost/bilemo/> on your browser. You need to import my BDD :"localhost.sql" file into your BDD.

## Try on WebSite

Then, go on your favorite browser, try the doc :

![alt text](image-1.png)
<http://127.0.0.1:8000/api/doc>

## Try on Postman

Then, go to the postman, try this API with :

<http://127.0.0.1:8000/api/login_check>

in the body put :

```
{
    "email": "free@free.fr",
    "password": "password"
}
```

copy the Token:

> Token: `###`

In the authorization:
![alt text](image.png)

Then :

1. Use type `Bearer Token`.
1. Paste the Token
   ![alt text](image-2.png)

After enjoy The API BileMo
