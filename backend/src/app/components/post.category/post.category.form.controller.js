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

    angular.module('postCategoryModule').controller('PostCategoryFormController', PostCategoryFormController);

    function PostCategoryFormController($location, postCategoryService, messageService, toaster, $translate, category, Restangular) {

        var vm = this;
        //==========================================
        // init
        //==========================================
        vm.category = (_.isEmpty(category) || _.isUndefined(category)) ? postCategoryService.init() : category;


        //==========================================
        // Load data
        //==========================================
        postCategoryService.get().then(function (result) {
            vm.categories = result;
        });


        //==========================================
        // Function
        //==========================================
        vm.generateSlug = function () {
            vm.generateSlugLoading = true;
            Restangular.all('slug').post({'slug': vm.category.name}).then(function (result) {
                vm.generateSlugLoading = false;
                vm.category.slug = result.slug;
            });
        };


        //==========================================
        // save
        //==========================================
        var save = function () {
            var category = angular.copy(vm.category);

            if (category.id !== '') {
                return postCategoryService.update(category.id, category);
            } else {
                return postCategoryService.store(category);
            }
        };

        vm.saveLoading = false;
        vm.save = function () {
            vm.saveLoading = true;
            save().then(function (result) {
                vm.saveLoading = false;
                toaster.pop('success', '', $translate.instant('post.category.update_success_msg'));
            }, function (result) {
                vm.saveLoading = false;
                messageService.formError(result);
            });
        };

        vm.saveAndExit = function () {
            vm.saveLoading = true;
            save().then(function (result) {
                vm.saveLoading = false;
                toaster.pop('success', '', $translate.instant('post.category.update_success_msg'));
                $location.path('/posts/categories');
            }, function (result) {
                vm.saveLoading = false;
                messageService.formError(result);
            });
        };

    }
})();
