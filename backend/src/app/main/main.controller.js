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

    angular.module('backend')
        .controller('MainController', MainController);
    function MainController($location, userService, authenticationService) {

        var vm = this;

        //============================================
        //User Control
        //============================================
        if (authenticationService.check()) {
            userService.getMe({}).then(function (result) {
                vm.me = result;
            });
        }
        vm.logout = function () {
            authenticationService.logout().then(function (result) {
                $location.path('auth/login');
            }, function (result) {
                $location.path('auth/login');
            });
        };

    }
})();