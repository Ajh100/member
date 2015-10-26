/*  
Append File:/as/js-2.0.2/gotop.js
*/
/*! Autoshow - V(2.0.2) - build(2014.01.09) */
define("gotop",["jquery","support"],function(t,e,n){"use strict";var o=t("jquery"),i=t("support"),r=function(t,e){var n=this;n.options=e,n.$elem=o(t),n.$scrollElem=o('[data-scroll="true"]',n.$elem),n.init()};r.prototype={constructor:r,init:function(){var t=this;o('a[data-gotop="true"]',t.$elem).on("click",function(){return t.setScrollTop(),!1}),t.$elem.css({bottom:t.options.bottom}),o(window).on("resize.gotop scroll.gotop",o.proxy(t.setPos,t)),t.setPos()},setScrollTop:function(){o("html,body").animate({scrollTop:0},i.fixed?300:0)},show:function(){this.$elem.trigger(o.Event("show")),this.$scrollElem.show()},hide:function(){this.$elem.trigger(o.Event("hide")),this.$scrollElem.hide()},setPos:function(){var t=this,e=o(window);t.timer&&clearTimeout(t.timer),t.timer=setTimeout(function(){var n=e.scrollTop(),o=e.width();t[n>0?"show":"hide"](),i.fixed?t.$elem.css({bottom:t.options.bottom}):t.$elem.css({bottom:"",top:n+e.height()-t.$elem.outerHeight()-t.options.bottom}),t.$elem[o>1024?"removeClass":"addClass"]("gotop-opacity")},10)}},o.fn.gotop=function(t){return this.each(function(){var e=o(this),n=e.data("gotop"),i=o.extend({},o.fn.gotop.defaults,e.data(),"object"==typeof t&&t);n||e.data("gotop",n=new r(this,i)),"string"==typeof t&&n[t]()})},o.fn.gotop.defaults={bottom:10},o.fn.gotop.Constructor=r,o(function(){o('[data-toggle="gotop"]').gotop()}),n.exports=o});
