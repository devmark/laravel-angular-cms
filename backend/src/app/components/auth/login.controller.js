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

    angular.module('authModule').controller('LoginController', LoginController);
    function LoginController($location, authenticationService, toaster) {

        var vm = this;
        vm.form = {
            email: '',
            password: ''
        };

        vm.loading = false;
        vm.login = function () {
            vm.loading = true;
            authenticationService.login(vm.form).then(function (result) {
                $location.path('/');
            }, function (result) {
                toaster.pop('error', '', result.data.result.message);
                vm.loading = false;
            });
        };

    }
})();
