
## Iniciar servicios
 docker compose up -d

## Mostrar productos 
http://localhost:8000/

## Crear admin
php bin/console app:create-admin admin@test.com pass "Name"

## Ejecutar migración
php bin/console doctrine:migrations:migrate


Endpoints disponibles: 
Método              Ruta            Acceso
POST                /api/login      Público
POST                /api/register   Público
GET                 /api/me         Autenticado
POST                /api/logout     Autenticado

## Logs
Dev  : var/log/dev.log
Prod : php://stderr

## Limpiar cache
php bin/console cache:clear

## Tests

php vendor/bin/phpunit

php vendor/bin/phpunit --coverage-html coverage

php vendor/bin/phpunit tests/Unit/Entity/ProductoEntityTest.php
