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
        .module('theme.directives', [])
        .directive('slideOutNav', function ($timeout) {
            return {
                restrict: 'A',
                scope: {
                    show: '=slideOutNav'
                },
                link: function (scope, element, attr) {
                    scope.$watch('show', function (newVal, oldVal) {
                        if (newVal) {
                            element.slideDown({
                                complete: function () {
                                    $timeout(function () {
                                        scope.$apply();
                                    });
                                }
                            });
                        } else if (!newVal) {
                            element.slideUp({
                                complete: function () {
                                    $timeout(function () {
                                        scope.$apply();
                                    });
                                }
                            });
                        }
                    });
                }
            };
        });

})();
