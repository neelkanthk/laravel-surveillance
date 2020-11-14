![Laravel Surveillance Logo](https://github.com/neelkanthk/repo_logos/blob/master/LaravelSurveillance_small.png?raw=true)

![](https://img.shields.io/github/v/release/neelkanthk/laravel-surveillance?style=for-the-badge)
![](https://img.shields.io/packagist/php-v/neelkanthk/laravel-surveillance.svg?style=for-the-badge)
![](https://img.shields.io/badge/Laravel-%3E%3D6.0-red?style=for-the-badge)
![](https://img.shields.io/github/stars/neelkanthk/laravel-surveillance?style=for-the-badge)
![](https://img.shields.io/github/issues/neelkanthk/laravel-surveillance?style=for-the-badge)
![](https://img.shields.io/github/license/neelkanthk/laravel-surveillance?style=for-the-badge)

# Laravel Surveillance [![Twitter](https://img.shields.io/twitter/url?style=social&url=https%3A%2F%2Fgithub.com%2Fneelkanthk%2Flaravel-surveillance)](https://twitter.com/intent/tweet?text=Laravel%20Surveillance:&url=https%3A%2F%2Fgithub.com%2Fneelkanthk%2Flaravel-surveillance)

Laravel Surveillance is a package to put malicious users, IP addresses and anonymous browser fingerprints under surveillance, write surveillance logs and block malicious ones from accessing the app.


------------


> **Please read the IMPORTANT INFORMATION below before using this package**

>  **This package collects and processes various attributes that may be Personal Identifiable Information and this should therefore be disclosed and screened before adopting this package. This packages author does not take responsibility for any compliance issues users may face. Please consult legal expertise to use responsibly.**


------------


------------

__This package provides__:

_1. A middleware to be used on routes._

_2. A command line interface to enable/disable surveillance and block/unblock access._

_3. A fluent API to programmatically enable/disable surveillance, block/unblock access and log the requests at runtime._

_4. By default the package used MySQL database as storage but the package can be extended to use virtually any storage technology._

**NOTE: This package does not provide a client side library for browser fingerprinting. [FingerprintJS Open Source](https://github.com/fingerprintjs/fingerprintjs) is a good library to use for client side browser fingerprinting.**

### Important Announcement !

Introducing [Laravel Surveillance UI](https://github.com/neelkanthk/laravel-surveillance-ui) : A package which provides Graphical UI for Laravel Surveillance and integrates within your existing application.

### Minimum Requirements

#### 1. Laravel 6.0
#### 2. PHP 7.2

## Installation

#### 1. Install the package via composer:

```bash
composer require neelkanthk/laravel-surveillance
```

#### 2.1. Publish the migration files:
```bash
php artisan vendor:publish --provider="Neelkanth\Laravel\Surveillance\Providers\SurveillanceServiceProvider" --tag="migrations"
```

#### 2.2. Publish language files:
```bash
php artisan vendor:publish --provider="Neelkanth\Laravel\Surveillance\Providers\SurveillanceServiceProvider" --tag="lang"
```

#### 3. Run the migrations
```bash
php artisan migrate
```

#### 4. After migrations have been run two tables will be created in the database namely `surveillance_managers` and `surveillance_logs`

#### 5. You can publish the config file with:
```bash
php artisan vendor:publish --provider="Neelkanth\Laravel\Surveillance\Providers\SurveillanceServiceProvider" --tag="config"
```

This is the contents of the file that will be published at `config/surveillance.php`:


```php
return [

    /*
     * The name of the header to be used for browser fingerprint
     */
    "fingerprint-header-key" => "fingerprint",

    /*
     *  This class is responsible enabling, disabling, blocking and unblocking.
     *  To override the default functionality extend the below class and provide its name here.
     */
    "manager-repository" => 'Neelkanth\Laravel\Surveillance\Implementations\SurveillanceManagerRepository',

    /*
     *  This class is responsible for logging the surveillance enabled requests
     *  To override the default functionality extend the below class and provide its name here.
     */
    "log-repository" => 'Neelkanth\Laravel\Surveillance\Implementations\SurveillanceLogRepository',

    /*
     *  The types which are allowed currently.
     *  DO NOT MODIFY THESE
     */
    "allowed-types" => ["userid", "ip", "fingerprint"]
];
```

## CLI Usage

#### Enable surveillance for an IP Address
```bash
php artisan surveillance:enable ip 192.1.2.4
```

#### Disable surveillance for an IP Address
```bash
php artisan surveillance:disable ip 192.1.2.4
```

#### Enable surveillance for a User ID
```bash
php artisan surveillance:enable userid 1234
```

#### Disable surveillance for a User ID
```bash
php artisan surveillance:disable userid 1234
```

#### Enable surveillance for Browser Fingerprint
```bash
php artisan surveillance:enable fingerprint hjP0tLyIUy7SXaSY6gyb
```

#### Disable surveillance for Browser Fingerprint
```bash
php artisan surveillance:disable fingerprint hjP0tLyIUy7SXaSY6gyb
```

#### Block an IP Address
```bash
php artisan surveillance:block ip 192.1.2.4
```

#### UnBlock an IP Address
```bash
php artisan surveillance:unblock ip 192.1.2.4
```

#### Block a User ID
```bash
php artisan surveillance:block userid 1234
```

#### UnBlock a User ID
```bash
php artisan surveillance:unblock userid 1234
```

#### Block a Browser Fingerprint
```bash
php artisan surveillance:block fingerprint hjP0tLyIUy7SXaSY6gyb
```

#### UnBlock a Browser Fingerprint
```bash
php artisan surveillance:unblock fingerprint hjP0tLyIUy7SXaSY6gyb
```

#### Remove a Surveillance record from Database
```bash
php artisan surveillance:remove ip 192.5.4.3
```

## Middleware Usage

#### You can use the 'surveillance' middleware on any route or route group just like any other middleware.

### _NOTE: The middleware looks for the browser fingerprint in the header name as set in the `fingerprint-header-key` inside `config/surveillance.php`_

```php
Route::middleware(["surveillance"])->get('/', function () {
    
});
```

## Programmatic Usage

#### Enable Surveillance

```php
use Neelkanth\Laravel\Surveillance\Services\Surveillance;
Surveillance::manager()->type("ip")->value("192.5.4.1")->enableSurveillance();
```

#### Block Access

```php
use Neelkanth\Laravel\Surveillance\Services\Surveillance;
Surveillance::manager()->type("userid")->value(2121)->blockAccess();
```

#### Logging a Request (Works when surveillance in enabled on User ID, IP Address or Browser Fingerprint)

```php
use Neelkanth\Laravel\Surveillance\Services\Surveillance;
Surveillance::logger()->writeLog();
```

## Allowed Types

#### Currently only userid, ip and fingerprint types are allowed.

## Customizing and Overriding the defaults

### To override the default surveillance management functionality

#### Step 1: Extend the `SurveillanceManagerRepository` Class and override all of its methods

```php
//Example repository to use MongoDB instead of MySQL
namespace App;

use Neelkanth\Laravel\Surveillance\Implementations\SurveillanceManagerRepository;
use Illuminate\Support\Carbon;

class SurveillanceManagerMongoDbRepository extends SurveillanceManagerRepository
{
    public function enableSurveillance()
    {
        $surveillance = $this->getRecord();
        if (is_null($surveillance)) {
            $surveillance["type"] = $this->getType();
            $surveillance["value"] = $this->getValue();
        }
        $surveillance["surveillance_enabled"] = 1;
        $surveillance["surveillance_enabled_at"] = Carbon::now()->toDateTimeString();
        $collection = (new \MongoDB\Client)->surveillance->manager;
        $insertOneResult = $collection->insertOne($surveillance);
        return $insertOneResult;
    }
}
```

#### Step 2: Provide the custom class in the `config/surveillance.php` file's `manager-repository` key

```php
/*
 *  This class is responsible enabling, disabling, blocking and unblocking.
 *  To override the default functionality extend the below class and provide its name here.
 */
"manager-repository" => 'App\SurveillanceManagerMongoDbRepository',
```

### To override the default logging funtionality

#### Step 1: Extend the `SurveillanceLogRepository` Class and override all of its methods

```php

//Example repository to write Logs in MongoDB instead of MySQL
namespace App;

use Neelkanth\Laravel\Surveillance\Implementations\SurveillanceLogRepository;

class SurveillanceLogMongoDbRepository extends SurveillanceLogRepository
{
    public function writeLog($dataToLog = null)
    {
        if (!is_null($dataToLog)) {
            $this->setLogToWrite($dataToLog);
        }
        $log = $this->getLogToWrite();
        if (!empty($log) && is_array($log)) {
            $collection = (new \MongoDB\Client)->surveillance->logs;
            $insertOneResult = $collection->insertOne($log);
        }
    }
}
```

#### Step 2: Provide the custom class in the `config/surveillance.php` file's `log-repository` key

```php
/*
 *  This class is responsible for logging the surveillance enabled requests
 *  To override the default functionality extend the below class and provide its name here.
*/
"log-repository" => 'App\SurveillanceLogMongoDbRepository',
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## Security
If you discover any security-related issues, please email me.neelkanth@gmail.com instead of using the issue tracker.

## Credits

- [Neelkanth Kaushik](https://github.com/neelkanthk)
- [All Contributors](../../contributors)
- [CCTV Icon](https://pixabay.com/vectors/image-sign-warning-icon-cctv-3042333)

## License
[MIT](https://choosealicense.com/licenses/mit/)