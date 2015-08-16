# Laravel Angular CMS Starter Site

## Features:
* Laravel 5.1 for API
 ⋅⋅⋅ Include Json Auth Token
 ⋅⋅⋅ Include Data Transformer
 ⋅⋅⋅ Include API Data Exception
 ⋅⋅⋅ output JSON or others
* Angular 1.4 for Backend
 ⋅⋅⋅ Include AdminLTE template
 ⋅⋅⋅ Include ui-router, restangular etc...
* Independent Laravel, Angular
* Backend
	* (TODO)User management
	* (TODO)Manage languages
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


-----
<a name="step2"></a>
### Step 2: Init api
 
#### Init Laravel
1. move to `api` directory
2. run `composer install`

#### Init Database
3. setup database config in `.env` file (copy from `.env.example`)
4. run `php artisan migrate`
5. run `php artisan db:seed`

-----
<a name="step3"></a>
### Step 3: Init Backend

This project makes use of Bower. You must first ensure that Node.js (included in homestead) is installed on your machine.

1. install npm, gulp, bower 
2. run `sudo npm install`
3. run `bower install`
4. edit `backend/src/index.js`, replace `cms.dev` to your api domain
5. run `gulp serve` for development


-----
<a name="step4"></a>
### Step 4: Production

#### API
1. edit `.env` file set `APP_DEBUG` to `false`

#### Backend
1. run `gulp` in `backend` directory
2. copy `backend/dist` all files to `api/public/assets-backend`

#### Frontend
1. You can move all frontend file to `api/public`


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



-----

## API Detail:



### Add Exception

### Add Transformer

### Config JWT

#### Backend
1. run `gulp` in `backend` directory
2. copy `backend/dist` all files to `api/public/assets-backend`

#### Frontend
1. You can move all frontend file to `api/public`