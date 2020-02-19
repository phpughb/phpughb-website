# phpughb-website

## Setup
* Requires you to have php, composer and symfony cli locally installed
* Clone Repo
```
git clone git@github.com:phpughb/phpughb-website.git
```
* Install dependencies
```
composer install
```
* Start local web server
```
symfony serve
```


## Deployment
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

## PHP CS
* Check files
```
vendor/bin/php-cs-fixer fix --dry-run
```
* Fix files
```
vendor/bin/php-cs-fixer fix
```

## Tests
* Unit  Test
```
bin/phpunit --testsuite=Unit --testdox
```
* Functional Test
```
bin/phpunit --testsuite=Functional --testdox
```