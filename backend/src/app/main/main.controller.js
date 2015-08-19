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
    function MainController(siteThemeService, $location, userService, authenticationService) {

        var vm = this;

        //For footer
        vm.today = new Date();

        //============================================
        //User Control
        //============================================
        if (authenticationService.check()) {
            userService.getMe().then(function (result) {
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

        //============================================
        //Theme Control
        //============================================
        vm.isOpenSidebar = siteThemeService.get('isOpenSidebar');
        vm.isOpenControlSidebar = siteThemeService.get('isOpenControlSidebar');
        vm.isFixedLayout = siteThemeService.get('isFixedLayout');
        vm.isBoxedLayout = siteThemeService.get('isBoxedLayout');
        vm.fullscreen = siteThemeService.get('fullscreen');

        vm.toggleSidebar = function ($event) {

            $event.preventDefault();
            $event.stopPropagation();

            vm.isOpenSidebar = !vm.isOpenSidebar;
            siteThemeService.set('isOpenSidebar', vm.isOpenSidebar);
            console.log(vm.isOpenSidebar);
        };
        vm.toggleControlSidebar = function ($event) {
            $event.preventDefault();
            $event.stopPropagation();
            vm.isOpenControlSidebar = !vm.isOpenControlSidebar;
            siteThemeService.set('isOpenControlSidebar', vm.isOpenSidebar);
        };


    }
})();