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

    angular.module('mediaModule').controller('MediaListController', MediaListController);

    function MediaListController($scope, mediaService, $location, toaster, $translate, $q, mediaCategoryService, $state) {

        var vm = this;
        //================================================
        // Upload
        //================================================
        vm.files = [];
        $scope.$watch('listCtrl.files', function () {
            console.log('sd');
            vm.upload(vm.files);
        });
        vm.upload = function (files) {
            if (files && files.length) {
                vm.uploadLoading = true;
                var uploadPromises = [];
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    var formData = new FormData();
                    formData.append('name', file.name);
                    if (vm.filter.category_ids[0] > 0) {
                        formData.append('media_category_id', vm.filter.category_ids[0]);
                    }
                    formData.append('attachment', file);
                    var promise = mediaService.store(formData).then(
                        function (result) {
                            vm.rowCollection.push(result);
                        }, function () {
                            toaster.pop('error', '', $translate.instant('media.update_error_msg'));
                        }
                    );
                    uploadPromises.push(promise);
                }

                $q.all(uploadPromises).then(function () {
                    vm.uploadLoading = false;
                    // show notifications
                    toaster.pop('success', '', $translate.instant('media.update_success_msg'));
                });
            }
        };


        //================================================
        // categories
        //================================================
        //get filter data (category)
        mediaCategoryService.get({}, {cache: false}).then(function (result) {
            vm.categories = result;
        });

        vm.categoryFormData = {
            name: ''
        };
        vm.addCategory = function () {
            vm.categorySaveLoading = true;
            mediaCategoryService.store(vm.categoryFormData).then(function (result) {
                vm.categories.push(result);
                vm.categorySaveLoading = false;
                vm.categoryFormData = {
                    name: ''
                };
            });
        };
        vm.removeCategory = function () {
            vm.deleteLoading = true;
            mediaCategoryService.destroy(vm.filter.category_ids[0]).then(function (result) {
                vm.deleteLoading = false;
                $state.go($state.$current, null, {reload: true});
            });
        };
        vm.bulkMove = function () {
            if (typeof vm.moveSelectedCategory === 'undefined') {
                return;
            }
            var movePromises = [];
            _.each(vm.rowCollection, function (row, index) {
                if (row.isSelected === true && row.id !== '') {
                    movePromises.push(mediaService.update(row.id, {media_category_id: vm.moveSelectedCategory.id}));
                }
            });
            $q.all(movePromises).then(function () {
                // show notifications
                toaster.pop('success', '', $translate.instant('media.move_success_msg'));
            }, function () {
                // show notifications
                toaster.pop('error', '', $translate.instant('media.move_error_msg'));
            });
        };


        //================================================
        // Table & Filter
        //================================================
        vm.tableLoading = false;
        vm.paginationAction = '';
        vm.rowCollection = [];
        vm.itemsByPage = 10;

        vm.filter = {
            limit: vm.itemsByPage,
            page: parseInt($location.search().page) || 1,
            search: $location.search().search || '',
            category_ids: []
        };

        vm.resetFilter = function () {
            var deferred = $q.defer();
            vm.filter.search = null;

            deferred.resolve();
            return deferred.promise;
        };

        vm.filterAction = function () {
            var deferred = $q.defer();
            deferred.resolve();
            return deferred.promise;
        };

        vm.callServer = function callServer(tableState) {
            var params = angular.copy(vm.filter);
            if (typeof params.category_ids !== 'undefined' && params.category_ids.length > 0) {
                params['category_ids[]'] = params.category_ids[0];
                delete params.category_ids;
            }


            var pagination = tableState.pagination;
            if (vm.paginationAction === 'next') {
                params.page = pagination.next;
            } else if (vm.paginationAction === 'prev') {
                params.page = pagination.prev;
            }

            vm.tableLoading = true;
            mediaService.get(params, {cache: false}).then(function (result) {
                tableState.pagination.next = result.meta.pagination.next_page || null;
                tableState.pagination.prev = result.meta.pagination.prev_page || null;

                vm.rowCollection = result;
                vm.tableLoading = false;
            });
        };


        //================================================
        //Event
        //================================================
        vm.saveLoading = {};
        vm.save = function (row) {
            vm.saveLoading[row.id] = true;
            var index = vm.rowCollection.indexOf(row);
            mediaService.update(row.id, {'name': row.name}).then(function (result) {
                // show notifications
                toaster.pop('success', '', $translate.instant('media.update_success_msg'));
                vm.saveLoading[row.id] = false;
            }, function () {
                // show notifications
                toaster.pop('error', '', $translate.instant('media.update_error_msg'));
                vm.saveLoading[row.id] = false;

            });
        };

        vm.remove = function (row) {
            var index = vm.rowCollection.indexOf(row);
            if (index !== -1 && row.id !== '') {
                mediaService.destroy(row.id).then(function () {
                        // remove the row data
                        vm.rowCollection.splice(index, 1);

                        // show notifications
                        toaster.pop('success', '', $translate.instant('media.delete_success_msg'));
                    },
                    function () {
                        // show notifications
                        toaster.pop('error', '', $translate.instant('media.delete_error_msg'));

                    });
            }
        };

        vm.bulkRemove = function () {
            var removePromises = [];
            _.each(vm.rowCollection, function (row, index) {
                if (row.isSelected === true && row.id !== '') {
                    removePromises.push(mediaService.destroy(row.id));
                }
            });
            $q.all(removePromises).then(function () {
                var newRowCollection = [];
                for (var i = 0; i < vm.rowCollection.length; i++) {
                    if (!(vm.rowCollection[i].isSelected === true && vm.rowCollection[i].id !== '')) {
                        newRowCollection.push(vm.rowCollection[i]);
                    }
                }
                vm.rowCollection = newRowCollection;

                // show notifications
                toaster.pop('success', '', $translate.instant('media.delete_success_msg'));
            }, function () {
                // show notifications
                toaster.pop('error', '', $translate.instant('media.delete_error_msg'));
            });
        };


    }

})();
