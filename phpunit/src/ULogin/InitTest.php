<?php
namespace Test\ULogin;
use ULogin\Auth;
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
     * User status
     *
     */
    private $use;

    /**
     * Init class object
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
        $providers = $this->init->setProviders('vkontakte=true,odnoklassniki=true,facebook=false,google=false,yandex=true');

        $this->assertInstanceOf('ULogin\Init', $providers,
            "[-] setProviders() return must be instance of ULogin\\Init"
        );

        // call method
        $fieldsString = $this->init->setFields('first_name,last_name,photo,city');

        $this->assertInstanceOf('ULogin\Init', $fieldsString,
            "[-] setFields() return must be instance of ULogin\\Init"
        );

        $fieldsArray = $this->init->setFields(array(
            'first_name',
            'last_name',
            'photo',
            'city'
        ));

        $this->assertInstanceOf('ULogin\Init', $fieldsArray,
            "[-] setFields() return must be instance of ULogin\\Init"
        );

        // call method

        $optionalArray = $this->init->setOptional(array(
            'first_name',
            'last_name',
            'photo',
            'city'
        ));

        $this->assertInstanceOf('ULogin\Init', $optionalArray,
            "[-] setOptional() return must be instance of ULogin\\Init"
        );

        $optionalString = $this->init->setOptional('first_name,last_name,photo,city');

        $this->assertInstanceOf('ULogin\Init', $optionalString,
            "[-] setOptional() return must be instance of ULogin\\Init"
        );

        // call method
        $typeString = $this->init->setType('panel');

        $this->assertInstanceOf('ULogin\Init', $typeString,
            "[-] setType() return must be instance of ULogin\\Init"
        );

        $typeArray = $this->init->setType(array('panel'));

        $this->assertInstanceOf('ULogin\Init', $typeArray,
            "[-] setType() return must be instance of ULogin\\Init"
        );

        // call method
        $urlArray = $this->init->setUrl(array('?success'));

        $this->assertInstanceOf('ULogin\Init', $urlArray,
            "[-] setUrl() return must be instance of ULogin\\Init"
        );

        $urlArray = $this->init->setUrl('');

        $this->assertInstanceOf('ULogin\Init', $urlArray,
            "[-] setUrl() return must be instance of ULogin\\Init"
        );
    }

    /**
     * @covers ULogin\Init::getUser()
     */
    public function testGetUser() {

        // call method with  non auth user data
        $user = $this->init->getUser();

        $this->assertFalse($user,
            "[-] the first instance of user must equal false"
        );

    }

    /**
     * @covers ULogin\Init::destroyUserData()
     */
    public function testDestroyUser() {

        // call method
        $destroy = $this->invokeMethod($this->init, 'destroyUserData', array());

        $this->assertInternalType('boolean', $destroy,
            "[-] destroyUserData() method must return boolean"
        );

        // get default property value
        $reflectionProperty = $this->reflection->getProperty('user');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->init, ['error' => 'Brain Fuck']);

        $destroy = $this->invokeMethod($this->init, 'destroyUserData', array());

        $this->assertTrue($destroy,
            "[-] destroyUserData() return true when destroyed user session or getting error"
        );
    }

    /**
     * @covers ULogin\Init::getToken()
     */
    public function testToken() {

        // check return token initial
        $token = $this->init->getToken();

        $this->assertFalse($token,
            "[-] token property must be init as `false` (bool)"
        );

        // test POST
        $_SERVER['REQUEST_METHOD']  =   'POST';
        $token = $this->init->getToken();

        $this->assertFalse($token,
            "[-] token property must be init as `false` (bool)"
        );
    }

    /**
     * @covers ULogin\Init::isAuthorised()
     */
    public function testAuthorised() {

        // first call
        $auth =  $this->init->isAuthorised();

        $this->assertFalse($auth,
            "[-] Auth user status must be init as `false` on first state"
        );

        $reflectionProperty = $this->reflection->getProperty('user');
        $reflectionProperty->setAccessible(true);
        $this->user = $reflectionProperty->setValue($this->init, [
            'first_name'    => 'Brain',
            'last_name'     => 'Fuck',
            'sex'           => '2',
        ]);
        // check auth
        $auth =  $this->init->isAuthorised();

        $this->assertTrue($auth,
            "[-] Success user auth must return true"
        );

    }

    /**
     * @covers ULogin\Init::getForm()
     */
    public function testForm() {

        // call method
        $form =  (new Auth())->getForm();

        $this->assertInternalType('string', $form,
            "[-] (new Auth())->getForm() must return string"
        );;

    }

    /**
     * @covers ULogin\Init::logout()
     */
    public function testLogout() {

        // check return
        $logout = $this->invokeMethod($this->init, 'logout', array());

        $this->assertNull($logout,
            "[-] logout must return null always"
        );


    }
}