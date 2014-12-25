<?php
namespace ULogin;
use Phalcon\Session\Exception;
use Phalcon\Di;

/**
 * ULogin init class
 *
 * @package   ULogin
 * @since     PHP >=5.4.28
 * @version   1.0
 * @author    Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright Stanislav WEB
 */
class Auth extends Init
{

    /**
     * @const KEY ulogin uid
     */
    const KEY   = 'ulogin';

    /**
     * @var \Phalcon\DiInterface $session
     */
    protected $session;

    /**
     * Constructor. Allows you to specify the initial settings for the widget.
     * Parameters can be passed as an associative array.
     * Also, the parameters can be set using the appropriate methods
     *
     * @param array $params
     * @throws \Phalcon\DI\Exception
     * @throws \Phalcon\Session\Exception
     *
     * @return void
     */
    public function __construct(array $params = [])
    {
        if(DI::getDefault() === null) {
            throw new \Phalcon\DI\Exception('DI is not configured!');
        }

        if($this->hasSession() === false) {
            throw new Exception('Session does not configured in DI');
        }

        if($this->session->has(self::KEY) === true) {

            $this->user = $this->session->get(self::KEY);
        }
        else {
            $this->user = false;
        }

        parent::__construct($params);
    }

    /**
     * Check if \Phalcon\Di has session service
     *
     * @return bool
     */
    private function hasSession() {

        if(DI::getDefault()->has('session') === true) {

            // get instance of session class
            $this->session = DI::getDefault()->getSession();

            if($this->session->getId() === '') {
                $this->session->start();
            }

            return true;
        }

        return false;
    }

    /**
     * Returns an associative array with the data about the user.
     * Fields array described in the method Init::setFields
     *
     * @example <code>
     *          $this->getUser();
     *          </code>
     *
     * @return boolean|array  data provided by the ISP authentication
     */
    public function getUser() {

        if($this->user === false) {

            $this->session->remove(self::KEY);
            $this->session->set(self::KEY, parent::getUser());
        }

        return $this->user;
    }

    /**
     * User logout
     *
     * @return boolean
     */
    public function logout() {
        parent::logout();
        $this->session->remove(self::KEY);

        return true;
    }
}

$auth = new Auth();
$auth->getUser();
