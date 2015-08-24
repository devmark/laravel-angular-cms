/*
 * This file is part of YOOV TEAM PROJECT.
 *
 * (c) YOOV TEAM
 * (c) DevMark <mark@yoov.com>
 *
 * For the full copyright and license information, please view http://yoov.com/
 *
 */
(function () {
    'use strict';

    angular.module('mediaModule', ['ui.router', 'ngFileUpload'])
        .run(function (Restangular, languageService, $filter, $log) {
            //================================================
            // Restangular init
            //================================================
            Restangular.extendCollection('medias', function (model) {
                return model;
            });
            Restangular.extendModel('medias', function (model) {
                model.init = function () {
                    _.extend(model, {
                        id: ''
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
                .state('main.media-list', {
                    url: 'media',
                    templateUrl: 'app/components/media/media.list.html',
                    controller: 'MediaListController as listCtrl',
                    resolve: {
                        hasPermission: function (userService, $state, $q) {
                            var deferred = $q.defer();
                            userService.getMe().then(function (result) {
                                if (!result.can('media.index')) {
                                    $state.go('main.index');
                                    deferred.resolve(false);
                                }
                                deferred.resolve(true);
                            }, function () {
                                $state.go('main.index');
                                deferred.reject(false);
                            });
                            return deferred.promise;
                        },
                        meta: function ($rootScope, $translate, $q) {
                            var deferred = $q.defer();
                            $translate('media.media').then(function (translation) {
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

