<?php

declare(strict_types=1);

namespace Facile\JsonApiPhp\Serialization;

use RuntimeException;

class JsonApiDeserializer implements JsonApiDeserializerInterface
{
    /** @var string */
    private const REFERENCE_KEYS_TYPE = 'type';

    /** @var string */
    private const REFERENCE_KEYS_ID = 'id';

    /** @var bool */
    private $flattenedRelationships;

    /** @var array */
    private $referencesContainer = [];

    /**
     * @param array $elements
     * @param bool $flattenedRelationships
     *
     * @return array
     */
    public function deserialize(array $elements, bool $flattenedRelationships = self::DEFAULT_FLATTENED_RELATIONSHIPS): array
    {
        $this->flattenedRelationships = $flattenedRelationships;
        $this->referencesContainer = self::moveReferences($elements);

        return $this->parseData($elements[self::REFERENCE_DATA] ?? []);
    }

    /**
     * @param string $jsonApiString
     * @param bool $flattenedRelationships
     *
     * @throws RuntimeException
     *
     * @return string
     */
    public function deserializeToString(string $jsonApiString, bool $flattenedRelationships = self::DEFAULT_FLATTENED_RELATIONSHIPS): string
    {
        $elements = json_decode($jsonApiString, true);
        if (null === $elements) {
            throw new RuntimeException('Not valid JSON string');
        }

        $outputString = json_encode($this->deserialize($elements, $flattenedRelationships), JSON_PRETTY_PRINT);
        if (false === $outputString) {
            throw new RuntimeException('Error during JSON encoding of the object');
        }

        return $outputString;
    }

    /**
     * @param array $elements
     * @param bool $flattenedRelationships
     *
     * @return array
     */
    public function __invoke(array $elements, bool $flattenedRelationships = self::DEFAULT_FLATTENED_RELATIONSHIPS): array
    {
        return $this->deserialize($elements, $flattenedRelationships);
    }

    /**
     * @param mixed $element
     *
     * @return bool
     */
    private static function isReference($element): bool
    {
        if (false === is_array($element)) {
            return false;
        }

        return true === array_keys_exists([self::REFERENCE_KEYS_TYPE, self::REFERENCE_KEYS_ID], $element);
    }

    /**
     * @param mixed $element
     *
     * @return bool
     */
    private static function isArrayOfReference($element): bool
    {
        if (false === is_array($element)) {
            return false;
        }

        return array_reduce(
            $element,
            static function ($valid, $item): bool {
                return $valid && true === self::isReference($item);
            },
            true
        );
    }

    /**
     * @param array $reference
     *
     * @return array
     */
    private static function keepReferenceKeys(array $reference): array
    {
        if (true === empty($reference)) {
            return $reference;
        }

        $keys = [];
        if (true === array_key_exists(self::REFERENCE_KEYS_TYPE, $reference)) {
            $keys['_' . self::REFERENCE_KEYS_TYPE] = $reference[self::REFERENCE_KEYS_TYPE];
        }

        if (true === array_key_exists(self::REFERENCE_KEYS_ID, $reference)) {
            $keys['_' . self::REFERENCE_KEYS_ID] = is_numeric($reference[self::REFERENCE_KEYS_ID])
                ? (int) $reference[self::REFERENCE_KEYS_ID]
                : $reference[self::REFERENCE_KEYS_ID];
        }

        return $keys;
    }

    /**
     * @param array $elements
     *
     * @return array
     */
    private static function moveReferences(array &$elements): array
    {
        $references = [];
        if (false === array_key_exists(self::REFERENCE_INCLUDED, $elements)) {
            return $references;
        }

        foreach ($elements[self::REFERENCE_INCLUDED] as $reference) {
            if (false === array_keys_exists([self::REFERENCE_KEYS_TYPE, self::REFERENCE_KEYS_ID], $reference)) {
                continue;
            }

            $references[$reference[self::REFERENCE_KEYS_TYPE]][$reference[self::REFERENCE_KEYS_ID]] = $reference;
        }

        unset($elements[self::REFERENCE_INCLUDED]);

        return $references;
    }

    /**
     * @param array $relationship
     *
     * @return array|null
     */
    private function completeRelationship(array $relationship): ?array
    {
        $attributes = $relationship[self::REFERENCE_ATTRIBUTES] ?? [];
        $relationships = $relationship[self::REFERENCE_RELATIONSHIPS] ?? [];

        if (false === empty($this->referencesContainer)
            && false === array_keys_exists([self::REFERENCE_ATTRIBUTES, self::REFERENCE_RELATIONSHIPS], $relationship)
        ) {
            $reference = $this->referencesContainer[$relationship[self::REFERENCE_KEYS_TYPE]][$relationship[self::REFERENCE_KEYS_ID]] ?? null;
            if (null !== $reference) {
                $attributes = $reference[self::REFERENCE_ATTRIBUTES] ?? [];
                $relationships = $reference[self::REFERENCE_RELATIONSHIPS] ?? [];
            }
        }

        unset($relationship[self::REFERENCE_ATTRIBUTES], $relationship[self::REFERENCE_RELATIONSHIPS]);

        $relationships = array_map(
            function ($key, $item) {
                return $this->parseRelationship($key, $item[self::REFERENCE_DATA]);
            },
            array_keys($relationships),
            $relationships
        );

        $completedRelationship = array_merge_recursive(
            self::keepReferenceKeys($relationship),
            $attributes,
            true === empty($relationships)
                ? []
                : array_merge(
                    ...$relationships
                )
        );

        return true === empty($completedRelationship)
            ? null
            : $completedRelationship;
    }

    /**
     * @param string $name
     * @param array $relationship
     * @param bool $arrayOf
     *
     * @return array|null
     */
    private function parseRelationship(string $name, array $relationship, bool $arrayOf = false): ?array
    {
        if (true === empty($relationship)) {
            return $relationship;
        }

        if (true === self::isArrayOfReference($relationship)) {
            return [
                $name => array_map(
                    function ($relationship) use ($name): ?array {
                        return $this->parseRelationship($name, $relationship, true);
                    },
                    $relationship
                ),
            ];
        }

        if (false === self::isReference($relationship)) {
            return $relationship;
        }

        $completeRelationship = $this->completeRelationship($relationship);
        if (true === $arrayOf) {
            return $completeRelationship;
        }

        return [
            $name => $completeRelationship,
        ];
    }

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    private function parseData($data)
    {
        $completedRelationship = $this->completeRelationship($data);
        if (null === $completedRelationship) {
            if (false === is_array($data)) {
                return $data;
            }

            return array_map(
                function ($element) {
                    return $this->parseData($element);
                },
                $data
            );
        }

        return $completedRelationship;
    }
}
