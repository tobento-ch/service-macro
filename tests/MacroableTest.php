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

namespace Tobento\Service\Macro\Test;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Macro\Test\Mock\MacroableClass;

/**
 * MacroableTest tests
 */
class MacroableTest extends TestCase
{    
    public function testRegisterAndCallMacro()
    {
        $mc = new MacroableClass();
        
        $mc::macro('ucwords', fn($v) => ucwords($v));
        
        $this->assertSame('Foo', $mc->ucwords('foo'));
    }

    public function testGetMacroNamesMethod()
    {
        $mc = new MacroableClass();
        
        $mc::macro('ucwords', fn($v) => ucwords($v));
        $mc::macro('lowercase', fn($v) => strtolower($v));
        
        $this->assertSame(['ucwords', 'lowercase'], $mc::getMacroNames());
    }    
}