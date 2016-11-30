## Bugsnag

A Laravel wrapper for [Bugsnag](http://bugsnag.com) [Laravel](http://laravel.com/docs).

[![Total downloads](https://img.shields.io/packagist/dt/nodes/bugsnag.svg)](https://packagist.org/packages/nodes/bugsnag)
[![Monthly downloads](https://img.shields.io/packagist/dm/nodes/bugsnag.svg)](https://packagist.org/packages/nodes/bugsnag)
[![Latest release](https://img.shields.io/packagist/v/nodes/bugsnag.svg)](https://packagist.org/packages/nodes/bugsnag)
[![Open issues](https://img.shields.io/github/issues/nodes-php/bugsnag.svg)](https://github.com/nodes-php/bugsnag/issues)
[![License](https://img.shields.io/packagist/l/nodes/bugsnag.svg)](https://packagist.org/packages/nodes/bugsnag)
[![Star repository on GitHub](https://img.shields.io/github/stars/nodes-php/bugsnag.svg?style=social&label=Star)](https://github.com/nodes-php/bugsnag/stargazers)
[![Watch repository on GitHub](https://img.shields.io/github/watchers/nodes-php/bugsnag.svg?style=social&label=Watch)](https://github.com/nodes-php/bugsnag/watchers)
[![Fork repository on GitHub](https://img.shields.io/github/forks/nodes-php/bugsnag.svg?style=social&label=Fork)](https://github.com/nodes-php/bugsnag/network)
[![StyleCI](https://styleci.io/repos/48364252/shield)](https://styleci.io/repos/48364252)

## Introduction

We love [Bugsnag](http://bugsnag.com). It's pretty much our most used tool in [Nodes](http://nodesagency.com).

Therefore we decided to make a Laravel wrapper for the service and even managed to squeeze in an additional feature or two.

## 📦 Installation

To install this package you will need:

* Laravel 5.1+
* PHP 5.5.9+

You must then modify your `composer.json` file and run `composer update` to include the latest version of the package in your project.

```json
"require": {
    "nodes/bugsnag": "^2.0"
}
```

Or you can run the composer require command from your terminal.

```bash
composer require nodes/bugsnag:^2.0
```

## 🔧 Setup

Setup service provider in config/app.php

```php
Nodes\ServiceProvider::class,
Nodes\Bugsnag\ServiceProvider::class,
```

Publish config files

```bash
php artisan vendor:publish --provider="Nodes\Bugsnag\ServiceProvider"
```

If you want to overwrite any existing config files use the `--force` parameter

```bash
php artisan vendor:publish --provider="Nodes\Bugsnag\ServiceProvider" --force
```

## ⚙ Usage

After you have added the service provider to the `config/app.php` array, then it pretty much works automatically.

Only thing you need make sure, is that you've entered the correct `API_KEY` in the `config/nodes/bugsnag.php` config file.

### Manually reporting exceptions

It happens once in a while, that you might need to `try {} catch {}` stuff and when you're catching exception you wish to surpress it for the user,
but you would actually also like to be notified about it in Bugsnag. Then you can use the global helper method `bugsnag_report` to that.

```php
function bugsnag_report(\Exception $exception)
```

## 🏆 Credits

This package is developed and maintained by the PHP team at [Nodes Agency](http://nodesagency.com)

[![Follow Nodes PHP on Twitter](https://img.shields.io/twitter/follow/nodesphp.svg?style=social)](https://twitter.com/nodesphp) [![Tweet Nodes PHP](https://img.shields.io/twitter/url/http/nodesphp.svg?style=social)](https://twitter.com/nodesphp)

## 📄 License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)



