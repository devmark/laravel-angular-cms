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

    angular.module('postCategoryModule')
        .controller('PostCategoryListController', PostCategoryListController);

    function PostCategoryListController(postCategoryService, $location, toaster, $timeout, $translate) {

        var vm = this;
        //================================================
        //Variable
        //================================================
        // get product categories data
        postCategoryService.get({
            parent_id: 1
        }, {cache: false}).then(function (result) {
            vm.categories = result;
        });

        //================================================
        //Event
        //================================================
        vm.getChildrenLoading = {};
        vm.getChildren = function (category) {
            vm.getChildrenLoading[category.id] = true;
            $timeout(function () {
                postCategoryService.get({
                    parent_id: category.id
                }, {cache: false}).then(function (result) {
                    category.children = result;
                    vm.getChildrenLoading[category.id] = false;
                });
            });
        };
        vm.delete = function (scope) {
            var data = scope.$modelValue;
            postCategoryService.destroy(data.id).then(function () {
                    // remove the row data
                    scope.remove();

                    // show notifications
                    toaster.pop('success', '', $translate.instant('post.category.delete_success_msg'));
                },
                function () {
                    // show notifications
                    toaster.pop('error', '', $translate.instant('post.category.delete_error_msg'));
                });

        };

        // Category move event
        vm.categoryTree = {
            collapsed: true,
            accept: function (sourceNodeScope, destNodesScope, destIndex) {
                return true;
            },
            dragStart: function (event) {
                var oldParent = event.dest.nodesScope.$parent.$parent.$modelValue;
                
                //if old parent get only a children, remove the handle tool
                if (!_.isUndefined(oldParent) && oldParent.children.length === 1) {
                    oldParent.rgt = oldParent.lft + 1;
                }
            },
            dropped: function (event) {
                if (!event.pos.moving) {
                    return;
                }

                var source = event.source.nodeScope.$modelValue.id;
                console.log('sad', event.source.nodeScope);
                var categoryAPI = {};

                var dest = event.dest.nodesScope.$parent.$modelValue;

                if (dest) {
                    categoryAPI.parent_id = dest.id;
                }
                for (var i = 0; i < event.dest.nodesScope.$modelValue.length; i++) {
                    if (event.dest.nodesScope.$modelValue[i].id === source) {
                        if (event.dest.nodesScope.$modelValue[i - 1]) {
                            categoryAPI.prev_id = event.dest.nodesScope.$modelValue[i - 1].id;
                        }
                        if (event.dest.nodesScope.$modelValue[i + 1]) {
                            categoryAPI.next_id = event.dest.nodesScope.$modelValue[i + 1].id;
                        }
                        break;
                    }
                }

                postCategoryService.move(source, categoryAPI).then(function (result) {
                    if (!_.isUndefined(dest) && dest.children.length > 0) {
                        dest.rgt += 1;
                    }

                    // show notifications
                    toaster.pop('success', '', $translate.instant('post.category.update_success_msg'));

                });

            }
        };

    }


})();
