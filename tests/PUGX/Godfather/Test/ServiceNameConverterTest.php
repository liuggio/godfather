<?php

namespace PUGX\Godfather\Test;

use PUGX\Godfather\ServiceNameConverter;

Class ServiceNameConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testServiceNameConverter()
    {
        $converter = new ServiceNameConverter();
        $this->assertEquals('pugx.user_name', $converter->serviceNameConverter('PUGX\UserName'));
    }

    public function testServiceNameConverterCamelCase()
    {
        $converter = new ServiceNameConverter();
        $this->assertEquals('user_name', $converter->serviceNameConverter('UserName'));
    }

    public function testGetServiceNamespace()
    {
        $converter = new ServiceNameConverter();
        $input = array('abc','def');
        $prefix = 'prefix';
        $this->assertEquals('prefix.abc.def', $converter->getServiceNamespace($prefix, $input));
    }

    public function testGetServiceNamespaceWithConvertedServices()
    {
        $converter = new ServiceNameConverter();
        $input = array('abCd','dEf');
        $prefix = 'prefix';
        $this->assertEquals('prefix.ab_cd.d_ef', $converter->getServiceNamespace($prefix, $input));
    }
}
