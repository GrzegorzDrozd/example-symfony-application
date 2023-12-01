## Demo Symfony Application

This is a simple demo application that is using Symfony 6.3.

### Task description

Create API for simple rating system
1. Store new rating for a "vico"
2. Update previous rating for a "vico"
3. Store text comment for a "vico"

### Requirements

1. use symfony php framework
2. use mysql database

## Instalation

1. Checkout project from github.
```shell
git clone git@github.com:GrzegorzDrozd/example-symfony-application.git
```

2. Install dependencies
```shell
composer install
```

3. Check configuration in `.env` file

4. Run database migration and seeding
```shell
 php bin/console doctrine:migrations:migrate
 php bin/console doctrine:fixtures:load
```

5. Run tests:
```shell
php vendor/bin/phpunit tests
```

### Notes
I discovered an issue with `MapRequestPayload` attribute. It seems to have an issue with an array of DTOs. Code works but it might be a bug in Symfony. I will investigate it further.
