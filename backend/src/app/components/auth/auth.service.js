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

angular.module('authModule').factory('authenticationService', authenticationService);
    function authenticationService(Restangular, $sanitize, localStorageService, $q) {

        var service = {};

        // Service logic
        var sanitizeCredentials = function (credentials) {
            return {
                email: $sanitize(credentials.email),
                password: $sanitize(credentials.password)
            };
        };

        service.login = function (data) {
            data = sanitizeCredentials(data);
            var deferred = $q.defer();
            Restangular.all('auth/login').post(data).then(function (result) {
                localStorageService.set('token', result.token);
                deferred.resolve(result);
            }, function (result) {
                deferred.reject(result);
            });
            return deferred.promise;
        };

        service.logout = function () {
            var deferred = $q.defer();
            Restangular.all('auth/logout').post().then(function (result) {
                localStorageService.remove('token');
                deferred.resolve(result);
            }, function (result) {
                deferred.reject(result);
            });
            return deferred.promise;
        };

        service.getToken = function () {
            return 'Bearer ' + localStorageService.get('token');
        };

        service.check = function () {
            return localStorageService.get('token') ? 1 : 0;
        };

        return service;

    }
})();
