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

    angular.module('userModule').factory('roleService', roleService);
    function roleService(Restangular) {

        var service = {};

        service.init = function () {
            return Restangular.one('roles', '');
        };
        service.get = function (param, httpConfig) {
            param = param || {};
            httpConfig = httpConfig || {};
            return Restangular.all('roles').withHttpConfig(httpConfig).getList(param);
        };
        service.find = function (id, httpConfig) {
            httpConfig = httpConfig || {};
            return Restangular.one('roles', id).withHttpConfig(httpConfig).get();
        };
        service.update = function (id, data) {
            return Restangular.one('roles', id).customPUT(data);
        };
        service.store = function (data) {
            return Restangular.all('roles').post(data);
        };
        service.destroy = function (id) {
            return Restangular.one('roles', id).remove();
        };

        return service;

    }
})();
