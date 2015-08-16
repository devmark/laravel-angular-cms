/*
 * This file is part of EMCOO TEAM PROJECT.
 *
 *  (c) EMCOO TEAM
 *  (c) DevMark <mark@emcoo.com>
 *
 *  For the full copyright and license information, please view http://emcoo.com/
 *
 *
 */

(function () {
    'use strict';

    angular.module('backend', [

        'authModule',
        'postModule',
        'mediaModule',
        'mediaCategoryModule',
        'postCategoryModule',
        'settingModule',
        'languageModule',
        'meModule',

        'theme.directives',
        'theme.services',

        'global.filter',
        'global.service',
        'global.directive',

        'angular-spinkit',
        'smart-table',
        'restangular',
        'ui.router',
        'ui.bootstrap',
        'ui.bootstrap.datetimepicker',
        'ui.tree',
        'ui.select',

        'textAngular',
        'angular.filter',
        'pascalprecht.translate', //Multi Language plugin
        'angularMoment', //moment plugin, date plugin
        'toaster', //notification plugin
        'LocalStorageModule', // local storage module
        'tmh.dynamicLocale',

        'ngAnimate',
        'ngCookies',
        'ngTouch',
        'ngRoute',
        'ngSanitize'

    ])
        //================================================
        // Translate Config
        //================================================
        .config(function ($translateProvider) {
            $translateProvider.useStaticFilesLoader({
                prefix: 'app/languages/locale-',
                suffix: '.json'
            });

            $translateProvider.useCookieStorage();
            $translateProvider.useSanitizeValueStrategy('sanitize');
            $translateProvider.preferredLanguage('en-us');
            $translateProvider.fallbackLanguage(['en-us', 'zh-hk', 'zh-cn']);

        })
        //================================================
        // UI select Config
        //================================================
        .config(function (uiSelectConfig) {
            uiSelectConfig.theme = 'bootstrap';
            uiSelectConfig.resetSearchInput = true;
        })
        //================================================
        // Check isLogin when run system
        //================================================
        .run(function ($rootScope, $location, authenticationService, localStorageService, amMoment, tmhDynamicLocale, settingService, $translate, $timeout) {
            var routesThatRequireAuth = ['/auth/login', '/auth/logout'];

            //================================================
            // page route event
            //================================================
            $rootScope.pageViewLoading = false;
            $rootScope.$on('$stateChangeStart', function (event, next, current) {
                if (!_(routesThatRequireAuth).contains($location.path()) && !authenticationService.check()) {
                    console.log('Oops! please login!');
                    $location.path('auth/login');
                }
                $rootScope.pageViewLoading = true;
            });
            $rootScope.$on('$stateChangeSuccess', function () {
                $timeout(function () {
                    $rootScope.pageViewLoading = false;
                }, 500);
            });

            //================================================
            // page title init
            //================================================
            $translate('panel.name').then(function (translation) {
                $rootScope.siteTitle = translation;
            });
            $rootScope.meta = {
                pageTitle: ''
            };

            //================================================
            // global variable init
            //================================================
            $rootScope.$location = $location;


            //================================================
            // timezone init
            //================================================
            amMoment.changeTimezone(settingService.find('timezone'));

            //================================================
            // currency init
            //================================================
            if (!localStorageService.get('currency')) {
                tmhDynamicLocale.set('zh-hk');
                localStorageService.set('currency', settingService.get('currency'));
            }

        })
        .run(function ($location, authenticationService, localStorageService, $timeout, Restangular, $http, $q) {

            var refreshAccessToken = function () {
                var deferred = $q.defer();
                console.log('start get refresh token');

                $http({
                    method: 'POST', url: 'http://cms.dev/api/admin/auth/refresh-token', headers: {
                        'Authorization': authenticationService.getToken()
                    }
                }).success(function (data, status, headers, config) {
                    console.log('set token');
                    localStorageService.set('token', data.result.token);
                    deferred.resolve(data.result.token);
                }).error(function (data, status, headers, config) {
                    console.log('Oops! Expired Token, please login again! ');
                    $location.path('auth/login');
                    deferred.reject(data);
                });

                return deferred.promise;
            };

            Restangular.setErrorInterceptor(function (response, deferred, responseHandler) {
                if (response.status === 400 || response.message === 'token_expired') {
                    return refreshAccessToken().then(function () {
                        response.config.headers.Authorization = authenticationService.getToken();
                        // Repeat the request and then call the handlers the usual way.
                        $http(response.config).then(responseHandler, deferred.reject);
                        return false;
                        // Be aware that no request interceptors are called this way.
                    });
                    return false; // error handled
                }
                console.log('error not handled');
                return true; // error not handled
            });


            //Restangular.setErrorInterceptor(function (response, deferred, responseHandler) {
            //    if (response.status === 401 || response.message === 'token_expired') {
            //        refreshAccessToken().then(function () {
            //            response.config.headers.Authorization = authenticationService.getToken();
            //            // Repeat the request and then call the handlers the usual way.
            //            $http(response.config).then(responseHandler, deferred.reject)
            //                .then(function (httpResponse) {
            //                    deferred.resolve(httpResponse);
            //                });
            //
            //            // Be aware that no request interceptors are called this way.
            //        });
            //        return false; // error handled
            //    }
            //    return true; // error not handled
            //});


            Restangular.addFullRequestInterceptor(function (element, operation, route, url, headers, params, httpConfig) {
                //================================================
                // Token setup when load each request
                //================================================
                headers.Authorization = authenticationService.getToken();

                return {
                    element: element,
                    headers: headers,
                    params: params,
                    httpConfig: httpConfig
                };

            });


        })


        .config(function ($httpProvider, localStorageServiceProvider) {

            $httpProvider.defaults.useXDomain = true;
            delete $httpProvider.defaults.headers.common['X-Requested-With'];

            //================================================
            // Local storage module init
            //================================================
            localStorageServiceProvider
                .setPrefix('yoovcms')
                .setStorageType('localStorage')
                .setNotify(true, true);
        })

        //================================================
        // Plugin: Smart-table init
        //================================================
        .config(function (stConfig) {
            stConfig.select.mode = 'multiple';
        })

        //================================================
        // Plugin: Restangular init
        //================================================
        .config(function (RestangularProvider) {
            RestangularProvider.setBaseUrl('http://cms.dev/api/admin');
            RestangularProvider.setRestangularFields({
                ids: "_ids"
            });

            RestangularProvider.addResponseInterceptor(function (data, operation, what, url, response, deferred) {
                var extractedData;
                if (operation === 'getList') {
                    // handle the data and meta data
                    extractedData = data.result;
                    if (!_.isUndefined(data.meta)) {
                        extractedData.meta = data.meta;
                    }
                } else {
                    extractedData = data.result;
                }

                return extractedData;
            });

            RestangularProvider.setDefaultHttpFields({
                cache: true
            });

        })
        .config(function ($locationProvider, $stateProvider, $urlRouterProvider) {
            $locationProvider.html5Mode(true);
            $stateProvider
                .state('main', {
                    url: '/',
                    abstract: true,
                    templateUrl: 'app/main/main.html'
                })
                .state('fullscreen', {
                    url: '/',
                    abstract: true,
                    templateUrl: 'app/main/fullscreen.html'
                })
                .state('main.index', {
                    url: '',
                    templateUrl: 'app/main/dashboard.html',
                    resolve: {
                        meta: function ($rootScope, $translate, $q) {
                            var deferred = $q.defer();
                            $translate('panel.dashboard').then(function (translation) {
                                $rootScope.meta.pageTitle = translation;
                                deferred.resolve(true);
                            }, function () {
                                deferred.reject();
                            });
                            return deferred.promise;
                        }
                    }
                })
                .state('main.not-found', {
                    url: '404',
                    templateUrl: 'app/template/404.html',
                    resolve: {
                        meta: function ($rootScope, $translate, $q) {
                            var deferred = $q.defer();
                            $translate('panel.dashboard').then(function (translation) {
                                $rootScope.meta.pageTitle = translation;
                                deferred.resolve(true);
                            }, function () {
                                deferred.reject();
                            });
                            return deferred.promise;
                        }
                    }
                });

            $urlRouterProvider.otherwise('/');
        });

})();
