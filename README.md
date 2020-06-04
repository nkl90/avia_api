###Требования к окружению:
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

После всех проделанных манипуляций, у вас появится каркася для дальнейшей работы над бизнес-логикой и построением API согласно ТЗ.

