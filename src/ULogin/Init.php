<?php
namespace ULogin;

use \Phalcon\Http\Request;
use \Phalcon\Mvc\Router;
use \Phalcon\Mvc\View;

/**
 * ULogin init class
 *
 * @package   ULogin
 * @since     PHP >=5.5.12
 * @version   1.0
 * @author    Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright Stanislav WEB
 */
class Init {

    /**
     * Got user data
     * @var boolean|array
     */
    protected $user = false;

    /**
     * Token key
     * @var boolean
     */
    protected $token =  false;

    /**
     * Available auth providers. Default has false attribute
     * to disable view on drop down list
     *
     * @var array
     */
    protected $providers  =   [
        'vkontakte'     =>  true,
        'odnoklassniki' =>  true,
        'mailru'        =>  false,
        'facebook'      =>  true,
        'twitter'       =>  false,
        'google'        =>  true,
        'yandex'        =>  true,
        'livejournal'   =>  false,
        'openid'        =>  false
    ];

    /**
     * Available providers fields. Default has false attribute
     * to disable required from auth services
     *
     * @var array
     */
    protected $fields  =   [
        'first_name'    =>  true,
        'last_name'     =>  true,
        'photo'         =>  true,
        'email'         =>  false,
        'nickname'      =>  false,
        'bdate'         =>  false,
        'sex'           =>  false,
        'photo_big'     =>  false,
        'city'          =>  false,
        'country'       =>  false
    ];

    /**
     * Widget types
     * @var array
     */
    protected $types  =   [
        'small',
        'panel',
        'window'
    ];

    /**
     * Widget. 'small' as default
     * @var string
     */
    protected $widget  =   'small';

    /**
     * Use callback?
     * @var boolean|callback
     */
    protected $callback = false;

