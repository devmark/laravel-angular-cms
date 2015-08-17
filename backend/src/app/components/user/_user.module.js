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

    angular.module('userModule', ['ui.router'])
        .run(function (Restangular) {
            //================================================
            // Restangular init
            //================================================
            Restangular.extendCollection('users', function (model) {
                return model;
            });
            Restangular.extendModel('users', function (model) {
                // variable ===================================================


                // event ===================================================
                model.init = function () {
                    _.extend(model, {
                        id: '',
                        roles: []
                    });
                };
                model.getFullName = function () {
                    return (model.firstname || '') + ' ' + (model.lastname || '');
                };

                // data event ===================================================
                if (model.id === '') {
                    model.init();
                } else {
                }

                return model;

            });
        })

        .config(function ($stateProvider) {
            $stateProvider
                .state('main.role-list', {
                    url: 'roles',
                    templateUrl: 'app/components/user/role.list.html',
                    controller: 'RoleListController as listCtrl',
                    resolve: {
                        meta: function ($rootScope, $translate, $q) {
                            var deferred = $q.defer();
                            $translate('user.roles').then(function (translation) {
                                $rootScope.meta.pageTitle = translation;
                                deferred.resolve(true);
                            }, function () {
                                deferred.reject();
                            });
                            return deferred.promise;
                        }
                    }
                })
                .state('main.user-list', {
                    url: 'users',
                    templateUrl: 'app/components/user/user.list.html',
                    controller: 'UserListController as listCtrl',
                    resolve: {
                        meta: function ($rootScope, $translate, $q) {
                            var deferred = $q.defer();
                            $translate('user.users').then(function (translation) {
                                $rootScope.meta.pageTitle = translation;
                                deferred.resolve(true);
                            }, function () {
                                deferred.reject();
                            });
                            return deferred.promise;
                        }
                    }
                })
                .state('main.user-create', {
                    url: 'users/create',
                    templateUrl: 'app/components/user/user.form.html',
                    controller: 'UserFormController as formCtrl',
                    resolve: {
                        user: function () {
                            return;
                        },
                        meta: function ($rootScope, $translate, $q) {
                            var deferred = $q.defer();
                            $translate('user.add_a_user').then(function (translation) {
                                $rootScope.meta.pageTitle = translation;
                                deferred.resolve(true);
                            }, function () {
                                deferred.reject();
                            });
                            return deferred.promise;
                        }
                    }
                })
                .state('main.user-edit', {
                    url: 'users/:id/edit',
                    templateUrl: 'app/components/user/user.form.html',
                    controller: 'UserFormController as formCtrl',
                    resolve: {
                        user: function (userService, $stateParams) {
                            return userService.find($stateParams.id, {cache: false});
                        },
                        meta: function ($rootScope, $translate, $q) {
                            var deferred = $q.defer();
                            $translate('user.edit_user').then(function (translation) {
                                $rootScope.meta.pageTitle = translation;
                                deferred.resolve(true);
                            }, function () {
                                deferred.reject();
                            });
                            return deferred.promise;
                        }
                    }
                });

        });

})();
