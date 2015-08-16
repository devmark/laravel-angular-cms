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

    angular.module('languageModule')
        .factory('languageService', function (Restangular, $q, $filter, $modal, $translate, amMoment, $state) {

            //private variables
            var service = {};

            service.setCurrentLanguage = function (languageCode) {
                $translate.use(languageCode);
                amMoment.changeLocale(languageCode);
                $state.go('.');
            };


            return service;
        });
})();
