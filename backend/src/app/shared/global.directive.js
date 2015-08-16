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

angular
    .module('global.directive', [])
    .directive('ngReallyClick', ['$modal',
        function ($modal) {

            var ModalInstanceCtrl = function ($scope, $modalInstance) {
                $scope.ok = function () {
                    $modalInstance.close();
                };

                $scope.cancel = function () {
                    $modalInstance.dismiss('cancel');
                };
            };

            return {
                restrict: 'A',
                scope: {
                    ngReallyClick: "&"
                },
                link: function (scope, element, attrs) {
                    element.bind('click', function () {
                        var message = attrs.ngReallyMessage || "Are you sure ?";

                        var modalHtml = '<div class="modal-body">' + message + '</div>';
                        modalHtml += '<div class="modal-footer"><button class="btn btn-primary" ng-click="ok()">{{ \'button.yes\' | translate }}</button><button class="btn btn-default" ng-click="cancel()">Cancel</button></div>';

                        var modalInstance = $modal.open({
                            template: modalHtml,
                            controller: ModalInstanceCtrl
                        });

                        modalInstance.result.then(function () {
                            scope.ngReallyClick();
                        }, function () {
                            //Modal dismissed
                        });

                    });

                }
            }
        }]
);

})();
