# Youbook backend

https://symfony.com/doc/current/validation/raw_values.html

https://symfony.com/doc/current/routing.html

https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/reference/association-mapping.html

## Requisitos

- PHP 7.4
- Composer 1.10.7
- Symfony CLI
- Postgres 12

## PostgreSQL

Instalando: 
```shell script
sudo sh -c 'echo "deb http://apt.postgresql.org/pub/repos/apt/ bionic-pgdg main" > /etc/apt/sources.list.d/postgresql.list'
wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | sudo apt-key add -
sudo apt update
sudo apt upgrade
sudo apt install postgresql-12
sudo apt-get install pgadmin4
```

Configurando:
```shell script
sudo su -
su - prostgres
psql
postgres=# CREATE USER youbook
postgres-# WITH SUPERUSER CREATEDB CREATEROLE
postgres-# password '!23Mudar';
postgres-# CREATE DATABASE youbook WITH OWNER youbook;
```

## Criando o projeto e pacotes

- symfony new youbook-backend
- composer require symfony/orm-pack
- composer require annotations
- composer require lexik/jwt-authentication-bundle
- composer require nelmio/cors-bundle
- composer require symfony/validator doctrine/annotations
- composer require symfony/translation
- composer require symfony/monolog-bundle
- composer require symfony/serializer-pack
- composer require --dev doctrine/doctrine-fixtures-bundle
- composer require --dev symfony/maker-bundle
- Configurado no .env a conexão com o banco

Iniciar servidor
```shell script
symfony server:start --no-tls
```

Limpar cache de prod
```shell script
php bin/console cache:clear --env=prod
```

## Gerando JWT

https://www.useapassphrase.com/
https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#prerequisites
```shell script
mkdir -p config/jwt
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```

## Doctrine

```shell script
doctrine:cache:clear-collection-region  Clear a second-level cache collection region
doctrine:cache:clear-entity-region      Clear a second-level cache entity region
doctrine:cache:clear-metadata           Clears all metadata cache for an entity manager
doctrine:cache:clear-query              Clears all query cache for an entity manager
doctrine:cache:clear-query-region       Clear a second-level cache query region
doctrine:cache:clear-result             Clears result cache for an entity manager
doctrine:database:create                Creates the configured database
doctrine:database:drop                  Drops the configured database
doctrine:database:import                Import SQL file(s) directly to Database.
doctrine:ensure-production-settings     Verify that Doctrine is properly configured for a production environment
doctrine:fixtures:load                  Load data fixtures to your database
doctrine:mapping:convert                [orm:convert:mapping] Convert mapping information between supported formats
doctrine:mapping:import                 Imports mapping information from an existing database
doctrine:mapping:info                   
doctrine:migrations:diff                [diff] Generate a migration by comparing your current database to your mapping information.
doctrine:migrations:dump-schema         [dump-schema] Dump the schema for your database to a migration.
doctrine:migrations:execute             [execute] Execute a single migration version up or down manually.
doctrine:migrations:generate            [generate] Generate a blank migration class.
doctrine:migrations:latest              [latest] Outputs the latest version number
doctrine:migrations:migrate             [migrate] Execute a migration to a specified version or the latest available version.
doctrine:migrations:rollup              [rollup] Rollup migrations by deleting all tracked versions and insert the one version that exists.
doctrine:migrations:status              [status] View the status of a set of migrations.
doctrine:migrations:up-to-date          [up-to-date] Tells you if your schema is up-to-date.
doctrine:migrations:version             [version] Manually add and delete migration versions from the version table.
doctrine:query:dql                      Executes arbitrary DQL directly from the command line
doctrine:query:sql                      Executes arbitrary SQL directly from the command line.
doctrine:schema:create                  Executes (or dumps) the SQL needed to generate the database schema
doctrine:schema:drop                    Executes (or dumps) the SQL needed to drop the current database schema
doctrine:schema:update                  Executes (or dumps) the SQL needed to update the database schema to match the current mapping metadata
doctrine:schema:validate                Validate the mapping files
```

Criar o banco de dados
```shell script
php bin/console doctrine:database:create
```

Criar migrations a partir das entitidades
```shell script
php bin/console doctrine:migrations:diff
```

Realizar as migrations.
```shell script
php bin/console doctrine:migrations:migrate
```

```shell script
```

```shell script
```

```shell script
```

### Fixtures 

Executar
```shell script
php bin/console doctrine:fixtures:load
```
Esse comando d aum drop do banco. Pra apenas acrecentar, adicione **--append**.

## Make

```shell script
make:auth                   Creates a Guard authenticator of different flavors
make:command                Creates a new console command class
make:controller             Creates a new controller class
make:crud                   Creates CRUD for Doctrine entity class
make:entity                 Creates or updates a Doctrine entity class, and optionally an API Platform resource
make:fixtures               Creates a new class to load Doctrine fixtures
make:form                   Creates a new form class
make:functional-test        Creates a new functional test class
make:message                Creates a new message and handler
make:messenger-middleware   Creates a new messenger middleware
make:migration              Creates a new migration based on database changes
make:registration-form      Creates a new registration form system
make:reset-password         Create controller, entity, and repositories for use with symfonycasts/reset-password-bundle.
make:serializer:encoder     Creates a new serializer encoder class
make:serializer:normalizer  Creates a new serializer normalizer class
make:subscriber             Creates a new event subscriber class
make:twig-extension         Creates a new Twig extension class
make:unit-test              Creates a new unit test class
make:user                   Creates a new security user class
make:validator              Creates a new validator and constraint class
make:voter                  Creates a new security voter class
```