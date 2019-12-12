<?php

declare(strict_types=1);

namespace Facile\JsonApiPhp\Serialization;

interface JsonApiInterface
{
    /** @var string */
    public const REFERENCE_ATTRIBUTES = 'attributes';

    /** @var string */
    public const REFERENCE_RELATIONSHIPS = 'relationships';

    /** @var string */
    public const REFERENCE_INCLUDED = 'included';

    /** @var string */
    public const REFERENCE_DATA = 'data';

    /** @var bool */
    public const DEFAULT_FLATTENED_RELATIONSHIPS = true;
}
