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

    angular.module('userModule').controller('UserListController', UserListController);

    function UserListController(userService, $location, toaster, $translate, $q, angularMomentConfig, roleService) {
        var vm = this;
        //==========================================
        // Date & Time picker
        //==========================================
        vm.open = function ($event) {
            $event.preventDefault();
            $event.stopPropagation();
            vm.opened = true;
        };

        vm.dateOptions = {
            minMode: 'month'
        };
        // Disable weekend selection
        vm.disabled = function (date, mode) {
            return ( mode === 'day' && ( date.getDay() === 0 || date.getDay() === 6 ) );
        };

        //==========================================
        // Load Data
        //==========================================
        vm.roles = [];
        vm.refreshRole = function (string) {
            if (string !== '') {
                roleService.get({search: string}).then(function (result) {
                    vm.roles = result;
                });
            }
        };


        //================================================
        // Table & Filter
        //================================================
        vm.tableLoading = false;
        vm.paginationAction = '';
        vm.rowCollection = [];
        vm.itemsByPage = 15;

        vm.filter = {
            limit: vm.itemsByPage,
            page: parseInt($location.search().page) || 1,
            search: $location.search().search || '',
            created_at: '',
            role_ids: []
        };


        vm.resetFilter = function () {
            var deferred = $q.defer();
            vm.filter.search = null;
            vm.filter.created_at = null;
            vm.filter.created_at_max = null;
            vm.filter.created_at_min = null;

            deferred.resolve();
            return deferred.promise;
        };

        vm.filterAction = function () {
            var deferred = $q.defer();

            if (vm.filter.created_at !== '' && vm.filter.created_at !== null) {
                console.log(vm.filter.created_at);
                vm.filter.created_at_max = moment.tz(vm.filter.created_at, angularMomentConfig.timezone).utc().add(1, 'months').format('YYYY-MM-DD HH:mm:ss');
                vm.filter.created_at_min = moment.tz(vm.filter.created_at, angularMomentConfig.timezone).utc().add(1, 'days').format('YYYY-MM-DD HH:mm:ss');
            }

            deferred.resolve();
            return deferred.promise;
        };
        vm.resetFilter();

        vm.callServer = function callServer(tableState) {
            var params = angular.copy(vm.filter);
            if (params.role_ids.length > 0) {
                params['role_ids[]'] = params.role_ids[0];
                delete params.role_ids;
            }

            var pagination = tableState.pagination;
            if (vm.paginationAction === 'next') {
                params.page = pagination.next;
            } else if (vm.paginationAction === 'prev') {
                params.page = pagination.prev;
            }

            vm.tableLoading = true;
            userService.get(params, {cache: false}).then(function (result) {
                tableState.pagination.next = result.meta.pagination.next_page || null;
                tableState.pagination.prev = result.meta.pagination.prev_page || null;
                //update products list
                vm.rowCollection = result;
                vm.tableLoading = false;
            });
        };


        //================================================
        //Event
        //================================================
        vm.remove = function removeItem(row) {
            var index = vm.rowCollection.indexOf(row);
            if (index !== -1 && row.id !== '') {
                userService.destroy(row.id).then(function () {
                        // remove the row data
                        vm.rowCollection.splice(index, 1);

                        // show notifications
                        toaster.pop('success', '', $translate.instant('user.delete_success_msg'));
                    },
                    function () {
                        // show notifications
                        toaster.pop('error', '', $translate.instant('user.delete_error_msg'));

                    });
            }
        };

        vm.bulkRemove = function () {
            var removePromises = [];
            _.each(vm.rowCollection, function (row, index) {
                if (row.isSelected === true && row.id !== '') {
                    removePromises.push(userService.destroy(row.id));
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
                toaster.pop('success', '', $translate.instant('user.delete_success_msg'));
            }, function () {
                // show notifications
                toaster.pop('error', '', $translate.instant('user.delete_error_msg'));
            });
        };

    }


})();

