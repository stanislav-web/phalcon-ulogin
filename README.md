# Phalcon ULogin
[![Build Status](https://travis-ci.org/stanislav-web/phalcon-ulogin.svg)](https://travis-ci.org/stanislav-web/phalcon-ulogin) [![Code Coverage](https://scrutinizer-ci.com/g/stanislav-web/phalcon-ulogin/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/stanislav-web/phalcon-ulogin/?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/stanislav-web/phalcon-ulogin/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/stanislav-web/phalcon-ulogin/?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/29a866f6-3609-4b1e-b824-242ae7a05d0a/mini.png)](https://insight.sensiolabs.com/projects/29a866f6-3609-4b1e-b824-242ae7a05d0a) [![Total Downloads](https://poser.pugx.org/stanislav-web/phalcon-ulogin/downloads.svg)](https://packagist.org/packages/stanislav-web/phalcon-ulogin) [![License](https://poser.pugx.org/stanislav-web/phalcon-ulogin/license.svg)](https://packagist.org/packages/stanislav-web/phalcon-ulogin) [![Latest Stable Version](https://poser.pugx.org/stanislav-web/phalcon-ulogin/v/stable.svg)](https://packagist.org/packages/stanislav-web/phalcon-ulogin)

Phalcon ULogin. The authorization form uLogin through social networks

[![ULogin](https://ulogin.ru/img/feat1.png)](https://ulogin.ru)

## Compatible
- PSR-0, PSR-1, PSR-2, PSR-4 Standards

## System requirements
- PHP 5.4.x >
- Phalcon extension 1.3.x
- \Phalcon\Session in DI

## Install
First update your dependencies through composer. Add to your composer.json:
```python
"require": {
    "stanislav-web/phalcon-ulogin": "1.0-stable"
}
```
```python
php composer.phar install
```
OR
```python
php composer.phar require stanislav-web/phalcon-ulogin dev-master
```
_(Do not forget to include the composer autoloader)_

Or manual require in your loader service
```php
    $loader->registerNamespaces([
        'ULogin\Auth' => 'path to src'
    ]);
```
You can create an injectable service
```php
    $this->di['ulogin'] = function() {
        return new ULogin\Auth();
    };
```
## Usage

#### simple use (get socials as default)
```php
    use ULogin\Auth;
    
    echo (new Auth())->getForm();
```
#### setup social widget
```php
    echo (new Auth())->setType('window')->getForm(); // window, panel, small as default
```
#### setup providers for widget form
```php
echo (new Auth())->setProviders([
                   'vkontakte'     =>  true,   // show inline
                   'odnoklassniki' =>  true,   // show inline
                   'facebook'      =>  false,  // show in drop down
                   'google'        =>  false,  // show in drop down
                   'yandex'        =>  true,   // show inline
    ])->setType('panel')->getForm();
```
#### or setup providers as string
```php
    echo (new Auth())->setProviders('vkontakte=true,odnoklassniki=true,facebook=false,google=false,yandex=true')->setType('panel')->getForm();
```
#### setup redirect url (current path using as default)
```php
    echo (new Auth())->setType('panel')->setUrl('?success')->getForm();
```
#### setup user fields getting from auth (optionals fields setup similary. Use setOptional())
```php
    echo (new Auth())->setFields([
                   'first_name',
                   'last_name',
                   'photo',
                   'city'
              ])->getForm();
```
#### or setup fields as string
```php
    echo (new Auth())->setFields('first_name,last_name,photo,city')->getForm();
```
#### alternate configuration
```php
    $ulogin = new Auth(array(
            'fields'        =>  'first_name,last_name,photo,city',
            'providers'     =>  'vk=true,mailru=false,linkedin=false',
            'url'           =>  '/auth/?success',
            'type'          =>  'window'
        ));
    echo $ulogin->getForm();
```
#### get auth data
```php
    $ulogin = new Auth();

    // print form
    echo $ulogin->setUrl('?success')->getForm();

    // handler
    
    $request = new \Phalcon\Http\Request();
    if($request->hasQuery('success') === true) {

        // check authorization
        if($ulogin->isAuthorised()) {
            
            // get auth token 
            echo $ulogin->getToken();
            
            // get  auth user data
            var_dump($ulogin->getUser());

            // logout
            $ulogin->logout();
        }
    }
```

## Unit Test
Also available in /phpunit directory. Run command to start
```php
php build/phpunit.phar --configuration phpunit.xml.dist --coverage-text
```

Read logs from phpunit/log

##[Issues](https://github.com/stanislav-web/phalcon-ulogin/issues "Issues")

## Screen
[![ULogin](http://dl2.joxi.net/drive/0004/0211/323795/141226/31db1a9566.jpg)](https://ulogin.ru)
