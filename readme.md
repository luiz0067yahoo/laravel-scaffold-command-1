
# laravel scaffold command
```bash
composer require luiz0067yahoo/laravel-scaffold-command-1:dev-main
```
## create all necessary files for crud operation with one command
- model
- resource controller, api or just regular crud controller
- migration with fields
- feature tests
- views, if crud is not api
    - index
    - create
    - show
    - edit
- resources, if crud is api
    - resource class
    - resource collection class


```bash
php artisan vendor:publish --tag=scaffold-stubs
```

### usage
```bash
php artisan scaffold your_entity_name

```
or
```bash
php artisan scaffold your_entity_name --api

```
like 
```bash
php artisan scaffold Category --fields=name:string,slug:string,parent_id:foreignId

```
or
```bash
php artisan scaffold Category --fields=name:string,slug:string,parent_id:foreignId --api

```

php artisan delete
```bash

like php artisan scaffold  Category --remove

```
