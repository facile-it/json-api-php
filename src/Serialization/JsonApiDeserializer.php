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
    private $flattenedRelationships = self::DEFAULT_FLATTENED_RELATIONSHIPS;

    /** @var array */
    private $referencesContainer = [];

    use JsonApiSerializerDeserializerTrait;

    /**
     * @param array $elements
     * @param bool $flattenedRelationships
     *
     * @return array
     */
    public function deserialize(
        array $elements,
        bool $flattenedRelationships = self::DEFAULT_FLATTENED_RELATIONSHIPS
    ): array {
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
    public function deserializeToString(
        string $jsonApiString,
        bool $flattenedRelationships = self::DEFAULT_FLATTENED_RELATIONSHIPS
    ): string {
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
    public function __invoke(
        array $elements,
        bool $flattenedRelationships = self::DEFAULT_FLATTENED_RELATIONSHIPS
    ): array {
        return $this->deserialize($elements, $flattenedRelationships);
    }

    /**
     * @param mixed $element
     *
     * @return bool
     */
    protected static function isReference($element): bool
    {
        if (false === is_array($element)) {
            return false;
        }

        return true === array_keys_exists([self::REFERENCE_KEYS_TYPE, self::REFERENCE_KEYS_ID], $element);
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
     * @param array $relationships
     *
     * @return array
     */
    private static function unnestRelationships(array $relationships): array
    {
        if (true === empty($relationships)) {
            return $relationships;
        }

        foreach ($relationships as $path => $relation) {
            if (self::REFERENCE_DATA === $path
                || self::REFERENCE_ATTRIBUTES === $path
                || self::REFERENCE_RELATIONSHIPS === $path
                || self::REFERENCE_KEYS_ID === $path
                || self::REFERENCE_KEYS_TYPE === $path
            ) {
                continue;
            }

            if (false === is_string($path)) {
                continue;
            }

            $pathParts = explode(self::NESTED_SEPARATOR, $path);
            if (count($pathParts) <= 1) {
                continue;
            }

            $counter = 0;
            foreach ($pathParts as $pathPart) {
                if (true === is_numeric($pathPart)) {
                    continue;
                }

                if (++$counter % 2 === 0) {
                    continue;
                }

                array_splice($pathParts, $counter, 0, self::REFERENCE_DATA);
            }

            $root = &$relationships[array_shift($pathParts)] ?? [];
            while (count($pathParts) >= 1) {
                $currentPath = array_shift($pathParts);
                $root = &$root[is_numeric($currentPath) ? (int) $currentPath : $currentPath] ?? [];
            }

            $root = array_merge_recursive(
                $root ?? [],
                $relation
            );

            unset($relationships[$path]);
        }

        return $relationships;
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

        if (true === $this->flattenedRelationships) {
            $relationships = self::unnestRelationships($relationships);
        }

        if (false === empty($this->referencesContainer)
            && false === array_keys_exists([self::REFERENCE_ATTRIBUTES, self::REFERENCE_RELATIONSHIPS], $relationship)
        ) {
            $reference = self::isReference($relationship)
                ? $this->referencesContainer[$relationship[self::REFERENCE_KEYS_TYPE]][$relationship[self::REFERENCE_KEYS_ID]] ?? null
                : null;
            if (null !== $reference) {
                $attributes = $reference[self::REFERENCE_ATTRIBUTES] ?? [];
                $relationships = $reference[self::REFERENCE_RELATIONSHIPS] ?? [];
            }
        }

        unset($relationship[self::REFERENCE_ATTRIBUTES], $relationship[self::REFERENCE_RELATIONSHIPS]);

        $relationships = array_map(
            function ($key, $item) {
                return $this->parseRelationship($key, $item[self::REFERENCE_DATA] ?? $item);
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
     * @param string $key
     * @param array $relationship
     * @param bool $arrayOf
     *
     * @return array|null
     */
    private function parseRelationship(string $key, array $relationship, bool $arrayOf = false): ?array
    {
        if (true === empty($relationship)) {
            return $relationship;
        }

        $recursiveRelationships = array_filter(
            $relationship,
            static function ($item, $key) {
                return true === is_int($key) || (true === is_array($item) && true === array_key_exists(self::REFERENCE_DATA, $item));
            },
            ARRAY_FILTER_USE_BOTH
        );
        $normalRelationships = array_diff_assoc($relationship, $recursiveRelationships);

        return [
            $key => array_merge(
                $this->completeRelationship($normalRelationships) ?? [],
                ...array_map(
                    function ($subKey, $item) {
                        if (false === is_int($subKey)) {
                            return $this->parseRelationship($subKey, $item[self::REFERENCE_DATA], true);
                        }

                        return [
                            $subKey => $this->completeRelationship($item),
                        ];
                    },
                    array_keys($recursiveRelationships),
                    array_values($recursiveRelationships)
                )
            ),
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
