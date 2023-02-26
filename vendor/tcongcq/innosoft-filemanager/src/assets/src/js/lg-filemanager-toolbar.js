/**!
 * lg-share.js | 0.0.1 | August 1st 2016
 * http://sachinchoolur.github.io/lg-share.js
 * Copyright (c) 2016 Sachin N;
 * @license Apache 2.0
 */(function(f){if(typeof exports==="object"&&typeof module!=="undefined"){module.exports=f()}else if(typeof define==="function"&&define.amd){define([],f)}else{var g;if(typeof window!=="undefined"){g=window}else if(typeof global!=="undefined"){g=global}else if(typeof self!=="undefined"){g=self}else{g=this}g.LgShare = f()}})(function(){var define,module,exports;return (function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
(function (global, factory) {
    if (typeof define === "function" && define.amd) {
        define([], factory);
    } else if (typeof exports !== "undefined") {
        factory();
    } else {
        var mod = {
            exports: {}
        };
        factory();
        global.lgFilemanagerToolbar = mod.exports;
    }
})(this, function () {
    'use strict';

    var _extends = Object.assign || function (target) {
        for (var i = 1; i < arguments.length; i++) {
            var source = arguments[i];

            for (var key in source) {
                if (Object.prototype.hasOwnProperty.call(source, key)) {
                    target[key] = source[key];
                }
            }
        }

        return target;
    };

    var filemanagerToolbarOptions = {
        filemanagerToolbar: true
    };

    var FilemanagerToolbar = function FilemanagerToolbar(element) {

        this.el = element;

        this.core = window.lgData[this.el.getAttribute('lg-uid')];
        this.core.s = _extends({}, filemanagerToolbarOptions, this.core.s);

        if (this.core.s.filemanagerToolbar) {
            this.init();
        }

        return this;
    };

    FilemanagerToolbar.prototype.init = function () {
        var _this = this;
        // var shareHtml = '<span id="lg-edit" class="lg-icon"></span>';
        //
        // this.core.outer.querySelector('.lg-toolbar').insertAdjacentHTML('beforeend', shareHtml);

    };

    FilemanagerToolbar.prototype.destroy = function () {
        //
    };

    window.lgModules.filemanagerToolbar = FilemanagerToolbar;
});

},{}]},{},[1])(1)
});
