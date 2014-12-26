<?php
namespace Test\ULogin;
use ULogin\Parser;

/**
 * Class ParserTest
 *
 * @package Test\ULogin
 * @since   PHP >=5.4.28
 * @version 1.0
 * @author  Stanislav WEB | Lugansk <stanisov@gmail.com>
 *
 */
class ParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Internal data
     *
     * @var array
     */
    private $data = [];

    /**
     * ReflectionClass
     *
     * @var \ReflectionClass
     */
    private $reflection;

    /**
     * Initialize testing object
     *
     * @uses Init
     * @uses \ReflectionClass
     */
    public function setUp()
    {
        $this->reflection = new \ReflectionClass('ULogin\Parser');

        $this->data['array'] = Parser::map(['vk' => true,'google' => true, 'linkedin' => true]);

        $this->data['int'] = Parser::map(['vk','google','linkedin']);

        $this->data['string'] = Parser::map('vk,google,linkedin');

    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     * @example <code>
     *          $this->invokeMethod($user, 'cryptPassword', array('passwordToCrypt'));
     *          </code>
     * @return mixed Method return.
     */
    protected function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $method = $this->reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Setup accessible any private (protected) property
     *
     * @param $name
     * @return \ReflectionMethod
     */
    protected function getProperty($name)
    {
        $prop = $this->reflection->getProperty($name);
        $prop->setAccessible(true);
        return $prop;
    }

    public function testMethods() {

        $methods = $this->reflection->getMethods(\ReflectionMethod::IS_STATIC);

        $this->assertCount(4, $methods,
            "[-] The number of static methods of class Parser must equal 4"
        );
    }

    /**
     * @covers ULogin\Parser::map()
     */
    public function testMapArray() {

        // check method setExact
        $this->assertTrue(
            method_exists(new Parser(), 'map'),
            '[-] Class Parser must have method map()'
        );

        $this->assertInternalType('array', $this->data['array'],
            "[-] Return type of map() must be array if was inject is array"
        );

        $this->assertArrayHasKey('required', $this->data['array'],
            "[-] map() method should return array with key [required]"
        );

        $this->assertInternalType('array', $this->data['string'],
            "[-] Return type of map() must be array if was inject is array"
        );

        $this->assertArrayHasKey('required', $this->data['string'],
            "[-] map() method should return array with key [required]"
        );
    }

    /**
     * @covers ULogin\Parser::arrayResolve()
     */
    public function testArrayResolve() {

        $result = Parser::arrayResolve(['vk' => true,'google' => true, 'linkedin' => true]);

        $this->assertInternalType('array', $result,
            "[-] Return type of arrayResolve() must be array if was inject array"
        );

        $this->assertArrayHasKey('required', $result,
            "[-] arrayResolve() method should return array with key [required]"
        );

        // some wrong data
        $result = Parser::arrayResolve(array('wrong' => 'vk'));

        $this->assertArrayHasKey('hidden', $result,
            "[-] arrayResolve() method should return array with key [hidden]"
        );
    }

    /**
     * @covers ULogin\Parser::stringResolve()
     * @covers ULogin\Parser::separate()
     */
    public function testStringResolve() {

        // test empty param
        $result = Parser::stringResolve();

        $this->assertInternalType('array', $result,
            "[-] Return type of stringResolve() must be array if was inject string"
        );

        $this->assertArrayHasKey('required', $result,
            "[-] stringResolve() method should return array with key [required]"
        );

        // some true data
        $resultTrue = Parser::stringResolve('vk=true,linkedin=true,mailru=false');

        $this->assertCount(2, $resultTrue,
            "[-] The number of elements array produced by method stringResolve() must count 2"
        );

        $this->assertArrayHasKey('hidden', $resultTrue,
            "[-] stringResolve() method should return array with key [hidden]"
        );

        $this->assertArrayHasKey('required', $resultTrue,
            "[-] stringResolve() method should return array with key [required]"
        );
    }
}