# Laravel 10.x blog

The purpose of this repository is to show good development practices on [Laravel](http://laravel.com/) as well as to present cases of use of the framework's features like:

- [Authentication](https://laravel.com/docs/10.x/authentication)
- API
  - [Sanctum](https://laravel.com/docs/10.x/sanctum)
  - [API Resources](https://laravel.com/docs/10.x/eloquent-resources)
  - Versioning
- [Blade](https://laravel.com/docs/10.x/blade)
- [Broadcasting](https://laravel.com/docs/10.x/broadcasting)
- [Cache](https://laravel.com/docs/10.x/cache)
- [Email Verification](https://laravel.com/docs/10.x/verification)
- [Filesystem](https://laravel.com/docs/10.x/filesystem)
- [Helpers](https://laravel.com/docs/10.x/helpers)
- [Horizon](https://laravel.com/docs/10.x/horizon)
- [Localization](https://laravel.com/docs/10.x/localization)
- [Mail](https://laravel.com/docs/10.x/mail)
- [Migrations](https://laravel.com/docs/10.x/migrations)
- [Policies](https://laravel.com/docs/10.x/authorization)
- [Providers](https://laravel.com/docs/10.x/providers)
- [Requests](https://laravel.com/docs/10.x/validation#form-request-validation)
- [Seeding & Factories](https://laravel.com/docs/10.x/seeding)
- [Testing](https://laravel.com/docs/10.x/testing)
- [Homestead](https://laravel.com/docs/10.x/homestead)

Beside Laravel, this project uses other tools like:

- [Bootstrap 5.x](https://getbootstrap.com/)
- [Pint](https://github.com/laravel/pint)
- [Font Awesome](https://fontawesome.com/)
- [Hotwired](https://hotwired.dev/)
- [Redis](https://redis.io/)
- [spatie/laravel-medialibrary](https://github.com/spatie/laravel-medialibrary)
- [hotwired-laravel/turbo-laravel](https://github.com/hotwired-laravel/turbo-laravel)
- Many more to discover.

## Some screenshots

You can find some screenshots of the application on : [https://imgur.com/a/Jbnwj](https://imgur.com/a/Jbnwj)

## Installation

To create your development environment [follow these instructions](https://laravel.com/docs/10.x/installation).

Setting up your development environment on your local machine:
```bash
$ git clone https://github.com/guillaumebriday/laravel-blog.git
$ cd laravel-blog
$ ./init.mac.sh # if your device is macos
$ ./init.sh # if your device is window
$ ./init.prod.sh # if in product environment
```

### Mailer

You can use [Mailpit](https://github.com/axllent/mailpit) to test your emails in development.

Once installed, open [http://localhost:8025](http://localhost:8025).



This will create a new user that you can use to sign in :
```yml
email: admin@gmail.com
password: 123456
```

And then, compile the assets :
```bash
$ yarn dev # or yarn watch
```



## Useful commands

Run swagger:
```bash
$ php artisan l5-swagger:generate
```

Once generated, open [http://localhost:9999/api/documentation#/](http://localhost:8025).


Start Laravel Horizon:

```bash
$ php artisan horizon
```

Seeding the database :
```bash
$ php artisan db:seed
```

Running tests :
```bash
$ php artisan test
```

Running Laravel Pint :
```bash
$ ./vendor/bin/pint --verbose --test
```

Generating backup :
```bash
$ php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"
$ php artisan backup:run
```

Generating fake data :
```bash
$ php artisan db:seed --class=DevDatabaseSeeder
```

Discover package
```bash
$ php artisan package:discover
```

In development environment, rebuild the database :
```bash
$ php artisan migrate:fresh --seed
```
