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

    angular.module('mediaCategoryModule').factory('mediaCategoryService', mediaCategoryService);
    function mediaCategoryService(Restangular) {

        var service = {};

        service.init = function () {
            return Restangular.one('media/categories', '');
        };
        service.get = function (param, httpConfig) {
            param = param || {};
            httpConfig = httpConfig || {};
            return Restangular.all('media/categories').withHttpConfig(httpConfig).getList(param);
        };
        service.find = function (id) {
            return Restangular.one('media/categories', id).get();
        };
        service.update = function (id, data) {
            return Restangular.one('media/categories', id).customPUT(data);
        };
        service.store = function (data) {
            return Restangular.all('media/categories').post(data);
        };
        service.destroy = function (id) {
            return Restangular.one('media/categories', id).remove();
        };
        return service;

    }
})();
