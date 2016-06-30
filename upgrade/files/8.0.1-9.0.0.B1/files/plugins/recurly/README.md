# Recurly PHP Client

[![Build Status](https://travis-ci.org/recurly/recurly-client-php.png?branch=master)](https://travis-ci.org/recurly/recurly-client-php)

The Recurly PHP Client library is an open source library to interact with
Recurly's subscription management from your PHP website. The library interacts
with Recurly's [REST API](https://dev.recurly.com/docs/getting-started).

**Note:** This version uses Recurly API v2. There are substantial differences
between this version of the client library and versions before _0.5.0_. Please
be careful when upgrading.

## Requirements

###cURL and OpenSSL

The PHP library depends on PHP 5.3.0 (or higher) and libcurl compiled with
OpenSSL support. Open up a `phpinfo();` page and verify that under the curl
section, there's a line that says something like:

```
libcurl/7.19.5 OpenSSL/0.9.8g zlib/1.2.3.3 libidn/1.15
```

### Timezone
You will need to specify your server's timezone before using the Recurly PHP client. This is necessary for the library to properly handle datetime conversions. You can do this in your `php.ini` file:

```php
date.timezone = 'America/Los_Angeles'
```

or in your PHP script:

```php
date_default_timezone_set('America/Los_Angeles');
```

## Installation

### Composer

If you're using [Composer](http://getcomposer.org/), you can simply add a
dependency on `recurly/recurly-client` to your project's `composer.json` file.
Here's an example of a dependency on 2.5:

```json
{
    "require": {
        "recurly/recurly-client": "2.5.*"
    }
}
```

### Git

If you already have git, the easiest way to download the Recurly PHP Client is
with the git command:

```
git clone git://github.com/recurly/recurly-client-php.git /path/to/include/recurly
```

### By Hand

Alternatively, you may download the PHP files in the `lib/` directory and place
them within your PHP project.

## Initialization

Load the Recurly library files and set your subdomain and API Key globally:

```php
<?php
require_once('./lib/recurly.php');

/* https://<your-subdomain>.recurly.com */
Recurly_Client::$subdomain = 'your-subdomain';
/* your private API key */
Recurly_Client::$apiKey = '012345678901234567890123456789ab';
```

If you are getting certificate verification errors that look like this:

```
Fatal error: Uncaught exception 'Recurly_ConnectionError' with message 'Could not verify Recurly's SSL certificate.'
```

Then there is likely a problem with your php or libcurl package and it cannot find your system's root CA certificates.
Ideally you would want to fix your installation but if you cannot you can override the path manually:

```php
// Example on my OS X system, the path will be dependent on your system so ask your sysadmin
Recurly_Client::$CACertPath = '/usr/local/etc/openssl/cert.pem';
```

## API Documentation

Please see the [Recurly API](https://dev.recurly.com/docs/getting-started) for more information.

## Unit tests

You can run our unit tests by using Composer to install PHPUnit:

```
$ curl -s https://getcomposer.org/installer | php
$ php composer.phar install --dev
$ vendor/phpunit/phpunit/phpunit Tests/
```

## Support

- [https://support.recurly.com](https://support.recurly.com)
- [stackoverflow](http://stackoverflow.com/questions/tagged/recurly)

## Announcements

- [@recurly](https://twitter.com/recurly)
- [Google Group Announcements](https://groups.google.com/group/recurly-api)

## Contributing Guidelines

Please refer to [CONTRIBUTING.md](CONTRIBUTING.md)
