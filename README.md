
# BackendAutex

BackendAutex es una API RESTful construida con Laravel para la gestión de vehículos, partes, reportes y licencias.

## Índice

- [Requisitos previos](#requisitos-previos)
- [Instalación](#instalación)
- [Configuración de autenticación](#configuración-de-autenticación)
- [Configuración de base de datos](#configuración-de-base-de-datos)
- [Migraciones y modelos](#migraciones-y-modelos)
- [Comandos útiles](#comandos-útiles)
- [Contacto](#contacto)

## Requisitos previos

- PHP >= 8.0
- Composer
- MySQL o similar

## Instalación

```bash
composer install
cp .env.example .env
php artisan key:generate
```


## Configuración de autenticación
```bash
composer require laravel/sanctum 
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```


## Configuración de base de datos
Edita el archivo .env con los datos de tu base de datos.


## Migraciones y modelos

### Crear migraciones

```bash
php artisan make:migration create_vehicles_table
php artisan make:migration create_features_table
php artisan make:migration create_parts_table
php artisan make:migration create_reports_table
php artisan make:migration create_licenses_table
```

### Crear migraciones
```bash
php artisan make:model Vehicle
php artisan make:model Part
php artisan make:model Feature
php artisan make:model Report
php artisan make:model License
```
### Ejecutar migraciones
```bash
php artisan migrate 
```
⚠️ Advertencia: El siguiente comando elimina todos los datos y recrea las tablas:

```bash
php artisan migrate:fresh
``` 
### Comandos útiles
php artisan migrate — Aplica las migraciones
php artisan migrate:fresh — Reinicia la base de datos (elimina todos los datos)
php artisan make:model <Nombre> — Crea un nuevo modelo
php artisan make:migration <Nombre> — Crea una nueva migración

