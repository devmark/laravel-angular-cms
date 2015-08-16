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

    angular.module('mediaModule').factory('mediaService', mediaService);
    function mediaService(Restangular) {

        var service = {};

        service.init = function () {
            return Restangular.one('media', '');
        };
        service.get = function (param, httpConfig) {
            param = param || {};
            httpConfig = httpConfig || {};
            return Restangular.all('media').withHttpConfig(httpConfig).getList(param);
        };
        service.find = function (id, httpConfig) {
            httpConfig = httpConfig || {};
            return Restangular.one('media', id).withHttpConfig(httpConfig).get();
        };
        service.update = function (id, data) {
            return Restangular.one('media', id).customPUT(data);
        };
        service.store = function (data) {
            return Restangular.all('media').
                withHttpConfig({transformRequest: angular.identity})
                .customPOST(data, undefined, undefined, {'Content-Type': undefined});
        };
        service.destroy = function (id) {
            return Restangular.one('media', id).remove();
        };

        return service;

    }
})();
