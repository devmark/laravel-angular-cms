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

    angular.module('meModule')
        .factory('meService', meService);

    function meService(Restangular) {

        //private variables
        var _self = this;
        var service = {};

        service.get = function (param) {
            param = param || {};
            return Restangular.one('me').get(param);
        };
        service.update = function (data) {
            return Restangular.one('me').customPUT(data);
        };

        return service;
    }
})();
