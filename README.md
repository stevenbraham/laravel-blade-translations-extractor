# Laravel Blade Translations Extractor

A Laravel package that automatically scans Blade template files and PHP files for translatable strings and exports them to JSON translation files.

I could not find a suitable existing Laravel i18n package that could both scan Blade template files and export them to JSON with a `null` key. All existing solutions only supported `__`, did not capture all strings correctly, or could not add new strings as null to the JSON files etc.
So I made this package with the help of Cursor and ChatGPT.

With a JSON file containing all strings as null values, you can easily translate them using a tool like [POEdit](https://poedit.net/).

## Overview

This package helps you maintain your Laravel translation files by automatically extracting translatable strings from your Blade templates and PHP files. It scans for strings used with the `__()` helper function and `@lang()` Blade directive, then merges them into JSON translation files.

## Features

- 🔍 Automatically scans Blade templates (`.blade.php`) and PHP files (`.php`)
- 📝 Extracts translatable strings from `__()` and `@lang()` calls
- 🔄 Merges new strings into existing translation files without overwriting existing translations
- 🎯 Configurable regex pattern for custom translation function detection
- 📦 Zero configuration required - works out of the box

## Installation

Install the package via Composer:

```bash
composer require stevenbraham/laravel-blade-translations-extractor
```

The package will automatically register its service provider if you're using Laravel's package auto-discovery.

## Usage

### Basic Usage

Run the extraction command with your desired locale:

```bash
php artisan extract:translations en
```

This will:
1. Scan all `.blade.php` files in `resources/views/`
2. Scan all `.php` files in `app/`
3. Extract all translatable strings found in `__()` and `@lang()` calls
4. Merge new strings (with `null` values) into `lang/en.json`
5. Preserve existing translations in the JSON file

### Example

If you have a Blade template like this:

```blade
<h1>{{ __('Welcome to our website') }}</h1>
<p>@lang('Please sign in to continue')</p>
```

Running `php artisan extract:translations en` will create or update `lang/en.json`:

```json
{
    "Please sign in to continue": null,
    "Welcome to our website": null
}
```

You can then fill in the translations manually or use a translation management tool.

## Configuration

### Publishing the Config File

To customize the extraction pattern, publish the configuration file:

```bash
php artisan vendor:publish --tag=extract-translations-config
```

This will create `config/laravel-blade-translations-extractor.php`.

### Customizing the Pattern

The default pattern matches `__()` and `@lang()` helper functions. You can customize it in the config file:

```php
return [
    'pattern' => '/(?:__|@lang)\(\s*(["\'])(.*?)\1\s*[\),]/m',
];
```