    /**
     * Redirect url
     * @var boolean|string
     */
    protected $url = false;

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
        if(empty($params) === false) {

            foreach($params as $key => $values) {

                if(method_exists($this, 'set'.ucfirst($key)) === true) {
                    $this->{'set'.ucfirst($key)}($params[$key]);
                }
            }

        }
    }

    /**
     * Allows you to add authentication providers in the list of available.
     *
     * @param mixed $providers as ('provider' => true, 'provider' => false) or string separated by comma
     * @example <code>
     *          $this->setProviders([
     *              'vkontakte'     =>  true,
     *              'odnoklassniki' =>  true,
     *              'mailru'        =>  false,  // in drop down
     *              'facebook'      =>  true,
     *              'twitter'       =>  false,  // in drop down
     *              'google'        =>  true,
     *              'yandex'        =>  true,
     *              'livejournal'   =>  false,  // in drop down
     *              'openid'        =>  false   // in drop down
     *          ]);
     *
     *          $this->setProviders('vkontakte=true,odnoklassniki=true,mailru=false');
     *          </code>
     * @return Init
     */
    public function setProviders($providers) {

        if(is_array($providers) === true) {
            $this->providers    =   $providers;
        }
        else {
            $providers = explode(',', trim($providers));

            foreach($providers as $provider) {

                if(strpos($provider,'=') === true) {
                    $provider = explode('=', $provider);
                    $this->providers[$provider[0]]  =   $provider[1];
                }
            }

        }

        return $this;
    }

    /**
     * Allows you to add to the list of fields requested for the provider's authorization.
     *
     * @param mixed $fields as ('field' => true, 'field' => false) or string separated by comma
     * @example <code>
     *          $this->setFields([
     *              'first_name'    =>  true,
     *              'last_name'     =>  true,
     *              'photo'         =>  false  // disabled
     *          ]);
     *
     *          $this->setFields('first_name=true,last_name=true,photo=false');
     *          </code>
     * @return Init
     */
    public function setFields($fields) {

        if(is_array($fields) === true) {
            $this->providers    =   $fields;
        }
        else {
            $fields = explode(',', trim($fields));

            foreach($fields as $field) {

                if(strpos($field,'=') === true) {
                    $field = explode('=', $field);
                    $this->fields[$field[0]]  =   $field[1];
                }
            }

        }

        return $this;

    }

    /**
     * Lets you specify the widget type. Must match the variable `types`
     *
     * @param $type
     * @example <code>
     *          $this->setType('small');
     *          </code>
     * @return Init
     */
    public function setType($type) {

        $this->types = array_flip($this->types);
        if(isset($this->types[$type]) === true) {

            $this->widget = $type;

        }

        return $this;
    }

    /**
     * Lets you specify the callback url to redirect to when authorizing the page is reloaded.
     * If the url is not specified and is used to redirect the authorization,
     * the authorization after the current page just updated
     *
     * @param string $url page that will be implemented to redirect after login
     * @return $this
     */
    public function setUrl($url) {

        $this->url = $url;

        return $this;
    }

    /**
     * Allows authentication without reloading the page.
     * The parameters of this function can be defined in two ways:
     *
     * 1. The first parameter - the name of the js-function, which is passed as an argument token authentication.
     * The second option - the page of your site,
     * That displays the code returned by getWindow().
     *
     * 2. Single parameter - an array of two elements.
     * The first element - the name of the js-function, the second - url for getWindow().
     *
     * Js-function should be organized in such a way that the token passed through
     * POST or GET methods of the page on which is called.
     * Method getUser() or isAuthorised().
     *
     * In the case of authorization without a referral is not necessary
     * to specify the url to redirect through setUrl() method or constructor.
     *
     * @return null
     */
    public function setCallback() {

        // get function arguments

        $args   =   func_num_args();

        if($args === 1
            && is_array(func_get_arg(0)) === true
            && count(func_get_arg(0)) > 1) {

                $arg = func_get_arg(0);
                $callback = $arg[0];
                $url = $arg[1];

        }
        else if($args === 2) {

            $callback = func_get_arg(0);
            $url = func_get_arg(1);
        }

        $this->callback = $callback;
        $this->url = $url;

        return null;
    }

    /**
     * Destroy user data
     *
     * @return bool
     */
    private function destroyUserData() {

        if(is_array($this->user) === true
            && isset($this->user["error"]) === true) {
            $this->user = false;
            return true;
        }
        return false;
    }

    /**
     * Reads the parameters passed to the script, and selects the authorization key ULogin
     *
     * @return bool|mixed
     */
    protected function getToken() {

        $request = new Request();

        if($request->isPost() && $request->hasPost('token')) {
            $this->token = $request->getPost('token', null, false);
        }
        else if($request->isGet() && $request->has('token')) {
            $this->token = $request->getQuery('token', null, false);
        }

        return $this->token;
    }

    /**
     * Returns an associative array with the data about the user.
     * Fields array described in the method setFields
     *
     * @example <code>
     *          $this->getUser();
     *          </code>
     *
     * @return array|bool|mixed data provided by the ISP login
     */
    public function getUser() {

        // destroy previous content
        $this->destroyUserData();

        if($this->user === false) {

            // get user

            $url = 'http://ulogin.ru/token.php?token=' . $this->getToken() . '&host=' . (new Request())->getHttpHost();
            $content = file_get_contents($url);
            $this->user =   json_decode($content, true);

            // if use has error , destroy user data
            if($this->destroyUserData() === true) {
                $this->logout();
            }
        }

        return $this->user;
    }

    /**
     * Checks whether logon
     *
     * @return array|bool|mixed
     */
    protected function isAuthorised() {

        if(is_array($this->user) === true
            && isset($this->user['error']) === false) {

            return true;
        }

        return $this->getUser();
    }

    /**
     * Allows the user to exit from the system
     *
     * @return null
     */
    protected function logout() {

        $this->token    =   false;
        $this->user     =   false;

        return null;

    }

    /**
     * Returns the html-code widget
     *
     * @return View
     */
    public function getForm() {

        if($this->url === false) {

            $this->url = (new Router())->getRewriteUri();
        }

        $view = new View();

        return $view->getRender('views', 'ulogin', [
            'widget'    => $this->widget,
            'fields'    => $this->fields,
            'providers' => $this->providers,
            'url'       => $this->url,
            'callback'  =>  callback
        ]);

    }

    /**
     * Returns the code necessary to authenticate without reloading the page
     * @return View
     */
    public function getWindow() {

        return (new View())->getRender('views', 'window');
    }
}
