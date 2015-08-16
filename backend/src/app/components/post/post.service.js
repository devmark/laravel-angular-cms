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

    angular.module('postModule').factory('postService', postService);
    function postService(Restangular) {

        var service = {};

        service.init = function () {
            return Restangular.one('posts', '');
        };
        service.get = function (param, httpConfig) {
            param = param || {};
            httpConfig = httpConfig || {};
            return Restangular.all('posts').withHttpConfig(httpConfig).getList(param);
        };
        service.find = function (id, httpConfig) {
            httpConfig = httpConfig || {};
            return Restangular.one('posts', id).withHttpConfig(httpConfig).get();
        };
        service.update = function (id, data) {
            return Restangular.one('posts', id).customPUT(data);
        };
        service.store = function (data) {
            return Restangular.all('posts').post(data);
        };
        service.destroy = function (id) {
            return Restangular.one('posts', id).remove();
        };

        return service;

    }
})();
