'use strict';

var path = require('path');
var gulp = require('gulp');
var conf = require('./conf');

var browserSync = require('browser-sync');

var $ = require('gulp-load-plugins')();

var wiredep = require('wiredep').stream;
var _ = require('lodash');

gulp.task('own-styles', function () {
    var lessOptions = {
        options: [
            path.join(conf.paths.src, '/app')
        ]
    };

    var injectFiles = gulp.src([
        path.join(conf.paths.src, '/assets/styles/less/*.less')
    ], { read: false });

    var injectOptions = {
        transform: function(filePath) {
            filePath = filePath.replace(conf.paths.src + '/app/', '');
            return '@import "' + filePath + '";';
        },
        starttag: '// own-styles-injector',
        endtag: '// own-styles-endinjector',
        addRootSlash: false
    };
    
    return gulp.src([
        path.join(conf.paths.src, '/app/styles.less')
    ])
        .pipe($.inject(injectFiles, injectOptions))
        .pipe(wiredep(_.extend({}, conf.wiredep)))
        .pipe($.sourcemaps.init())
        .pipe($.less(lessOptions)).on('error', conf.errorHandler('Less'))
        .pipe($.autoprefixer()).on('error', conf.errorHandler('Autoprefixer'))
        .pipe($.sourcemaps.write())
        .pipe(gulp.dest(path.join(conf.paths.tmp, '/serve/app/')))
        .pipe(browserSync.reload({ stream: true }));
});
