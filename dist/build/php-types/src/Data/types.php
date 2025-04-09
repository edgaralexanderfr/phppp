<?php

declare(strict_types=1);

namespace PHPTypes\Data;

use stdClass;

class json extends JSONType {};
class json_array extends JSONArray {};

function json(array|stdClass|string $values): json
{
    return new json($values);
}

function json_array(JSONType|json ...$values): json_array
{
    return new json_array(...$values);
}
