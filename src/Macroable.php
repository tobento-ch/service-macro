<?php

/**
 * TOBENTO
 *
 * @copyright   Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);

namespace Tobento\Service\Macro;

use Closure;
use BadMethodCallException;

/**
 * Macroable trait
 */
trait Macroable
{
    /**
     * @var array The registered macros.
     */    
    protected static array $macros = [];

    /**
     * Register a macro.
     *
     * @param string $name The macro name.
     * @param object|callable $macro
     * @return void
     */
    public static function macro(string $name, object|callable $macro): void
    {
        // let them be able to overwrite.
        static::$macros[$name] = $macro;
    }

    /**
     * Get the macro names.
     *
     * @return array
     */
    public static function getMacroNames(): array
    {
        return array_keys(static::$macros);
    }
    
    /**
     * If a macro exists.
     *
     * @param string $name The macro name.
     * @return bool True on success, false on failure.
     */
    public static function hasMacro(string $name): bool
    {
        return isset(static::$macros[$name]);
    }

    /**
     * Call the macro.
     *
     * @param string The method name.
     * @param array The methods parameters.
     *
     * @throws BadMethodCallException
     *
     * @return mixed
     */
    public function __call(string $methodName, array $parameters): mixed
    {
        // check if macro exists.
        if (! static::hasMacro($methodName))
        {
             throw new BadMethodCallException('Method "'.$methodName.'" does not exist on "'.static::class.'".');
        }
        
        $macro = static::$macros[$methodName];
        
        if ($macro instanceof Closure)
        {
            $macro = $macro->bindTo($this, static::class);
        }
        
        return $macro(...$parameters);
    }
    
    /**
     * Call the macro.
     *
     * @param string $method
     * @param array $parameters
     *
     * @throws BadMethodCallException
     *
     * @return mixed
     */
    public static function __callStatic(string $method, array $parameters): mixed
    {
        if (! static::hasMacro($method))
        {
             throw new BadMethodCallException('Method "'.$methodName.'" does not exist on "'.static::class.'".');
        }
        
        $macro = static::$macros[$method];

        if ($macro instanceof Closure)
        {
            $macro = $macro->bindTo(null, static::class);
        }

        return $macro(...$parameters);        
    }
}