# Phalcon ULogin

Phalcon ULogin. The authorization form uLogin through social networks

[![ULogin](https://ulogin.ru/img/feat1.png)](https://ulogin.ru)

## Compatible
- PSR-0, PSR-1, PSR-2, PSR-4 Standards

## System requirements
- PHP 5.4.x >
- Phalcon extension 1.3.x

## Install
First update your dependencies through composer. Add to your composer.json:
```php
"require": {
    "stanislav-web/phalcon-ulogin": "dev-master",
}
```
Then run to update dependency and autoloader 
```python
php composer.phar update
php composer.phar install
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
                   'facebook'      =>  true,   // show inline 
                   'google'        =>  false,  // show in drop down
                   'yandex'        =>  false,  // show in drop down
    ])->setType('panel')->getForm();
```
#### or setup providers as string
```php
    echo (new Auth())->setProviders('vkontakte=false,odnoklassniki=true,facebook=true,google=true,yandex=true')->setType('panel')->getForm();
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
```
Unavailable
```
##[Issues](https://github.com/stanislav-web/phalcon-ulogin/issues "Issues")

## Screen (For what I use it?)
```
Unavailable
```