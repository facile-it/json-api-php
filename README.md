JSON API Serializer/Deserializer
===============
[![GitHub release](https://img.shields.io/github/release/facile-it/json-api-php.svg)](https://packagist.org/packages/facile-it/json-api-php)
[![Travis](https://img.shields.io/travis/facile-it/json-api-php/master.svg)](https://travis-ci.org/facile-it/json-api-php/branches)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/facile-it/json-api-php.svg)](https://scrutinizer-ci.com/g/facile-it/json-api-php/?branch=master)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/facile-it/json-api-php.svg)](https://scrutinizer-ci.com/g/facile-it/json-api-php/?branch=master)

This library provides `serialize()` and `deserialize()` methods for normal JSON format.

Installation
----
Insert this external repository in your `composer.json` file:
```json
    "repositories": [
        ...,
        {
            "type": "vcs",
            "url": "https://github.com/facile-it/json-api-php.git"
        }
    ]
```
Run `composer require facile/json-api-php`.

Import
----
Use `Facile\JsonApiPhp\Serialization\JsonApiSerializer` or `Facile\JsonApiPhp\Serialization\JsonApiDeserializer` in order to import and use the functionality.
