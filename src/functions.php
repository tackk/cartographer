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
    if (is_array($entry) || $entry instanceof \ArrayAccess) {
        return isset($entry[$prop]) ? $entry[$prop] : $default;
    } elseif (is_object($entry)) {
        return isset($entry->$prop) ? $entry->$prop : $default;
    } else {
        throw new InvalidArgumentException('Invalid Entry Type: Entry must be an array, or an object, '.gettype($entry).' given.');
    }
}
