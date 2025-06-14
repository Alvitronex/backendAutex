
# BackendAutex Setup Guide

## 1. Instalar dependencias

### Autenticación (API con autenticación)
```bash
composer require laravel/sanctum 
```
Publicando configuracion de Sanctum
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```




# Configuracion de base de datos
## Migraciones
Tablas de modelos que utilizaremos

### Tabla vehiculo
```bash
php artisan make:migration create_vehicles_table
```
### Tabla Features
```bash
php artisan make:migration create_features_table
```
### Tabla Parts
```bash
php artisan make:migration create_parts_table
```
### Tabla Reports
```bash
php artisan make:migration create_reports_table
```
### Tabla Licenses
```bash
php artisan make:migration create_licenses_table
```

Ejecutar las migraciones

### 1. Crear base de datos
```bash
php artisan migrate 
```
### 2. Comando para reiniciar toda la base de datos pero !Cuidado! borra todo
```bash
php artisan migrate:fresh
``` 
# Creacion de Modelos
### Vehicle
```bash
php artisan make:model Vehicle
```
### Part
```bash
php artisan make:model Part
```
### Feature
```bash
php artisan make:model Feature
```
### Report
```bash
php artisan make:model Report
```
### License
```bash
php artisan make:model License
```