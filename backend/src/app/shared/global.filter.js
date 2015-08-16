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

    angular.module('global.filter', [])
        /*
         * gen num range
         */
        .filter('range', function () {
            return function (input) {
                var lowBound, highBound;
                switch (input.length) {
                    case 1:
                        lowBound = 0;
                        highBound = parseInt(input[0]) - 1;
                        break;
                    case 2:
                        lowBound = parseInt(input[0]);
                        highBound = parseInt(input[1]);
                        break;
                    default:
                        return input;
                }
                var result = [];
                for (var i = lowBound; i <= highBound; i++)
                    result.push(i);
                return result;
            };
        })

})();
