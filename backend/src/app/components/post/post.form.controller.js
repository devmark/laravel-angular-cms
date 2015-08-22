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

    angular.module('postModule').controller('PostFormController', PostFormController);
    function PostFormController($scope, postService, postCategoryService, messageService, moment, angularMomentConfig, toaster, $translate, post, $location, Restangular, $q) {

        var vm = this;

        vm.post = (_.isEmpty(post) || _.isUndefined(post)) ? postService.init() : post;
        console.log(vm.post);
        //==========================================
        // Load Data
        //==========================================
        if (vm.post.categories.length > 0) {
            postCategoryService.get({'ids[]': vm.post.categories}).then(function (result) {
                vm.post.categories = result;
            });
        }

        vm.categories = [];
        vm.refreshCategory = function (string) {
            if (string !== '') {
                postCategoryService.get({name: string}).then(function (result) {
                    vm.categories = result;
                });
            }
        };


        //==========================================
        // transform Data for display
        //==========================================
        vm.post.published_at = moment(vm.post.published_at).format();


        //==========================================
        // Date & Time picker
        //==========================================
        vm.open = function ($event) {
            $event.preventDefault();
            $event.stopPropagation();
            vm.opened = true;
        };

        vm.dateOptions = {
            startingDay: 1,
            showWeeks: false,
            initDate: null,
            maxDate: moment().add(3, 'year').format('YYYY-MM-DD')
        };

        vm.form = {
            publishedDate: moment(vm.post.published_at).format('YYYY-MM-DD'),
            publishedTime: vm.post.published_at
        };

        //==========================================
        // Function
        //==========================================
        vm.generateSlug = function () {
            vm.generlateSlugLoading = true;
            Restangular.all('slug').post({'slug': vm.post.title}).then(function (result) {
                vm.generlateSlugLoading = false;
                vm.post.slug = result.slug;
            });
        };

        //==========================================
        // save
        //==========================================
        var save = function () {
            var post = angular.copy(vm.post);
            var deferred = $q.defer();

            post.categories = _.pluck(vm.post.categories, 'id');

            post.published_at = moment(vm.form.publishedDate).hour(new Date(vm.form.publishedTime).getHours()).minutes(new Date(vm.form.publishedTime).getMinutes()).tz(angularMomentConfig.timezone).utc().format('YYYY-MM-DD HH:mm:ss');

            if (post.id !== '') {
                postService.update(post.id, post).then(function (result) {
                    deferred.resolve(result);
                }, function (result) {
                    deferred.reject(result);
                });
            } else {
                postService.store(post).then(function (result) {
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
                toaster.pop('success', '', $translate.instant('post.' + (vm.post.id !== '' ? 'update_success_msg' : 'create_success_msg')));
                if (vm.isSaveAndExit) {
                    $location.path('/posts');
                } else if (vm.post.id === '') {
                    $location.path('/posts/' + result.id + '/edit');
                } else {
                    vm.post = result;
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
            if (vm.post.id !== '') {
                vm.deleteLoading = true;
                postService.destroy(vm.post.id).then(function () {
                    toaster.pop('success', '', $translate.instant('post.delete_success_msg'));
                    $location.path('/posts');
                }, function (result) {
                    deferred.reject(result);
                });
            }

        };

    }
})();
