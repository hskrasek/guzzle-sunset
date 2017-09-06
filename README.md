# Guzzle Middleware Sunset

[![Build Status][travis-image]][travis-url]
[![MIT License][license-image]][license-url]

Watch out for Sunset headers on HTTP responses, as they signify the deprecation (and eventual removal) of an endpoint.

Sunset is an in-development RFC for a HTTP response header, [currently v03][sunset-draft]. Check out [GitHub][sunset-github] for issues and discussion around it's development.

> This specification defines the Sunset HTTP response header field, which indicates that a URI is likely to become unresponsive at a specified point in the future.

[sunset-draft]: https://tools.ietf.org/html/draft-wilde-sunset-header-03
[sunset-github]: https://github.com/dret/I-D/tree/master/sunset-header

The header we're sniffing for looks a little like this:

```
Sunset: Sat, 31 Dec 2018 23:59:59 GMT
```

So long as the server being called is inserting a `Sunset` header to the response with a [HTTP date], this client-side code will do stuff.

[HTTP date]: https://tools.ietf.org/html/rfc7231#section-7.1.1.1

## Install

Via Composer

```bash
composer require hskrasek/guzzle-sunset
```

## Usage

```php
$stack = new \GuzzleHttp\HandlerStack(\GuzzleHttp\choose_handler());
$stack->push(new \HSkrasek\Sunset\SunsetMiddleware($somePsr3Logger));
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Bug reports and pull requests are welcome on GitHub at [hskrasek/guzzle-sunset](https://github.com/hskrasek/guzzle-sunset). This project is intended to be a safe, welcoming space for collaboration, and contributors are expected to adhere to the [Contributor Covenant](http://contributor-covenant.org) code of conduct.

[travis-url]: https://travis-ci.org/hskrasek/guzzle-sunset
[travis-image]: https://travis-ci.org/hskrasek/guzzle-sunset.svg?branch=master

[license-url]: LICENSE
[license-image]: http://img.shields.io/badge/license-MIT-000000.svg?style=flat-square
