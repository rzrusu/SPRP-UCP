# Singleplayer Roleplay UCP 

This is the UCP I wrote from scratch for the 2024 Singleplayer Roleplay server. It's compatible with [Dignity's SP-RP Gamemode](https://github.com/DignitySAMP/SP-RP). It's development was cut short as the server sadly closed before it could ever be finished. It's only about 20-30% done but theres plenty work done to make continuing it rather easy so I'm open-sourcing it. It's made with Laravel in PHP, using Livewire and AlpineJS for front-end reactivity and TailwindCSS for styling.

# Features
Here are some features I've managed to complete before it was discontinued.
- Sleek Design
- Login / Registration System
- Character Creator
- Admin Record Viewer
- Map Viewer (with properties, markers)
- Player Profile
- Connections Page
- Dashboard Page with Statistics Tracking
- Skin Data Seeder with all SA:MP skins extended data.
- Basic Working Quiz
- Release Notes Page

... and probably some other stuff I forgot.

# Media

Image Gallery
https://imgur.com/a/iSXYTMD

Map Viewer Video
https://youtu.be/EhISmUcTqwk

# Disclaimer 
This project is NOT FINISHED. This is not plug and play, it's a very much work in progress that will require a lot of work to finalize, but the basic skeleton is all there ready to be fleshed out. Anyone ambitious can make short work of it with the right knowledge.

# Installation

## Requirements
- [SP-RP Gamemode Database](https://github.com/DignitySAMP/SP-RP). This UCP is made to use the same DB as the live server, so you need to setup the gamemode before trying to make this work.
- PHP 8.2
- Composer
- Apache & MySQL I recommend [Laragon](https://laragon.org/) for ease of use, XAMPP also works.
- NodeJS & NPM
- Git


## Setup

Once cloned, run the following commands

```bash
# Install Composer dependencies
composer install

# Install npm dependencies
npm install

# Configure env file
# You need to set your database credentials and other relevant info here
cp .env.example .env

# Generate app key
php artisan key:generate

# Migrate ucp tables
# Make sure you set up the SPRP gm before doing this
php artisan migrate

# Seed the database
php artisan db:seed
```

## Development Server

Start the development server on `http://localhost:8000`:

```bash
# npm
npm run dev

# Laravel server
php artisan serve

```

Open the application in your browser at http://127.0.0.1:8000.

Check out the [Laravel Docs](https://laravel.com/docs) for more information.

## License
This repo is under [MIT license](https://opensource.org/licenses/MIT).

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>
