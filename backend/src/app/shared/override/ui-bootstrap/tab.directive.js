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

    angular.module('backend')
        .run(function ($templateCache) {
            $templateCache.put('template/tabs/tabset.html', '<div> <ul class="{{tabClass}} nav nav-{{type || \'tabs\'}}" ng-class="{\'nav-stacked\': vertical, \'nav-justified\': justified}" ng-transclude></ul> <div class="tab-content"> <div class="tab-pane" ng-repeat="tab in tabs" ng-class="{active: tab.active}" tab-content-transclude="tab"> </div></div></div>');
        })
        .config(function ($provide) {

            // This adds the decorator to the tabset directive
            // @see https://github.com/angular-ui/bootstrap/blob/master/src/tabs/tabs.js#L88
            $provide.decorator('tabsetDirective', function ($delegate) {

                // The `$delegate` contains the configuration of the tabset directive as
                // it is defined in the ui bootstrap module.
                var directive = $delegate[0];

                // This is the original link method from the directive definition
                var link = directive.link;

                // This sets a compile method to the directive configuration which will provide
                // the modified/extended link method
                directive.compile = function () {

                    // Modified link method
                    return function (scope, element, attrs) {

                        // Call the original `link` method
                        link(scope, element, attrs);

                        // Get the value for the `custom-class` attribute and assign it to the scope.
                        // This is the same the original link method does for the `vertical` and ``justified` attributes
                        scope.tabClass = attrs.tabClass;
                    }
                };

                // Return the modified directive
                return $delegate;
            });
        });

})();