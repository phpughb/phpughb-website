# phpughb-website

## Setup local
* Requires you to have php, composer, yarn, docker and symfony cli locally installed
* Clone Repo
```
git clone git@github.com:phpughb/phpughb-website.git
```
* Install PHP dependencies
```
composer install
```
* Build Frontend
```
yarn install
yarn encore dev
```
* Start containers (mysql: 3306, mailhog-smtp: 1025, malhog-webui: 8025)
```
docker-compose up -d
```
* Setup database
```
bin/console doctrine:database:create
bin/console doctrine:schema:create
```
* Start local web server
```
symfony serve
```


## Deploy origin master to production
* Requires you to have deployer locally installed
* Requires you to have access for the phpughb user on our production server
* Checkout master
```
git checkout origin/master
```
* Run PHP CS
* Run Tests
* Start deployment
```
dep deploy production
```

## PHP CS Fixer
* Check files
```
composer run-script cs-check
```
* Fix files
```
composer run-script cs-fix
```

## Unit and functional tests
* Unit  Test
```
bin/phpunit --testsuite=Unit --testdox
```
* Functional Test
```
bin/phpunit --testsuite=Functional --testdox
```
