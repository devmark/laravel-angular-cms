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

    angular
        .module('theme.services', [])
        .service('siteThemeService', siteThemeService);
    function siteThemeService($rootScope) {
        this.settings = {
            isOpenSidebar: true,
            isOpenControlSidebar: false,
            isBoxedLayout: false,
            isFixedLayout: true,
            fullscreen: false
        };

        this.get = function (key) {
            return this.settings[key];
        };
        this.set = function (key, value) {
            this.settings[key] = value;
            $rootScope.$broadcast('siteThemeService:changed', {
                key: key,
                value: this.settings[key]
            });
            $rootScope.$broadcast('siteThemeService:changed:' + key, this.settings[key]);
        };
        this.values = function () {
            return this.settings;
        };
    }
})();
