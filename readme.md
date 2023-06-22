# Laravel

Laravel 5.7 Project with Vue2 and [Element](https://github.com/ElemeFE/element)

# Node

Node node-v13.14.0-x64.msi

# php 7.4.33
extension=fileinfo
extension=gd2
extension=pdo_mysql

## Usage

```
git clone https://github.com/l30n/taller.git <project-name>
```

```
cd <project-name>
```

## Commands
npm install
composer install
npm run dev  / watch
php artisan serve 

## If don't have .env file

```
cp .env.example .env
```

```
php artisan key:generate
```

## Basic start up

```
php artisan serve (if not using laravel valet)
```

Visit `http://localhost:8000`, It works!

## Docker start up

You can download [docker](https://www.docker.com/get-started)

Run command

```
docker-compose up -d
```

After starting docker the database is already create run the migrations using the container of docker

```
docker exec talle_php php artisan migrate --seed
```

Visit `http://localhost`, It works!

## Front end build

```
yarn OR npm install
```

```
yarn watch OR npm run watch
```

```
If using laravel valet, visit http://<project-name>.test
```

Now you're ready to start coding!

## More

The articles can help you to deploy it by yourself:

- [Try Laravel 5.3 + Vue2 with Element](http://codesky.me/archives/try-laravel5-vue2-element-en.wind)
- [Laravel 5.3 + Vue2 + Element 试水](http://codesky.me/archives/try-laravel5-vue2-element-cn.wind)
- [Element Docs](https://element.eleme.io/#/en-US)
