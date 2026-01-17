# Mudou

Web application built with [Laravel](https://laravel.com) and [Vue 3](https://vuejs.org).

---

## Requeriments

- [PHP](https://www.php.net)
- [Composer](https://getcomposer.org)
- [Node.js](https://nodejs.org)
- [NPM](https://www.npmjs.com)
- [Docker](https://www.docker.com) (only for tests)

---

## Stack

- **Laravel** (Backend / API)
- **Inertia.js** (Bridge SPA without traditional API REST)
- **Vue 3** (Frontend)
- **Vite** (Build and dev server)
- **PHPUnit** (tests)
- **Laravel Dusk** (tests)

---

## Running

### Setting up the application

First step is to run the composer.json script: setup

```bash
composer run-script setup
```

### Creating the environment variables

As you can see, when running the setup script, a new file will appear: **.env**.

You can edit this variables as you want, but they are ready for use.

### Running

To run the application, just use this command:

```bash
php artisan serve
```

## Running browser tests

### Setting up the application

To setup the application for tests, run this composer.json script: setup:dusk

```bash
composer run-script setup:dusk
```

### Running

To run the tests you will need to run the server, create a docker container with the selenium/standalone-chrome image and finally run the dusk command.

the corresponding command list for this steps:

```bash
php artisan serve
```

```bash
docker compose up
```

```bash
php artisan dusk
```

Thankfully there is 2 custom commands for this:

- `php artisan test:browser`
Will build the front-end, run the server, start the docker container, run the tests, and down the server and container. Will run all the tests.

- `php artisan test:setup` with `php artisan dusk`:
Will run the server and docker container. And run the tests with the dusk options. When you are done running the tests, you can do a ctrl + c to down the server and the container.

## Custom commands

- `php artisan beginsTheServe`
Will run the server and will run the dev package.json script allowing the dev hot reload.

- `php artisan test:repeat-browser`
Will run the tests (or the filtered test with the --filter dusk command option) a amount(default 5) of times