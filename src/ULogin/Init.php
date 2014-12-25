<?php
namespace ULogin;

use \Phalcon\Http\Request;
use \Phalcon\Mvc\Router;
use \Phalcon\Mvc\View\Simple as View;

/**
 * ULogin init class
 *
 * @package   ULogin
 * @since     PHP >=5.4.28
 * @version   1.0
 * @author    Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright Stanislav WEB
 */
class Init
{

    /**
     * Got user data
     *
     * @var boolean|array
     */
    protected $user = false;

    /**
     * Token key
     *
     * @var boolean
     */
    protected $token = false;

    /**
     * Available auth providers. Default show on the panel
     *
     * @var string
     */
    private $requiredProviders = 'vkontakte,odnoklassniki,mailru,facebook';

    /**
     * Hidden auth providers. Default hide on the drop down
     *
     * @var string
     */
    private $hiddenProviders = 'twitter,livejournal,google,yandex,openid';

    /**
     * Required providers fields.
     *
     * @var string
     */
    private $requiredFields = 'first_name,last_name,photo';

    /**
     * Optional (additional) fields providers fields.
     *
     * @var string
     */
    private $optionalFields = 'email, nickname,bdate,sex,photo_big,city,country';

    /**
     * Widget types
     *
     * @var array
     */
    protected $types = [
        'small',
        'panel',
        'window'
    ];

    /**
     * Widget. 'small' as default
     *
     * @var string
     */
    private $widget = 'small';

    /**
     * Redirect url
     *
     * @var boolean|string
     */
    private $url = false;

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
        if (empty($params) === false) {

            foreach ($params as $key => $values) {

                if (method_exists($this, 'set' . ucfirst($key)) === true) {
                    $this->{'set' . ucfirst($key)}($params[$key]);
                }
            }

        }
    }

    /**
     * Allows you to add authentication providers in the list of available.
     *
     * @param mixed $providers as ('provider' => true, 'provider' => false) or string separated by comma
     * @example <code>
     *                         $this->setProviders([
     *                         'vkontakte'     =>  true,
     *                         'odnoklassniki' =>  true,
     *                         'mailru'        =>  true,
     *                         'facebook'      =>  true,
     *                         'twitter'       =>  false,  // in drop down
     *                         'google'        =>  false,  // in drop down
     *                         'yandex'        =>  false,  // in drop down
     *                         'livejournal'   =>  false,  // in drop down
     *                         'openid'        =>  false   // in drop down
     *                         ]);
     *
     *          $this->setProviders('vkontakte=true,odnoklassniki=true,mailru=true,openid=false');
     *          </code>
     * @return Init
     */
    public function setProviders($providers)
    {
        $array = Parser::map($providers);

        // collection data
        if (empty($array['required']) === false) {
            $this->requiredProviders = implode(',', $array['required']);
        }

        if (empty($array['hidden']) === false) {
            $this->hiddenProviders = implode(',', $array['hidden']);
        }

        return $this;
    }

    /**
     * Allows you to add to the list of fields requested for the provider's authorization.
     *
     * @param mixed $fields as ('field1', 'field2', ...) or string separated by comma
     * @example <code>
     *                      $this->setFields([
     *                      'first_name',
     *                      'last_name',
     *                      'photo'
     *                      ]);
     *
     *          $this->setFields('first_name,last_name,photo');
     *          </code>
     * @return Init
     */
    public function setFields($fields)
    {

        if (empty($fields) === false) {

            if (is_array($fields) === true) {
                $this->requiredFields = implode(',', $fields);
            } else {
                $this->requiredFields = $fields;

            }
        }

        return $this;
    }

    /**
     * Allows you to add to the list of optionals fields.
     *
     * @param mixed $fields as ('field1', 'field2', ...) or string separated by comma
     * @example <code>
     *                      $this->setOptional([
     *                      'bday',
     *                      'city',
     *                      'sex'
     *                      ]);
     *
     *          $this->setOptional('bday,city,sex');
     *          </code>
     * @return Init
     */
    public function setOptional($fields)
    {

        if (empty($fields) === false) {

            if (is_array($fields) === true) {
                $this->optionalFields = implode(',', $fields);
            } else {
                $this->optionalFields = $fields;

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
    public function setType($type)
    {

        $this->types = array_flip($this->types);
        if (isset($this->types[$type]) === true) {

            $this->widget = $type;

        }

        return $this;
    }

    /**
     * Lets you specify the callback url to redirect to when authorizing the page is reloaded.
     * If the url is not specified and is used to redirect the authorization,
     * the authorization after the current page just updated
     *
     * @param string $url page that will be implemented to redirect after login (accept QUERY_STRING)
     * @return $this
     */
    public function setUrl($url = '')
    {

        $request = new Request();

        if (empty($url) === true) {

            $this->url = $request->getScheme() . '://' . $request->getHttpHost() . (new Router())->getRewriteUri();
        } else {
            $this->url = $request->getScheme() . '://' . $request->getHttpHost() . $url;
        }

        return $this;
    }

    /**
     * Destroy user data
     *
     * @return bool
     */
    private function destroyUserData()
    {

        if (is_array($this->user) === true
            && isset($this->user["error"]) === true
        ) {
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
    public function getToken()
    {

        $request = new Request();

        if ($request->isPost() === true) {
            $this->token = $request->getPost('token', null, false);
        } else if ($request->isGet() === true) {
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
    public function getUser()
    {

        // destroy previous content
        $this->destroyUserData();

        if ($this->user === false) {

            // get user

            $url = 'http://ulogin.ru/token.php?token=' . $this->getToken() . '&host=' . (new Request())->getHttpHost();
            $content = file_get_contents($url);
            $this->user = json_decode($content, true);

            // if use has error , destroy user data
            if ($this->destroyUserData() === true) {
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
    public function isAuthorised()
    {

        if (is_array($this->user) === true
            && isset($this->user['error']) === false
        ) {

            return true;
        }

        return $this->getUser();
    }

    /**
     * Allows the user to exit from the system
     *
     * @return null
     */
    protected function logout()
    {

        $this->token = false;
        $this->user = false;

        return null;

    }

    /**
     * Returns the html-form widget
     *
     * @return View
     */
    public function getForm()
    {

        $view = new View();

        return $view->render(__DIR__ . '/../views/ulogin', [
            'widget' => $this->widget,
            'fields' => $this->requiredFields,
            'optional' => $this->optionalFields,
            'providers' => $this->requiredProviders,
            'hidden' => $this->hiddenProviders,
            'url' => $this->url
        ]);

    }

}
