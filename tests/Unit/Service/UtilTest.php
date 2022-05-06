<?php
/**
 * Copyright (c) Ratepay GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RatePAY\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use RatePAY\Service\Util;

class UtilTest extends TestCase
{
    /** @dataProvider provideValuesToNegate */
    public function testChangeValueToNegative($input, $expectedValue)
    {
        $value = Util::changeValueToNegative($input);

        $this->assertEquals($expectedValue, $value);
    }

    public function provideValuesToNegate()
    {
        return [
            [10, -10],
            [-10, -10],
            [-9.75, -9.75],
            [9.75, -9.75],
            [0, 0],
            [null, 0],
            [true, -1],
            [false, 0],
            ["", 0],
        ];
    }

    /** @dataProvider provideCamelCasedAndUnderscoredStrings */
    public function testChangeCamelCaseToUnderscore($word, $expectedText)
    {
        $text = Util::changeCamelCaseToUnderscore($word);

        $this->assertEquals($expectedText, $text);
    }

    public function provideCamelCasedAndUnderscoredStrings()
    {
        return [
            ['FooBar', 'FOO_BAR'],
            ['Foobar', 'FOOBAR'],
            ['Foo_bar', 'FOO_BAR'],
            ['Foo-bar', 'FOO-BAR'],
            ['FoobarBaz', 'FOOBAR_BAZ'],
            ['foobarbaz', 'FOOBARBAZ'],
        ];
    }

    /** @dataProvider provideCamelCasedAndHyphenedStrings */
    public function testChangeCamelCaseToDash($word, $expectedText)
    {
        $text = Util::changeCamelCaseToDash($word);

        $this->assertEquals($expectedText, $text);
    }

    public function provideCamelCasedAndHyphenedStrings()
    {
        return [
            ['FooBar', 'foo-bar'],
            ['Foobar', 'foobar'],
            ['Foo_bar', 'foo_bar'],
            ['Foo-bar', 'foo-bar'],
            ['FoobarBaz', 'foobar-baz'],
            ['foobarbaz', 'foobarbaz'],
        ];
    }

    /** @dataProvider provideTemplatesForReplacement */
    public function testTemplateReplace($template, $dataset, $key, $value, $expectedText)
    {
        $text = Util::templateReplace($template, $dataset, $key, $value);

        $this->assertEquals($expectedText, $text);
    }

    public function provideTemplatesForReplacement()
    {
        return [
            ['Hello {{ world }}...', [], 'world', 'foo', 'Hello foo...'],
            ['Hello {{ world }}...', ['world' => 'foo'], null, null, 'Hello foo...'],
        ];
    }

    public function testTemplateLoop()
    {
        $template = 'Fibonacci: @loop {{ zahl }}, @endloop ...and many more!';
        $dataset = ['zahl' => [1, 1, 2, 3, 5, 8, 13, 21]];
        $expectedText = 'Fibonacci:  1,  1,  2,  3,  5,  8,  13,  21,  ...and many more!';

        $text = Util::templateLoop($template, $dataset);

        $this->assertEquals($expectedText, $text);
    }

    /** @dataProvider provideAmounts */
    public function testChangeAmountToFloat($input, $expectedAmount)
    {
        $amount = Util::changeAmountToFloat($input);

        $this->assertEquals($expectedAmount, $amount);
    }

    public function provideAmounts()
    {
        return [
            [5.75, 5.75],
            ["5.75", 5.75],
            ["5,234.75", 5234.75],
        ];
    }

    /** @dataProvider provideObjectsAndMethods */
    public function testExistsAndNotEmpty($object, $method, $expectedResult)
    {
        $result = Util::existsAndNotEmpty($object, $method);

        $this->assertEquals($expectedResult, $result);
    }

    public function provideObjectsAndMethods()
    {
        return [
            [new class {
                public function foo()
                {
                    return 'bar';
                }
            }, 'foo', true],
            [new class {
                public function baz()
                {
                    return 'bar';
                }
            }, 'foo', false],
            [new class {
                public function foo()
                {
                    return '';
                }
            }, 'foo', false],
            [new class {
                public function foo()
                {
                    return null;
                }
            }, 'foo', false],
            [null, 'foo', false],
            [new class {
                public function foo()
                {
                    return 'bar';
                }
            }, null, false],
        ];
    }
}
