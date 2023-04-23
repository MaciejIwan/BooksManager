## Requirements

PSQL_PDO
```shell
php -m | grep pdo
apt-get install php-pgsql
```

## useful command
### To start the app
```shell
symfony serve
```
### to generate JWT keys you can use (it generate keys in ./config/jwt/):
```shell
php bin/console lexik:jwt:generate-keypair
```

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

DROP test database
```shell
php bin/console --env=test doctrine:database:drop --force
```
Prepare the test database
```shell
php bin/console --env=test doctrine:database:create
php bin/console --env=test doctrine:schema:create
php bin/console --env=test doctrine:fixtures:load
```
