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

            Restangular.extendModel('users', userRestangularModel);
            Restangular.extendModel('me', userRestangularModel);

            function userRestangularModel(model) {
                // variable ===================================================
                model.permissions = _.pluck(_.pluck(model.roles, 'permissions')[0], 'name');

                // event ===================================================
                model.hasRole = function (roleNames, validateAll) {
                    roleNames = _.isArray(roleNames) ? roleNames : [roleNames];

                    var foundRoleNames = _.filter(roleNames, function (val) {
                        return model.roles.indexOf(val) !== -1;
                    });

                    return validateAll ? foundRoleNames.length === roleNames.length : foundRoleNames.length > 0;
                };

                model.can = function (permissionNames, validateAll) {
                    permissionNames = _.isArray(permissionNames) ? permissionNames : [permissionNames];

                    var foundPermissionNames = _.filter(permissionNames, function (val) {
                        return model.permissions.indexOf(val) !== -1;
                    });

                    return validateAll ? foundPermissionNames.length === permissionNames.length : foundPermissionNames.length > 0;
                };

                model.getFullName = function () {
                    return (model.firstname || '') + ' ' + (model.lastname || '');
                };

                model.init = function () {
                    _.extend(model, {
                        id: '',
                        active: true,
                        roles: []
                    });
                };
                // data event ===================================================
                if (model.id === '') {
                    model.init();
                } else {
                }

                return model;

            }


            Restangular.extendModel('roles', function (model) {

                model.permissionIds = _.pluck(model.permissions, 'id');

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
