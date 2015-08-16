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

    angular.module('postModule', ['ui.router'])
        .run(function (Restangular) {
            //================================================
            // Restangular init
            //================================================
            Restangular.extendCollection('posts', function (model) {
                return model;
            });
            Restangular.extendModel('posts', function (model) {
                // variable ===================================================


                // event ===================================================
                model.init = function () {
                    _.extend(model, {
                        id: '',
                        status: 'published',
                        visibility: 'public',
                        categories: []
                    });
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
                .state('main.post-list', {
                    url: 'posts',
                    templateUrl: 'app/components/post/post.list.html',
                    controller: 'PostListController as listCtrl',
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
                .state('main.post-create', {
                    url: 'posts/create',
                    templateUrl: 'app/components/post/post.form.html',
                    controller: 'PostFormController as formCtrl',
                    resolve: {
                        post: function () {
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
                .state('main.post-edit', {
                    url: 'posts/:id/edit',
                    templateUrl: 'app/components/post/post.form.html',
                    controller: 'PostFormController as formCtrl',
                    resolve: {
                        post: function (postService, $stateParams) {
                            return postService.find($stateParams.id, {cache: false});
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
