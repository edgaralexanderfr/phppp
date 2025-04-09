<?php

declare(strict_types=1);

namespace PHPTypes;

function typedef(string $type, string $name, ?string $type_name = null): void
{
    if ($type_name === null) {
        $type_name = $name;
        $name = $type;
        $type = 'type';
    }

    if (!in_array($type, ['type', 'array', 'multiple'])) {
        throw new \PHPTypes\Exception('Invalid `$type` to define');
    }

    switch ($type) {
        case 'type':
            define_type($name, $type_name);
            break;
        case 'array':
            define_array_type($name, $type_name);
            break;
        case 'multiple':
            define_multiple_type($name, $type_name);
            break;
    }
}

function define_type(string $name, string $type_name): void
{
    $primitives = [
        'bool' => 'bool',
        'int' => 'int',
        'float' => 'float',
        'string' => 'string',
        'array' => 'array',
    ];

    if (isset($primitives[strtolower($name)])) {
        $type = $name;
        $namespaces = array_map(fn($type_name) => str_replace('\\', '', $type_name), explode('\\', $type_name));
        $last_index = count($namespaces) - 1;
        $class_name = $namespaces[$last_index] ?? '';
        array_pop($namespaces);
        $namespace = implode('\\', $namespaces);

        $code = <<<PHP
            class {$class_name} implements \Stringable, \JsonSerializable
            {
                public function __construct(
                    public {$type} \$value
                ) {}

                public function __serialize(): array
                {
                    return [
                        'value' => \$this->value,
                    ];
                }

                public function __unserialize(array \$data): void
                {
                    \$this->value = \$data['value'];
                }

                public function __toString(): string
                {
                    return (string) \$this->value;
                }

                public function jsonSerialize(): mixed
                {
                    return \$this->value;
                }
            }
        PHP;
    } else if (!class_exists($name)) {
        throw new \PHPTypes\Exception("Class {$name} not defined");
    } else {
        $namespaces = array_map(fn($type_name) => str_replace('\\', '', $type_name), explode('\\', $type_name));
        $last_index = count($namespaces) - 1;
        $class_name = $namespaces[$last_index] ?? '';
        array_pop($namespaces);
        $namespace = implode('\\', $namespaces);

        $code = <<<PHP
            class {$class_name} extends {$name} {};
        PHP;
    }

    /** @disregard */
    if (!defined('PHPTYPES_IGNORE_TYPEDEF_FUNCTIONS') || !PHPTYPES_IGNORE_TYPEDEF_FUNCTIONS) {
        $code = <<<PHP
            {$code}

            function {$class_name}(mixed \$value): {$class_name}
            {
                return new {$class_name}(\$value);
            }
        PHP;
    }

    if ($namespace != '') {
        $code = <<<PHP
            namespace {$namespace};

            {$code}
        PHP;
    }

    eval($code);
}

function define_array_type(string $name, string $array_name): void
{
    if (!class_exists($name)) {
        throw new \PHPTypes\Exception("Class {$name} not defined");
    }

    $namespaces = array_map(fn($name) => str_replace('\\', '', $name), explode('\\', $name));
    $last_index = count($namespaces) - 1;
    $class_name = $namespaces[$last_index] ?? '';
    array_pop($namespaces);
    $namespace = implode('\\', $namespaces);

    $code = <<<PHP
        class {$array_name} extends \PHPTypes\ArrayObject
        {
            protected array \$object = [
                {$class_name}::class => {$class_name}::class,
            ];

            public function __construct({$class_name} ...\$values)
            {
                parent::__construct(\$values);
            }
        }
    PHP;

    if ($namespace != '') {
        $code = <<<PHP
            namespace {$namespace};

            $code
        PHP;
    }

    eval($code);
}

function define_multiple_type(string $name, string $multiple_name): void
{
    $types = explode(' ', $name);
    $params_values = [];
    $args_values = [];
    $i = 1;

    foreach ($types as $type) {
        $params_values[] = "{$type} \$value_{$i}";
        $args_values[] = "\$value_{$i}";

        $i++;
    }

    $params = implode(', ', $params_values);
    $args = implode(', ', $args_values);

    $code = <<<PHP
        class {$multiple_name} extends \PHPTypes\Returnable\MultipleType
        {
            public function __construct({$params})
            {
                parent::__construct({$args});
            }
        }
    PHP;

    eval($code);
}
