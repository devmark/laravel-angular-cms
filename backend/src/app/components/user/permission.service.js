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

    angular.module('userModule').factory('permissionService', permissionService);
    function permissionService(Restangular) {

        var service = {};

        service.init = function () {
            return Restangular.one('permissions', '');
        };
        service.get = function (param, httpConfig) {
            param = param || {};
            httpConfig = httpConfig || {};
            return Restangular.all('permissions').withHttpConfig(httpConfig).getList(param);
        };
        service.find = function (id, httpConfig) {
            httpConfig = httpConfig || {};
            return Restangular.one('permissions', id).withHttpConfig(httpConfig).get();
        };
        service.update = function (id, data) {
            return Restangular.one('permissions', id).customPUT(data);
        };
        service.store = function (data) {
            return Restangular.all('permissions').post(data);
        };
        service.destroy = function (id) {
            return Restangular.one('permissions', id).remove();
        };

        return service;

    }
})();
