### Требования к окружению
* php7.4-common, php7.4-cli, php7.4-fpm, php7.4-xml, php7.4-mbstring, php7.4-pgsql
* postgresql-12
* rabbitmq-server
* БД с пустой схемой `public`

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

Сконфигурировать виртуальный хост и пользователя в RabbitMQ:

```
# rabbitmqctl add_vhost vhost_name
# rabbitmqctl add_user username password
# rabbitmqctl set_permissions -p vhost_name username ".*" ".*" ".*"
```

и дописать эти параметры в `.env.local`:

```
RABBITMQ_URL=amqp://username:password@localhost:5672/vhost_name
```

Сконфигурировать подключение к smtp-серверу в файле `.env.local`:


```
MAILER_URL=gmail://gmail_username:gmail_app_password@localhost
MAILER_SEND_FROM=gmail_username@gmail.com
```