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

