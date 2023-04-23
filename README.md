## Requirements


- PHP && COMPOSER
- Docker
- PSQL_PDO
```shell
php -m | grep pdo
apt-get install php-pgsql
```

## useful command
### To install dependencies
```shell
composer install
```
### To start the database
```shell
docker-compose up -d
```

### to generate JWT keys you can use (it generate keys in ./config/jwt/):
```shell
php bin/console lexik:jwt:generate-keypair
```
### To start the app
```shell
symfony serve
```

### To interact with database:
```shell
php bin/console doctrine:database:create
```

```shell

php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

```shell
php bin/console doctrine:schema:update --force
```

### Setup db for tests
drop test database
```shell
php bin/console --env=test doctrine:database:drop --force
```
Prepare the test database
```shell
php bin/console --env=test doctrine:database:create
php bin/console --env=test doctrine:schema:create
php bin/console --env=test doctrine:fixtures:load
```
