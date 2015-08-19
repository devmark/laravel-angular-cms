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

    angular.module('userModule').controller('UserFormController', UserFormController);
    function UserFormController($scope, $http, $window, userService, messageService, moment, angularMomentConfig, toaster, $translate, user, $filter, $location, $timeout, Restangular, $q, roleService) {

        var vm = this;

        vm.user = (_.isEmpty(user) || _.isUndefined(user)) ? userService.init() : user;

        //==========================================
        // Load Data
        //==========================================


        //==========================================
        // Function
        //==========================================
        vm.roles = [];
        vm.refreshRole = function (string) {
            if (string !== '') {
                roleService.get({search: string}).then(function (result) {
                    vm.roles = result;
                });
            }
        };

        //==========================================
        // save
        //==========================================
        var save = function () {
            var user = angular.copy(vm.user);
            user.roles = _.pluck(user.roles, 'id');
            var deferred = $q.defer();
            if (user.id !== '') {
                userService.update(user.id, user).then(function (result) {
                    deferred.resolve(result);
                }, function (result) {
                    deferred.reject(result);
                });
            } else {
                userService.store(user).then(function (result) {
                    deferred.resolve(result);
                }, function (result) {
                    deferred.reject(result);
                });
            }

            return deferred.promise;

        };

        vm.isSaveAndExit = false;
        vm.saveLoading = false;
        vm.save = function () {
            vm.saveLoading = true;
            save().then(function (result) {
                vm.saveLoading = false;
                toaster.pop('success', '', $translate.instant('user.' + (vm.user.id !== '' ? 'update_success_msg' : 'create_success_msg')));
                if (vm.isSaveAndExit) {
                    $location.path('users');
                } else if (vm.user.id === '') {
                    $location.path('users/' + result.id + '/edit');
                } else {
                    vm.user = result;
                }
            }, function (result) {
                vm.saveLoading = false;
                messageService.formError(result);
            });
        };

        vm.saveAndExit = function () {
            vm.isSaveAndExit = true;
            vm.save();
        };

        //==========================================
        // Delete
        //==========================================
        vm.deleteLoading = false;
        vm.delete = function () {
            if (vm.user.id !== '') {
                vm.deleteLoading = true;
                userService.destroy(vm.user.id).then(function () {
                    toaster.pop('success', '', $translate.instant('user.delete_success_msg'));
                    $location.path('users');
                }, function (result) {
                    vm.deleteLoading = false;
                    toaster.pop('success', '', $translate.instant('user.delete_error_msg'));
                });
            }

        };

    }
})();
