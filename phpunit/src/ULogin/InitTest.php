<?php
namespace Test\ULogin;
use ULogin\Init;

/**
 * Class InitTest
 *
 * @package Test\ULogin
 * @since   PHP >=5.4.28
 * @version 1.0
 * @author  Stanislav WEB | Lugansk <stanisov@gmail.com>
 *
 */
class InitTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Auth class object
     *
     * @var Init
     */
    private $init;

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
        $this->reflection = new \ReflectionClass('ULogin\Init');
        $this->init       = new Init();
    }

    /**
     * Kill testing object
     *
     * @uses Init
     */
    public function tearDown()
    {
        $this->init = null;
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

    /**
     * @covers ULogin\Init::__construct()
     */
    public function testConstructor() {

        // check init params
        $params = array(
            'fields'        =>  'first_name,last_name,photo,city',
            'providers'     =>  'vk=true,mailru=false,linkedin=false',
            'url'           =>  '/auth/?success',
            'type'          =>  'window'
        );

        $this->assertInstanceOf('ULogin\Init', new Init($params),
            "[-] The return must be instance of ULogin\\Init"
        );
    }

    /**
     * @covers ULogin\Init::setProviders()
     * @covers ULogin\Init::setFields()
     * @covers ULogin\Init::setOptional()
     * @covers ULogin\Init::setType()
     * @covers ULogin\Init::setUrl()
     */
    public function testSetters() {

        // call method
        $providers = $this->invokeMethod($this->init, 'setProviders', array('vkontakte=true,odnoklassniki=true,facebook=false,google=false,yandex=true'));

        $this->assertInstanceOf('ULogin\Init', $providers,
            "[-] The return must be instance of ULogin\\Init"
        );

        // call method
        $fields = $this->invokeMethod($this->init, 'setFields', array('first_name,last_name,photo,city'));

        $this->assertInstanceOf('ULogin\Init', $fields,
            "[-] The return must be instance of ULogin\\Init"
        );

        // call method
        $optional = $this->invokeMethod($this->init, 'setOptional', array('first_name,last_name,photo,city'));

        $this->assertInstanceOf('ULogin\Init', $optional,
            "[-] The return must be instance of ULogin\\Init"
        );

        // call method
        $type = $this->invokeMethod($this->init, 'setType', array('panel'));

        $this->assertInstanceOf('ULogin\Init', $type,
            "[-] The return must be instance of ULogin\\Init"
        );

        // call method
        $url = $this->invokeMethod($this->init, 'setUrl', array('?success'));

        $this->assertInstanceOf('ULogin\Init', $url,
            "[-] The return must be instance of ULogin\\Init"
        );
    }
}