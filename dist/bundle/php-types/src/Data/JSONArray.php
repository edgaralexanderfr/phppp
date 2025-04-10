<?php

declare(strict_types=1);

namespace PHPTypes\Data;

use Stringable;

use PHPTypes\ArrayObject;
use PHPTypes\Primitive\JSONInterface;

class JSONArray extends ArrayObject implements Stringable, JSONInterface
{
    protected array $object = [
        JSONType::class => JSONType::class,
        json::class => json::class,
    ];

    public function __construct(JSONType|json ...$values)
    {
        parent::__construct($values);
    }

    public function __toString(): string
    {
        $json = $this->json();

        if ($json === false) {
            return 'false';
        }

        if ($json === null) {
            return 'null';
        }

        return $json;
    }

    public function json(int $flags = 0, int $depth = 512): string|false|null
    {
        return json_encode((array) $this, $flags, $depth);
    }
}
