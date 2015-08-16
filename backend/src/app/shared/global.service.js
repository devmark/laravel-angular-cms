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

    angular.module('global.service', [])
        .factory('messageService', messageService);
    function messageService(toaster) {
        var _self = this;
        var service = {};
        service.formError = function (result) {
            toaster.pop('error', '', result.data.message);
        };

        return service;
    }
})();
