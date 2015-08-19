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

    angular.module('userModule').factory('userService', userService);
    function userService(Restangular) {

        var service = {};

        service.init = function () {
            return Restangular.one('users', '');
        };
        service.get = function (param, httpConfig) {
            param = param || {};
            httpConfig = httpConfig || {};
            return Restangular.all('users').withHttpConfig(httpConfig).getList(param);
        };
        service.find = function (id, httpConfig) {
            httpConfig = httpConfig || {};
            return Restangular.one('users', id).withHttpConfig(httpConfig).get();
        };
        service.update = function (id, data) {
            return Restangular.one('users', id).customPUT(data);
        };
        service.store = function (data) {
            return Restangular.all('users').post(data);
        };
        service.destroy = function (id) {
            return Restangular.one('users', id).remove();
        };

        service.getMe = function (param, httpConfig) {
            param = param || {};
            httpConfig = httpConfig || {};
            return Restangular.one('me', '').withHttpConfig(httpConfig).get(param);
        };
        service.updateMe = function (data) {
            return Restangular.one('me').customPUT(data);
        };


        return service;

    }
})();
