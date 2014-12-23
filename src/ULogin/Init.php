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
     * Available auth providers
     * @var array
     */
    private $providers  =   [
        'vkontakte',
        'odnoklassniki',
        'mailru',
        'facebook',
        'twitter',
        'google',
        'yandex',
        'livejournal',
        'openid'
    ];

    /**
     * Available providers fields
     * @var array
     */
    private $fields  =   [
        'first_name',
        'last_name',
        'photo',
        'email',
        'nickname',
        'bdate',
        'sex',
        'photo_big',
        'city',
        'country'
    ];

    /**
     * Available providers fields
     * @var array
     */
    private $wiget  =   [
        'small',
        'panel',
        'window'
    ];

    /**
     * Use callback function?
     * @var boolean
     */
    private $callback =  false;

    /**
     * Token key
     * @var boolean
     */
    private $token =  false;


    /**
     *Constructor. Allows you to specify the initial settings for the widget.
     * Parameters can be passed as an associative array.
     * Also, the parameters can be set using the appropriate methods
     *
     * @param array $params
     * @return void
     */
    public function __construct(array $params = [])
    {
        foreach($params as $key => $values) {

            if(method_exists($this, 'set'.ucfirst($key)) === true) {
                $this->{'set'.ucfirst($key)}($params[$key]);
            }
        }
    }
}
