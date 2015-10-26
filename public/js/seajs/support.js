
/*  
Append File:/as/js-2.0.2/support.js
*/
/*! Autoshow - V(2.0.2) - build(2014.01.09) */
define("support",["jquery"],function(t,e,n){"use strict";var o=t("jquery");o.extend(o.support,{fixed:function(t){var e=t('<div style="position:absolute;top:200px"></div>'),n=t('<div style="position:fixed;top:100px;"></div>'),o=!0;return n.appendTo(e),e.appendTo(document.body),n[0].getBoundingClientRect&&n[0].getBoundingClientRect().top==e[0].getBoundingClientRect().top&&(o=!1),e.remove(),o}(o)}),n.exports=o.support});

/*
Execute Time:0ms
Create Time:2014/12/19 9:33:04 from 56
*/
