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

    angular.module('postCategoryModule', [])
        .run(function (Restangular, postCategoryService) {
            //================================================
            // Restangular init
            //================================================
            Restangular.extendCollection('posts/categories', function (model) {

                return model;

            });
            Restangular.extendModel('posts/categories', function (model) {
                model.init = function () {
                    _.extend(model, {
                        id: '',
                        active: 1
                    });
                };
                // event ===================================================
                if (model.id === '') {
                    model.init();
                } else {
                }

                return model;

            });

        })

        .config(function ($stateProvider, $urlRouterProvider) {
            $stateProvider
                .state('main.post-category-list', {
                    url: 'posts/categories',
                    templateUrl: 'app/components/post.category/post.category.list.html',
                    controller: 'PostCategoryListController as listCtrl',
                    resolve: {
                        hasPermission: function (userService, $state, $q) {
                            var deferred = $q.defer();
                            userService.getMe().then(function (result) {
                                if (!result.can('posts.categories.index')) {
                                    $state.go('main.index');
                                    deferred.resolve(false);
                                }
                                deferred.resolve(true);
                            }, function () {
                                $state.go('main.index');
                                deferred.reject();
                            });
                            return deferred.promise;
                        },
                        meta: function ($rootScope, $translate, $q) {
                            var deferred = $q.defer();
                            $translate('post.posts').then(function (translation) {
                                $rootScope.meta.pageTitle = translation;
                                deferred.resolve(true);
                            }, function () {
                                deferred.reject();
                            });
                            return deferred.promise;
                        }
                    }
                })
                .state('main.post-category-create', {
                    url: 'posts/categories/create',
                    templateUrl: 'app/components/post.category/post.category.form.html',
                    controller: 'PostCategoryFormController as formCtrl',
                    resolve: {
                        hasPermission: function (userService, $state, $q) {
                            var deferred = $q.defer();
                            userService.getMe().then(function (result) {
                                if (!result.can('posts.categories.store')) {
                                    $state.go('main.index');
                                    deferred.resolve(false);
                                }
                                deferred.resolve(true);
                            }, function () {
                                $state.go('main.index');
                                deferred.reject();
                            });
                            return deferred.promise;
                        },
                        category: function () {
                            return;
                        },
                        meta: function ($rootScope, $translate, $q) {
                            var deferred = $q.defer();
                            $translate('post.add_a_post').then(function (translation) {
                                $rootScope.meta.pageTitle = translation;
                                deferred.resolve(true);
                            }, function () {
                                deferred.reject();
                            });
                            return deferred.promise;
                        }
                    }
                })
                .state('main.post-category-edit', {
                    url: 'posts/categories/:id/edit',
                    templateUrl: 'app/components/post.category/post.category.form.html',
                    controller: 'PostCategoryFormController as formCtrl',
                    resolve: {
                        hasPermission: function (userService, $state, $q) {
                            var deferred = $q.defer();
                            userService.getMe().then(function (result) {
                                if (!result.can(['posts.categories.index', 'posts.categories.update'], true)) {
                                    $state.go('main.index');
                                    deferred.resolve(false);
                                }
                                deferred.resolve(true);
                            }, function () {
                                $state.go('main.index');
                                deferred.reject();
                            });
                            return deferred.promise;
                        },
                        category: function (postCategoryService, $stateParams, $q, $state) {
                            var deferred = $q.defer();
                            postCategoryService.find($stateParams.id, {cache: false}).then(function (result) {
                                deferred.resolve(result);
                            }, function () {
                                $state.go('main.post-category-list');
                                deferred.reject();
                            });
                            return deferred.promise;
                        },
                        meta: function ($rootScope, $translate, $q) {
                            var deferred = $q.defer();
                            $translate('post.edit_post').then(function (translation) {
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
