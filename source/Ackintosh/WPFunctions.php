<?php
namespace Ackintosh;

/**
 * Just calls WordPress global functions.
 * For easy to testing.
 */
class WPFunctions
{
    public function __call($method, $args)
    {
        call_user_func_array($method, $args);
    }
}
