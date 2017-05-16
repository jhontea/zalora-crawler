<p align="center"><img src="https://upload.wikimedia.org/wikipedia/commons/c/cc/Zalora-logo-black.png"></p>

<p align="center">
<a href="https://travis-ci.org/jhontea/zalora-crawler"><img src="https://travis-ci.org/jhontea/zalora-crawler.svg?branch=master" alt="Build Status"></a>
</p>

## About App

This app crawling price of items on <a href="https://www.zalora.co.id/">Zalora website</a> to show the changing of their price.


## Package Dependencies
* laravelcollective/html 5.4.*
* guzzlehttp/guzzle ~6.2.0
* barryvdh/laravel-debugbar 2.3
* symfony/dom-crawler: 3.2


## Installation

1. Clone this repository (git clone git@github.com:jhontea/zalora-crawler.git)
2. Create dan configure .env file based on .env.example
3. Run composer install in the root project to install all dependencies including develeopment requirement.
4. Run php artisan key:generate in the root project to generate new Key for new Application.
5. Run php artisan migrate in the root project to migrate the database.
8. Done!
