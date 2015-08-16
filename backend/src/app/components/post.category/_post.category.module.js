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
                        category: function (postCategoryService, $stateParams) {
                            return postCategoryService.find($stateParams.id, {cache: false});
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
