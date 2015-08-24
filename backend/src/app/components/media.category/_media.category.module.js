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

    angular.module('mediaCategoryModule', [])
        .run(function (Restangular) {
            //================================================
            // Restangular init
            //================================================
            Restangular.extendCollection('medias', function (model) {
                return model;
            });
            Restangular.extendModel('medias', function (model) {
                model.init = function () {
                    _.extend(model, {
                        id: ''
                    });
                };

                // event ===================================================
                if (model.id === '') {
                    model.init();
                } else {
                }

                return model;

            });
        });
})();
