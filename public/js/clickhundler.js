/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**************************************!*\
  !*** ./resources/js/clickhundler.js ***!
  \**************************************/
// import Vue from 'vue';
clickhundler = {
  copytext: function copytext() {
    $copytext = document.getElementsByClassName('copytext');
    $copytext[0].select();
    document.execCommand("copy");
  }
};
/******/ })()
;