### Требования к окружению
* php7.4-common, php7.4-cli, php7.4-fpm, php7.4-xml
* postgresql-12
* Новая пустая с пустой схемой `public`

### Инструкция по развертыванию

Стянуть репозиторий и установить зависимости:

```
$ git clone git@github.com:nkl90/avia_api.git
$ composer install
```
В корне проекта создать файл `.env.local` и записать в него данные для подключения к базе, например с таким содержимым:

```
DATABASE_URL=pgsql://db_user:db_password@localhost:5432/db_name
```

Поднять миграции и записать в БД фикстуры:

```
$ bin/console doctrine:migrations:migrate
$ bin/console doctrine:fixtures:load
```

Сконфигурировать приватный и публичный ключи для JWT-авторизации:

```
$ mkdir -p config/jwt
$ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
$ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```
и дописать данные о сгенерированных ключах в `.env.local`:

```
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=you_pass_phrase
```