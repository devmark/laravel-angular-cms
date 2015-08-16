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

    angular.module('postCategoryModule').factory('postCategoryService', postCategoryService);
    function postCategoryService(Restangular) {

        var service = {};

        service.init = function () {
            return Restangular.one('posts/categories', '');
        };
        service.get = function (param, httpConfig) {
            param = param || {};
            httpConfig = httpConfig || {};
            return Restangular.all('posts/categories').withHttpConfig(httpConfig).getList(param);
        };
        service.find = function (id) {
            return Restangular.one('posts/categories', id).get();
        };
        service.update = function (id, data) {
            return Restangular.one('posts/categories', id).customPUT(data);
        };
        service.store = function (data) {
            return Restangular.all('posts/categories').post(data);
        };
        service.destroy = function (id) {
            return Restangular.one('posts/categories', id).remove();
        };
        service.move = function (id, data) {
            data = data || {};
            return Restangular.one('posts/categories', id).customPOST(data, 'move');
        };
        return service;

    }

})();
