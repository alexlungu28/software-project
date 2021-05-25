![pipeline](https://gitlab.ewi.tudelft.nl/cse2000-software-project/2020-2021-q4/cluster-13/improving-assessement-procedure/badges/master/pipeline.svg)
# CSE2000 - Improving-Assessement-Procedure
## Group 3C:
Becheanu Luca, Ciurba Alex, Lungu Alexandru, Schoenmaker Melle, Zamfirescu Toma

### Running
Download and install xampp: https://www.apachefriends.org/index.html

Download and install composer: https://getcomposer.org/download/

Download and install nodejs: https://nodejs.org/en/download/

Run the following commands in the terminal:
```
npm install
composer install
php artisan serve
```
### Static analysis
Run the following commands in the terminal:

PHPMD:
```
php vendor/bin/phpmd app/ text phpmd.xml
```

PHPChecksyle:
```
phpcs --standard=PSR2 app
```

### Testing
Run the following command in the terminal:
```
php ./vendor/bin/phpunit
```

### Database Information
Our application is open to any MySQL database, which can be accessed by filling in the corresponding fields in the .env file.
```
DB_CONNECTION=mysql
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

### Docs
Inside the /docs folder, we have our sprint files. 

Agendas written for weekly client and TA meetings are in the /docs/agendas folder.

Assignments such as the project plan, requirements, code of conduct are in the /docs/assignments folder.

Notes from weekly client and TA meetings are in /docs/notes folder.

Sprint retrospectives with tasks accomplished through the respective week can be found in /docs/sprintRetrospectives folder.
