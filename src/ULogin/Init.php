<?php
namespace ULogin;

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
     * Available auth providers. Default has false attribute
     * to disable view on drop down list
     *
     * @var array
     */
    private $providers  =   [
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
    private $fields  =   [
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
    private $types  =   [
        'small',
        'panel',
        'window'
    ];

    /**
     * Widget. 'small' as default
     * @var string
     */
    private $widget  =   'small';

    /**
     * Use callback url?
     * @var string
     */
    private $callback = '';

    /**
     * Token key
     * @var boolean
     */
    private $token =  false;

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
     * @param string $callback page that will be implemented to redirect after login
     * @return $this
     */
    public function setCallback($callback) {

        $this->callback = $callback;

        return $this;
    }
}
