JSON API Serializer/Deserializer
===============
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
Run `composer require facile/json-api-php`

Importation
----
Use `Facile\JsonApiPhp\Serialization\JsonApiSerializer` or `Facile\JsonApiPhp\Serialization\JsonApiDeserializer` in order to import and use the functionality.
