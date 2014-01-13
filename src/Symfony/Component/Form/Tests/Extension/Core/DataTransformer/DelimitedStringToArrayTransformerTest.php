<?php

namespace Symfony\Component\Form\Tests\Extension\Core\DataTransformer;

use Symfony\Component\Form\Extension\Core\DataTransformer\DelimitedStringToArrayTransformer;

class DelimitedStringToArrayTransformerTest extends \PHPUnit_Framework_TestCase
{
    private $transformer;

    public function provideTransform()
    {
        return array(
            array(
                'foo,bar,foo', 
                array('foo', 'bar', 'foo')
            ),
            array(
                'foo', 
                array('foo'),
                ',',
            ),
            array(
                '', 
                array()
            ),
            array(
                'bar  , foo,       bat', 
                array('bar', 'foo', 'bat')
            ),
            array(
                'bar,foo,bat', 
                array('bar,foo,bat'),
                ':',
            ),

            array(
                array('thisisanarray'),
                null,
                null,
                'Expected a string',
            ),
        );
    }

    /**
     * @dataProvider provideTransform
     */
    public function testTransform($string, $expected, $delimiter = ',', $expectedException = null)
    {
        if ($expectedException) {
            $this->setExpectedException('Symfony\Component\Form\Exception\TransformationFailedException', $expectedException);
        }

        $transformer = new DelimitedStringToArrayTransformer($delimiter);
        $res = $transformer->transform($string);
        $this->assertEquals($expected, $res);
    }

    public function provideReverseTransform()
    {
        return array(
            array(
                array('foo', 'bar', 'foo'),
                'foo ,bar ,foo',
                ',',
                '%s ',
            ),
            array(
                array('foo', 'bar', 'foo'),
                'foo,bar,foo',
                ',',
                '%s',
            ),
            array(
                array('foo', 'bar', 'foo'),
                'foo , bar , foo',
                ',',
                ' %s ',
            ),
            array(
                array('foo'),
                'foo',
                ',',
                '%s',
            ),

            array(
                'asdasd',
                null,
                null,
                null,
                'Expected an array',
            ),
        );
    }

    /**
     * @dataProvider provideReverseTransform
     */
    public function testReverseTransform($array, $string, $delimiter, $format, $expectedException = null)
    {
        if ($expectedException) {
            $this->setExpectedException('Symfony\Component\Form\Exception\TransformationFailedException', $expectedException);
        }

        $transformer = new DelimitedStringToArrayTransformer($delimiter, $format);
        $res = $transformer->reverseTransform($array);
        $this->assertEquals($string, $res);
    }
}
