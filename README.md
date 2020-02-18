JSON API Serializer/Deserializer
===============
[![Build Status](https://travis-ci.com/facile-it/json-api-php.svg?branch=master)](https://travis-ci.com/facile-it/json-api-php)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/facile-it/json-api-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/facile-it/json-api-php/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/facile-it/json-api-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/facile-it/json-api-php/?branch=master)

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
