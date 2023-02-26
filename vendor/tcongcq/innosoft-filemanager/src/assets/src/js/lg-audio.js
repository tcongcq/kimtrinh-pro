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
        global.lgAudio = mod.exports;
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

    var audioOptions = {
        audios: [],
        audio: true
    };

    var Audio = function Audio(element) {

        this.el = element;

        this.core = window.lgData[this.el.getAttribute('lg-uid')];
        this.core.s = _extends({}, audioOptions, this.core.s);

        if (this.core.s.audio) {
            this.init();
        }

        return this;
    };

    Audio.prototype.init = function () {
        var _this = this;

        var audioprocess_format = function(current_time, duration){
            var audioprocess = '';
            if(current_time > 3600){
                audioprocess += (Math.floor(current_time/3600) >= 10 ? '':'0') + Math.floor(current_time/3600) + ':';
                current_time = current_time%3600;
            }else if(duration > 3600){
                audioprocess += '00:';
            }
            if(current_time > 60){
                audioprocess += (Math.floor(current_time/60) >= 10 ? '':'0') + Math.floor(current_time/60) + ':';
                current_time = current_time%60;
            }else if(duration > 60){
                audioprocess += '00:';
            }
            audioprocess += (Math.floor(current_time) >= 10 ? '':'0') + Math.floor(current_time) + ' / ';
            if(duration > 3600){
                audioprocess += (Math.floor(duration/3600) >= 10 ? '':'0') + Math.floor(duration/3600) + ':';
                duration = duration%3600;
            }
            if(duration > 60){
                audioprocess += (Math.floor(duration/60) >= 10 ? '':'0') + Math.floor(duration/60) + ':';
                duration = duration%60;
            }
            audioprocess += (Math.floor(duration) >= 10 ? '':'0') + Math.floor(duration);
            return audioprocess;
        };

        var loadWavesurfer = function(index){
            try {
                if(_this.core.s.audios[String(index)] == undefined){
                    var _uid = $(_this.core.___slide[index]).attr('lg-event-uid');
                    var wavesurfer = WaveSurfer.create({
                        barWidth: 3,
                        height: 100,
                        hideScrollbar: true,
                        scrollParent: true,
                        container: '.lg-item[lg-event-uid="'+_uid+'"] #waveform',
                        cursorColor: '#4d4d4d',
                        waveColor: '#808080',
                        progressColor: '#4285f4'
                    });
                    wavesurfer.load(_this.core.items[index].getAttribute('data-src'));
                    _this.core.s.audios[String(index)] = wavesurfer;
                    wavesurfer.on('ready', function(){
                        var _uid = $(_this.core.___slide[index]).attr('lg-event-uid');
                        $('.lg-item[lg-event-uid="'+ _uid +'"]').children('.lg-img-wrap').children('.lg-video-cont').children('.lg-video').children('.lg-object').children('.lg-audio-container').children('.lg-audio-body').children('.lg-audio-toolbar').children('#btn-group').addClass( 'open' );

                        $('.lg-item[lg-event-uid="'+ _uid +'"]').children('.lg-img-wrap').children('.lg-video-cont').children('.lg-video').children('.lg-object').children('.lg-audio-container').children('.lg-audio-body').children('.lg-audio-toolbar').children('#btn-group').children('.btn-audio-play-pause').on('click', function(){
                            _this.core.s.audios[String(index)].playPause();
                            $(this).toggleClass('playing');
                        });
                        $('.lg-item[lg-event-uid="'+ _uid +'"]').children('.lg-img-wrap').children('.lg-video-cont').children('.lg-video').children('.lg-object').children('.lg-audio-container').children('.lg-audio-body').children('.lg-audio-toolbar').children('#btn-group').children('.btn-audio-toggle-mute').on('click', function(){
                            _this.core.s.audios[String(index)].toggleMute();
                            $(this).toggleClass('off');
                        });

                        $('.lg-item[lg-event-uid="'+ _uid +'"]').children('.lg-img-wrap').children('.lg-video-cont').children('.lg-video').children('.lg-object').children('.lg-audio-container').children('.lg-audio-body').children('.lg-audio-toolbar').children('#lg-audio-process').html( audioprocess_format(wavesurfer.getCurrentTime(), wavesurfer.getDuration()) );
                    });
                    wavesurfer.on('audioprocess', function () {
                        var _uid = $(_this.core.___slide[index]).attr('lg-event-uid');
                        $('.lg-item[lg-event-uid="'+ _uid +'"]').children('.lg-img-wrap').children('.lg-video-cont').children('.lg-video').children('.lg-object').children('.lg-audio-container').children('.lg-audio-body').children('.lg-audio-toolbar').children('#lg-audio-process').html( audioprocess_format(wavesurfer.getCurrentTime(), wavesurfer.getDuration()) );
                    });
                    wavesurfer.on('finish', function () {
                        var _uid = $(_this.core.___slide[index]).attr('lg-event-uid');
                        $('.lg-item[lg-event-uid="'+ _uid +'"]').children('.lg-img-wrap').children('.lg-video-cont').children('.lg-video').children('.lg-object').children('.lg-audio-container').children('.lg-audio-body').children('.lg-audio-toolbar').children('#btn-group').children('.btn-audio-play-pause').removeClass('playing');
                    });
                }
            } catch (e) {
                // console.log("wavesurfer can't play. try again ...");
                setTimeout(function(){ loadWavesurfer(index); }, 100);
            }
        };

        utils.on(_this.core.el, 'onAfterSlide.lgtm', function (event) {
            var _audio = _this.core.items[event.detail.index].getAttribute('data-cus-type');
            if(_audio && _audio === "audio"){
                loadWavesurfer(event.detail.index);
            }
        });

        utils.on(_this.core.el, 'onBeforeSlide.lgtm', function (event) {
            if(_this.core.s.audios[String(event.detail.prevIndex)] != undefined && _this.core.s.audios[String(event.detail.prevIndex)].isPlaying()){
                _this.core.s.audios[String(event.detail.prevIndex)].pause();
                var _uid = $(_this.core.___slide[event.detail.prevIndex]).attr('lg-event-uid');
                $('.lg-item[lg-event-uid="'+ _uid +'"]').children('.lg-img-wrap').children('.lg-video-cont').children('.lg-video').children('.lg-object').children('.lg-audio-container').children('.lg-audio-body').children('.lg-audio-toolbar').children('#btn-group').children('.btn-audio-play-pause').removeClass('playing');
            }
        });
    };

    Audio.prototype.destroy = function () {
        var _this = this;
        $.each(_this.core.s.audios, function(idx, val){
            if(val !== undefined) val.destroy();
        });
        _this.core.s.audios = [];
    };

    window.lgModules.audio = Audio;
});

},{}]},{},[1])(1)
});
