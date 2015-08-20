# Laravel Angular CMS Starter Site

[![Join the chat at https://gitter.im/devmark/laravel-angular-cms](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/devmark/laravel-angular-cms?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)


![screenshot](https://raw.githubusercontent.com/devmark/laravel-angular-cms/master/screenshot/user-list.png)

## Features:
* Independent Laravel, Angular
* Laravel 5.1 for API
  - Include Json Auth Token
  - Include Data Transformer
  - Include API Data Exception
  - Output JSON or others
  - Include CSRF Protection
  - Timezone
* Angular 1.4 for Backend
  - Include AdminLTE template
  - Include ui-router, Restangular etc ...
  - Timezone
  - Support Multi-Languages interface
* Backend
	* User & Role management
	* Manage Media & Media Categories
	* Manage posts and posts categories
* Frontend
	* Soon
	
-----
## Install:
* [Step 1: Get the code](#step1)
* [Step 2: Init api](#step2)
* [Step 3: Init Backend](#step3)
* [Step 4: Production](#step4)
* [Step 5: Start](#step5)

-----
<a name="step1"></a>
### Step 1: Get the code

`https://github.com/devmark/laravel-angular-cms/archive/master.zip`

-----
<a name="step2"></a>
### Step 2: Init api
 
#### Init Laravel
1. Move to `api` directory
2. Run `composer install`

#### Init Database
3. Setup database config in `.env` file (copy from `.env.example`)
4. Run `php artisan migrate --seed`

-----
<a name="step3"></a>
### Step 3: Init Backend

This project makes use of Bower. You must first ensure that Node.js (included in homestead) is installed on your machine.

1. Install npm, gulp, bower 
2. Run `sudo npm install`
3. Run `bower install`
4. Edit `backend/src/index.js`, replace `cms.dev` to your api domain
5. Run `gulp serve` for development


-----
<a name="step4"></a>
### Step 4: Production

#### API
1. edit `.env` file set `APP_DEBUG` to `false`

#### Backend
1. run `gulp` in `backend` directory. It will auto copy `backend/dist` all files to `api/public/assets-backend`

#### Frontend
1. Move all frontend files to `api/public`

#### Server
Redirect backend:

##### Nginx
```
    location ~ ^/backend {
        rewrite ^/backend(.*) /assets-backend/$1 break;
    }
```

##### Apache
```
    RewriteRule ^backend/(.*)\.([^\.]+)$ /assets-backend/$1.$2 [L]
```

----
<a name="step5"></a>
### Step 5: Start 

1. access `http://yourdomain.com/backend`
You can now login to admin:

    username: `admin@emcoo.com`
    password: `adminmark`

-----
## Demo:

Soon

-----
## Screenshot:

![screenshot](https://raw.githubusercontent.com/devmark/laravel-angular-cms/master/screenshot/post-list.png)
![screenshot](https://raw.githubusercontent.com/devmark/laravel-angular-cms/master/screenshot/post-category-list.png)
![screenshot](https://raw.githubusercontent.com/devmark/laravel-angular-cms/master/screenshot/media-list.png)


-----
## API Detail:


Soon

### Add Exception
Add Whatever Exception you want in `api/app/Exceptions`

Example:
```php
class EmailOrPasswordIncorrectException extends HttpException
{
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct(401, 'Email/Password is incorrect', $previous, [], 10001);
    }
}
```
Use it:
```php
    throw new EmailOrPasswordIncorrectException;
```

Output:
```json
{
    "result":{
        "status":false,
        "code":10001,
        "message":"Email\/Password is incorrect"
    }
}
```

### Add Transformer
Soon

### Config JWT
Soon

## Backend Detail:
Soon
## Frontend Detail:
1. You can move all frontend file to `api/public`