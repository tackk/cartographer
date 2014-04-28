<?php

namespace Tackk\Cartographer;

use InvalidArgumentException;

/**
 * Gets the property of the given array or object.
 * @param  mixed  $entry
 * @param  string $prop
 * @param  null   $default
 * @return mixed
 * @throws InvalidArgumentException
 */
function get_property($entry, $prop, $default = null)
{
    checktype($entry, ['array', 'object', 'ArrayAccess']);

    if (is_array($entry) || $entry instanceof \ArrayAccess) {
        return isset($entry[$prop]) ? $entry[$prop] : $default;
    }

    return isset($entry->$prop) ? $entry->$prop : $default;
}

/**
 * Checks the type of the given value against an array of valid types.
 *
 * If the value is a valid type, `true` is returned, if not, an
 * InvalidArgumentException is thrown.
 *
 *     // Throws: Invalid type: string, Expected type(s): array, ArrayAccess
 *     checktype('foo', ['array', 'ArrayAccess']);
 *
 * @param  mixed $value
 * @param  array $validTypes
 * @return bool
 * @throws \InvalidArgumentException
 */
function checktype($value, array $validTypes)
{
    $nativeTypes = [
        'array'    => 'is_array',
        'bool'     => 'is_bool',
        'callable' => 'is_callable',
        'double'   => 'is_double',
        'float'    => 'is_float',
        'int'      => 'is_int',
        'integer'  => 'is_integer',
        'long'     => 'is_long',
        'null'     => 'is_null',
        'numeric'  => 'is_numeric',
        'object'   => 'is_object',
        'real'     => 'is_real',
        'resource' => 'is_resource',
        'scalar'   => 'is_scalar',
        'string'   => 'is_string',
    ];

    $valid = false;
    foreach ($validTypes as $type) {
        if (isset($nativeTypes[$type])) {
            $valid = call_user_func($nativeTypes[$type], $value);
        } else {
            $valid = $value instanceof $type;
        }

        if ($valid) {
            break;
        }
    }

    if (!$valid) {
        $type = gettype($value);
        if (gettype($value) === 'object') {
            $type = get_class($value);
        }
        throw new InvalidArgumentException('Invalid type: '.$type.', Expected type(s): '.implode(', ', $validTypes));
    }

    return true;
}
