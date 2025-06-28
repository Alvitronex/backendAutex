
# BackendAutex

BackendAutex es una API RESTful construida con Laravel para la gestión de vehículos, partes, reportes y licencias.

## Índice

- [Requisitos previos](#requisitos-previos)
- [Instalación](#instalación)
- [Configuración de autenticación](#configuración-de-autenticación)
- [Configuración de base de datos](#configuración-de-base-de-datos)
- [Migraciones y modelos](#migraciones-y-modelos)
- [Comandos útiles](#comandos-útiles)
- [Seeders](#seeders)
- [Controllers](#controllers)


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
Edita el archivo `.env` con los datos de tu base de datos.


## Migraciones y modelos

### Crear migraciones

```bash
php artisan make:migration create_vehicles_table
php artisan make:migration create_features_table
php artisan make:migration create_parts_table
php artisan make:migration create_reports_table
php artisan make:migration create_licenses_table
```

### Crear modelos
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
> ⚠️ **Advertencia:** Este comando eliminará todos los datos de la base de 1datos.

```bash
php artisan migrate:fresh
``` 
### Comandos útiles
```bash
php artisan migrate
php artisan migrate:fresh 
php artisan make:model 
php artisan make:migration 
```

## Seeders
## Crear seeders con datos de prueba 
```bash
php artisan make:seeder UserSeeder
php artisan make:seeder VehicleSeeder
php artisan make:seeder PartSeeder
php artisan make:seeder FeatureSeeder
php artisan make:seeder ReportSeeder
php artisan make:seeder LicenseSeeder
```

## Controllers
### Crear controladores dentro de una carpeta que llamaremos API
```bash
php artisan make:controller API/AuthController
php artisan make:controller API/VehicleController --resource
php artisan make:controller API/PartController --resource
php artisan make:controller API/FeatureController --resource
php artisan make:controller API/ReportController --resource
php artisan make:controller API/LicenseController --resource
```
