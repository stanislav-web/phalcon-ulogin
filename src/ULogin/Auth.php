<?php
namespace ULogin;
use Phalcon\Session\Exception;

/**
 * ULogin init class
 *
 * @package   ULogin
 * @since     PHP >=5.5.12
 * @version   1.0
 * @author    Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright Stanislav WEB
 */
class Auth extends Init implements \Phalcon\DI\InjectionAwareInterface {

    /**
     * @const KEY ulogin uid
     */
    const KEY   =   'ulogin';

    /**
     * @var \Phalcon\DiInterface $di
     */
    protected $di;

    /**
     * @var callable $session
     */
    protected $session;

    /**
     * @param \Phalcon\DiInterface $di
     * @return null
     */
    public function setDi($di) {
        $this->_di = $di;

        return null;
    }

    /**
     * @return \Phalcon\DiInterface
     */
    public function getDi() {
        return $this->_di;
    }

    /**
     * Constructor. Allows you to specify the initial settings for the widget.
     * Parameters can be passed as an associative array.
     * Also, the parameters can be set using the appropriate methods
     *
     * @param array $params
     * @return void
     */
    public function __construct(array $params = [])
    {
        parent::__construct($params);

        if($this->di->has('session') === true) {

            // get instance of session class
            $this->session = $this->di->get('session');

            if($this->session->getId() === '') {
                $this->session->start();
            }

            if($this->session->has(self::KEY) === true) {

                $this->user = $this->session->get(self::KEY);
            }
            else {
                $this->user = false;
            }
        }
        else {

            throw new Exception('Session does not configured in DI');
        }
    }

    /** Возвращает ассоциативный массив с данными о пользователе. Поля массива описаны в методе set_fields
     * \result данные о пользователе от провайдера авторизации
     *
     * Пример: $userdata=$this->uauth->userdata();
     *
     * $userdata содержит данные, предоставленные провайдером авторизации.
     */
    public function getUser() {

        if($this->user === false) {

            $this->session->set(self::KEY, parent::getUser());
        }

        return $this->user;
    }


    /** Завершает сессию и очищает сохраненные переменные
     */
    public function logout() {
        parent::logout();
        $this->session->remove(self::KEY);
    }

}