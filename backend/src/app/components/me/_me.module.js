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

    angular.module('meModule', [])
        .run(function (Restangular) {

            //================================================
            // Restangular init
            //================================================
            Restangular.extendModel('me', function (model) {
                model.getFullName = function () {
                    return (model.firstname || '') + ' ' + (model.lastname || '');
                };
                return model;
            });

        });
})();
