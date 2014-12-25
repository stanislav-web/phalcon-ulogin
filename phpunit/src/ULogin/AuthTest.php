<?php
namespace Test\ULogin;

use Phalcon\DI;
use Phalcon\DI\FactoryDefault;
use ULogin\Auth;

/**
 * Class AuthTest
 *
 * @package Test\ULogin
 * @since   PHP >=5.4.28
 * @version 1.0
 * @author  Stanislav WEB | Lugansk <stanisov@gmail.com>
 *
 */
class AuthTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Auth class object
     *
     * @var Auth
     */
    private $auth;

    /**
     * ReflectionClass
     *
     * @var \ReflectionClass
     */
    private $reflection;

    /**
     * Dependency Injection container
     *
     * @var \Phalcon\DI
     */
    private $di;

    /**
     * Initialize testing object
     *
     * @uses Auth
     * @uses \ReflectionClass
     */
    public function setUp()
    {
        $this->di = new FactoryDefault();
        $this->di->reset();

        // Setup DI
        $this->di   =  new DI();

        $this->di->setShared('session', function() {
            $session = new \Phalcon\Session\Adapter\Files();
            $session->start();
            return $session;
        });

        DI::setDefault($this->di);

        $this->reflection = new \ReflectionClass('ULogin\Auth');
        $this->auth       = new Auth();
    }

    /**
     * Kill testing object
     *
     * @uses Auth
     */
    public function tearDown()
    {
        $this->auth = null;
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

    }
}