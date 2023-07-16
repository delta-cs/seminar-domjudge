/**
 * Skipped minification because the original files appears to be already minified.
 * Original file: /npm/@editorjs/table@2.2.2/dist/table.js
 *
 * Do NOT use SRI with dynamically generated files! More information: https://www.jsdelivr.com/using-sri-with-dynamic-files
 */
!function(t,e){"object"==typeof exports&&"object"==typeof module?module.exports=e():"function"==typeof define&&define.amd?define([],e):"object"==typeof exports?exports.Table=e():t.Table=e()}(window,(function(){return function(t){var e={};function o(r){if(e[r])return e[r].exports;var i=e[r]={i:r,l:!1,exports:{}};return t[r].call(i.exports,i,i.exports,o),i.l=!0,i.exports}return o.m=t,o.c=e,o.d=function(t,e,r){o.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:r})},o.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},o.t=function(t,e){if(1&e&&(t=o(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var r=Object.create(null);if(o.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var i in t)o.d(r,i,function(e){return t[e]}.bind(null,i));return r},o.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return o.d(e,"a",e),e},o.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},o.p="/",o(o.s=6)}([function(t,e){t.exports='<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"><rect width="18" height="18" fill="#F4F5F7" rx="2"></rect><circle cx="11.5" cy="6.5" r="1.5"></circle><circle cx="11.5" cy="11.5" r="1.5"></circle><circle cx="6.5" cy="6.5" r="1.5"></circle><circle cx="6.5" cy="11.5" r="1.5"></circle></svg>'},function(t,e,o){var r=o(2);"string"==typeof r&&(r=[[t.i,r,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};o(4)(r,i);r.locals&&(t.exports=r.locals)},function(t,e,o){(t.exports=o(3)(!1)).push([t.i,'.tc-wrap{--color-background:#f9f9fb;--color-text-secondary:#7b7e89;--color-border:#e8e8eb;--cell-size:34px;--toolbox-icon-size:18px;--toolbox-padding:6px;--toolbox-aiming-field-size:calc(var(--toolbox-icon-size) + var(--toolbox-padding)*2);border-left:0;position:relative;height:100%;width:100%;margin-top:var(--toolbox-icon-size);box-sizing:border-box;display:grid;grid-template-columns:calc(100% - var(--cell-size)) var(--cell-size);}.tc-wrap--readonly{grid-template-columns:100% var(--cell-size)}.tc-wrap svg{vertical-align:top}@media print{.tc-wrap{border-left-color:var(--color-border);border-left-style:solid;border-left-width:1px;grid-template-columns:100% var(--cell-size)}}@media print{.tc-wrap .tc-row:after{display:none}}.tc-table{position:relative;width:100%;height:100%;display:grid;font-size:14px;border-top:1px solid var(--color-border);line-height:1.4;}.tc-table:after{width:calc(var(--cell-size));height:100%;left:calc(var(--cell-size)*-1);top:0}.tc-table:after,.tc-table:before{position:absolute;content:""}.tc-table:before{width:100%;height:var(--toolbox-aiming-field-size);top:calc(var(--toolbox-aiming-field-size)*-1);left:0}.tc-table--heading .tc-row:first-child{font-weight:600;border-bottom:2px solid var(--color-border);}.tc-table--heading .tc-row:first-child [contenteditable]:empty:before{content:attr(heading);color:var(--color-text-secondary)}.tc-table--heading .tc-row:first-child:after{bottom:-2px;border-bottom:2px solid var(--color-border)}.tc-add-column,.tc-add-row{display:flex;color:var(--color-text-secondary)}@media print{.tc-add{display:none}}.tc-add-column{padding:4px 0;justify-content:center;border-top:1px solid var(--color-border);}@media print{.tc-add-column{display:none}}.tc-add-row{height:var(--cell-size);align-items:center;padding-left:4px;position:relative;}.tc-add-row:before{content:"";position:absolute;right:calc(var(--cell-size)*-1);width:var(--cell-size);height:100%}@media print{.tc-add-row{display:none}}.tc-add-column,.tc-add-row{transition:0s;cursor:pointer;will-change:background-color;}.tc-add-column:hover,.tc-add-row:hover{transition:background-color .1s ease;background-color:var(--color-background)}.tc-add-row{margin-top:1px;}.tc-add-row:hover:before{transition:.1s;background-color:var(--color-background)}.tc-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(10px,1fr));position:relative;border-bottom:1px solid var(--color-border);}.tc-row:after{content:"";pointer-events:none;position:absolute;width:var(--cell-size);height:100%;bottom:-1px;right:calc(var(--cell-size)*-1);border-bottom:1px solid var(--color-border)}.tc-row--selected{background:var(--color-background)}.tc-row--selected:after{background:var(--color-background)}.tc-cell{border-right:1px solid var(--color-border);padding:6px 12px;overflow:hidden;outline:none;line-break:normal;}.tc-cell--selected{background:var(--color-background)}.tc-wrap--readonly .tc-row:after{display:none}.tc-toolbox{--toolbox-padding:6px;--popover-margin:30px;--toggler-click-zone-size:30px;--toggler-dots-color:#7b7e89;--toggler-dots-color-hovered:#1d202b;position:absolute;cursor:pointer;z-index:1;opacity:0;transition:opacity .1s;will-change:left,opacity;}.tc-toolbox--column{top:calc(var(--toggler-click-zone-size)*-1);transform:translateX(calc(var(--toggler-click-zone-size)*-1/2));will-change:left,opacity}.tc-toolbox--row{left:calc(var(--popover-margin)*-1);transform:translateY(calc(var(--toggler-click-zone-size)*-1/2));margin-top:-1px;will-change:top,opacity}.tc-toolbox--showed{opacity:1}.tc-toolbox .tc-popover{position:absolute;top:0;left:var(--popover-margin)}.tc-toolbox__toggler{display:flex;align-items:center;justify-content:center;width:var(--toggler-click-zone-size);height:var(--toggler-click-zone-size);color:var(--toggler-dots-color);opacity:0;transition:opacity .15s ease;will-change:opacity;}.tc-toolbox__toggler:hover{color:var(--toggler-dots-color-hovered)}.tc-toolbox__toggler svg{fill:currentColor}.tc-wrap:hover .tc-toolbox__toggler{opacity:1}.tc-settings .cdx-settings-button{width:50%;margin:0}.tc-popover{--color-border:#eaeaea;--color-background:#fff;--color-background-hover:rgba(232,232,235,0.49);--color-background-confirm:#e24a4a;--color-background-confirm-hover:#d54040;--color-text-confirm:#fff;background:var(--color-background);border:1px solid var(--color-border);box-shadow:0 3px 15px -3px rgba(13,20,33,.13);border-radius:6px;padding:6px;display:none;will-change:opacity,transform;}.tc-popover--opened{display:block;animation:menuShowing .1s cubic-bezier(.215,.61,.355,1) forwards}.tc-popover__item{display:flex;align-items:center;padding:2px 14px 2px 2px;border-radius:5px;cursor:pointer;white-space:nowrap;-webkit-user-select:none;-moz-user-select:none;user-select:none;}.tc-popover__item:hover{background:var(--color-background-hover)}.tc-popover__item:not(:last-of-type){margin-bottom:2px}.tc-popover__item-icon{display:inline-flex;width:26px;height:26px;align-items:center;justify-content:center;background:var(--color-background);border-radius:5px;border:1px solid var(--color-border);margin-right:8px}.tc-popover__item-label{line-height:22px;font-size:14px;font-weight:500}.tc-popover__item--confirm{background:var(--color-background-confirm);color:var(--color-text-confirm);}.tc-popover__item--confirm:hover{background-color:var(--color-background-confirm-hover)}.tc-popover__item--confirm .tc-popover__item-icon{background:var(--color-background-confirm);border-color:rgba(0,0,0,.1);}.tc-popover__item--confirm .tc-popover__item-icon svg{transition:transform .2s ease-in;transform:rotate(90deg) scale(1.2)}.tc-popover__item--hidden{display:none}@keyframes menuShowing{0%{opacity:0;transform:translateY(-8px) scale(.9)}70%{opacity:1;transform:translateY(2px)}to{transform:translateY(0)}}',""])},function(t,e){t.exports=function(t){var e=[];return e.toString=function(){return this.map((function(e){var o=function(t,e){var o=t[1]||"",r=t[3];if(!r)return o;if(e&&"function"==typeof btoa){var i=(s=r,"/*# sourceMappingURL=data:application/json;charset=utf-8;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(s))))+" */"),n=r.sources.map((function(t){return"/*# sourceURL="+r.sourceRoot+t+" */"}));return[o].concat(n).concat([i]).join("\n")}var s;return[o].join("\n")}(e,t);return e[2]?"@media "+e[2]+"{"+o+"}":o})).join("")},e.i=function(t,o){"string"==typeof t&&(t=[[null,t,""]]);for(var r={},i=0;i<this.length;i++){var n=this[i][0];"number"==typeof n&&(r[n]=!0)}for(i=0;i<t.length;i++){var s=t[i];"number"==typeof s[0]&&r[s[0]]||(o&&!s[2]?s[2]=o:o&&(s[2]="("+s[2]+") and ("+o+")"),e.push(s))}},e}},function(t,e,o){var r,i,n={},s=(r=function(){return window&&document&&document.all&&!window.atob},function(){return void 0===i&&(i=r.apply(this,arguments)),i}),l=function(t,e){return e?e.querySelector(t):document.querySelector(t)},a=function(t){var e={};return function(t,o){if("function"==typeof t)return t();if(void 0===e[t]){var r=l.call(this,t,o);if(window.HTMLIFrameElement&&r instanceof window.HTMLIFrameElement)try{r=r.contentDocument.head}catch(t){r=null}e[t]=r}return e[t]}}(),c=null,d=0,h=[],p=o(5);function u(t,e){for(var o=0;o<t.length;o++){var r=t[o],i=n[r.id];if(i){i.refs++;for(var s=0;s<i.parts.length;s++)i.parts[s](r.parts[s]);for(;s<r.parts.length;s++)i.parts.push(v(r.parts[s],e))}else{var l=[];for(s=0;s<r.parts.length;s++)l.push(v(r.parts[s],e));n[r.id]={id:r.id,refs:1,parts:l}}}}function f(t,e){for(var o=[],r={},i=0;i<t.length;i++){var n=t[i],s=e.base?n[0]+e.base:n[0],l={css:n[1],media:n[2],sourceMap:n[3]};r[s]?r[s].parts.push(l):o.push(r[s]={id:s,parts:[l]})}return o}function g(t,e){var o=a(t.insertInto);if(!o)throw new Error("Couldn't find a style target. This probably means that the value for the 'insertInto' parameter is invalid.");var r=h[h.length-1];if("top"===t.insertAt)r?r.nextSibling?o.insertBefore(e,r.nextSibling):o.appendChild(e):o.insertBefore(e,o.firstChild),h.push(e);else if("bottom"===t.insertAt)o.appendChild(e);else{if("object"!=typeof t.insertAt||!t.insertAt.before)throw new Error("[Style Loader]\n\n Invalid value for parameter 'insertAt' ('options.insertAt') found.\n Must be 'top', 'bottom', or Object.\n (https://github.com/webpack-contrib/style-loader#insertat)\n");var i=a(t.insertAt.before,o);o.insertBefore(e,i)}}function m(t){if(null===t.parentNode)return!1;t.parentNode.removeChild(t);var e=h.indexOf(t);e>=0&&h.splice(e,1)}function w(t){var e=document.createElement("style");if(void 0===t.attrs.type&&(t.attrs.type="text/css"),void 0===t.attrs.nonce){var r=function(){0;return o.nc}();r&&(t.attrs.nonce=r)}return b(e,t.attrs),g(t,e),e}function b(t,e){Object.keys(e).forEach((function(o){t.setAttribute(o,e[o])}))}function v(t,e){var o,r,i,n;if(e.transform&&t.css){if(!(n="function"==typeof e.transform?e.transform(t.css):e.transform.default(t.css)))return function(){};t.css=n}if(e.singleton){var s=d++;o=c||(c=w(e)),r=y.bind(null,o,s,!1),i=y.bind(null,o,s,!0)}else t.sourceMap&&"function"==typeof URL&&"function"==typeof URL.createObjectURL&&"function"==typeof URL.revokeObjectURL&&"function"==typeof Blob&&"function"==typeof btoa?(o=function(t){var e=document.createElement("link");return void 0===t.attrs.type&&(t.attrs.type="text/css"),t.attrs.rel="stylesheet",b(e,t.attrs),g(t,e),e}(e),r=R.bind(null,o,e),i=function(){m(o),o.href&&URL.revokeObjectURL(o.href)}):(o=w(e),r=k.bind(null,o),i=function(){m(o)});return r(t),function(e){if(e){if(e.css===t.css&&e.media===t.media&&e.sourceMap===t.sourceMap)return;r(t=e)}else i()}}t.exports=function(t,e){if("undefined"!=typeof DEBUG&&DEBUG&&"object"!=typeof document)throw new Error("The style-loader cannot be used in a non-browser environment");(e=e||{}).attrs="object"==typeof e.attrs?e.attrs:{},e.singleton||"boolean"==typeof e.singleton||(e.singleton=s()),e.insertInto||(e.insertInto="head"),e.insertAt||(e.insertAt="bottom");var o=f(t,e);return u(o,e),function(t){for(var r=[],i=0;i<o.length;i++){var s=o[i];(l=n[s.id]).refs--,r.push(l)}t&&u(f(t,e),e);for(i=0;i<r.length;i++){var l;if(0===(l=r[i]).refs){for(var a=0;a<l.parts.length;a++)l.parts[a]();delete n[l.id]}}}};var x,C=(x=[],function(t,e){return x[t]=e,x.filter(Boolean).join("\n")});function y(t,e,o,r){var i=o?"":r.css;if(t.styleSheet)t.styleSheet.cssText=C(e,i);else{var n=document.createTextNode(i),s=t.childNodes;s[e]&&t.removeChild(s[e]),s.length?t.insertBefore(n,s[e]):t.appendChild(n)}}function k(t,e){var o=e.css,r=e.media;if(r&&t.setAttribute("media",r),t.styleSheet)t.styleSheet.cssText=o;else{for(;t.firstChild;)t.removeChild(t.firstChild);t.appendChild(document.createTextNode(o))}}function R(t,e,o){var r=o.css,i=o.sourceMap,n=void 0===e.convertToAbsoluteUrls&&i;(e.convertToAbsoluteUrls||n)&&(r=p(r)),i&&(r+="\n/*# sourceMappingURL=data:application/json;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(i))))+" */");var s=new Blob([r],{type:"text/css"}),l=t.href;t.href=URL.createObjectURL(s),l&&URL.revokeObjectURL(l)}},function(t,e){t.exports=function(t){var e="undefined"!=typeof window&&window.location;if(!e)throw new Error("fixUrls requires window.location");if(!t||"string"!=typeof t)return t;var o=e.protocol+"//"+e.host,r=o+e.pathname.replace(/\/[^\/]*$/,"/");return t.replace(/url\s*\(((?:[^)(]|\((?:[^)(]+|\([^)(]*\))*\))*)\)/gi,(function(t,e){var i,n=e.trim().replace(/^"(.*)"$/,(function(t,e){return e})).replace(/^'(.*)'$/,(function(t,e){return e}));return/^(#|data:|http:\/\/|https:\/\/|file:\/\/\/|\s*$)/i.test(n)?t:(i=0===n.indexOf("//")?n:0===n.indexOf("/")?o+n:r+n.replace(/^\.\//,""),"url("+JSON.stringify(i)+")")}))}},function(t,e,o){"use strict";function r(t,e,o={}){const r=document.createElement(t);Array.isArray(e)?r.classList.add(...e):e&&r.classList.add(e);for(const t in o)Object.prototype.hasOwnProperty.call(o,t)&&(r[t]=o[t]);return r}function i(t){const e=t.getBoundingClientRect();return{y1:Math.floor(e.top+window.pageYOffset),x1:Math.floor(e.left+window.pageXOffset),x2:Math.floor(e.right+window.pageXOffset),y2:Math.floor(e.bottom+window.pageYOffset)}}function n(t,e){const o=i(t),r=i(e);return{fromTopBorder:r.y1-o.y1,fromLeftBorder:r.x1-o.x1,fromRightBorder:o.x2-r.x2,fromBottomBorder:o.y2-r.y2}}function s(t,e){return e.parentNode.insertBefore(t,e)}function l(t,e=!0){const o=document.createRange(),r=window.getSelection();o.selectNodeContents(t),o.collapse(e),r.removeAllRanges(),r.addRange(o)}o.r(e);class a{constructor({items:t}){this.items=t,this.wrapper=void 0,this.itemEls=[]}static get CSS(){return{popover:"tc-popover",popoverOpened:"tc-popover--opened",item:"tc-popover__item",itemHidden:"tc-popover__item--hidden",itemConfirmState:"tc-popover__item--confirm",itemIcon:"tc-popover__item-icon",itemLabel:"tc-popover__item-label"}}render(){return this.wrapper=r("div",a.CSS.popover),this.items.forEach((t,e)=>{const o=r("div",a.CSS.item),i=r("div",a.CSS.itemIcon,{innerHTML:t.icon}),n=r("div",a.CSS.itemLabel,{textContent:t.label});o.dataset.index=e,o.appendChild(i),o.appendChild(n),this.wrapper.appendChild(o),this.itemEls.push(o)}),this.wrapper.addEventListener("click",t=>{this.popoverClicked(t)}),this.wrapper}popoverClicked(t){const e=t.target.closest("."+a.CSS.item);if(!e)return;const o=e.dataset.index,r=this.items[o];!r.confirmationRequired||this.hasConfirmationState(e)?r.onClick():this.setConfirmationState(e)}setConfirmationState(t){t.classList.add(a.CSS.itemConfirmState)}clearConfirmationState(t){t.classList.remove(a.CSS.itemConfirmState)}hasConfirmationState(t){return t.classList.contains(a.CSS.itemConfirmState)}get opened(){return this.wrapper.classList.contains(a.CSS.popoverOpened)}open(){this.items.forEach((t,e)=>{"function"==typeof t.hideIf&&this.itemEls[e].classList.toggle(a.CSS.itemHidden,t.hideIf())}),this.wrapper.classList.add(a.CSS.popoverOpened)}close(){this.wrapper.classList.remove(a.CSS.popoverOpened),this.itemEls.forEach(t=>{this.clearConfirmationState(t)})}}var c=o(0),d=o.n(c);class h{constructor({api:t,items:e,onOpen:o,onClose:r,cssModifier:i=""}){this.api=t,this.items=e,this.onOpen=o,this.onClose=r,this.cssModifier=i,this.popover=null,this.wrapper=this.createToolbox()}static get CSS(){return{toolbox:"tc-toolbox",toolboxShowed:"tc-toolbox--showed",toggler:"tc-toolbox__toggler"}}get element(){return this.wrapper}createToolbox(){const t=r("div",[h.CSS.toolbox,this.cssModifier?`${h.CSS.toolbox}--${this.cssModifier}`:""]);t.dataset.mutationFree="true";const e=this.createPopover(),o=this.createToggler();return t.appendChild(o),t.appendChild(e),t}createToggler(){const t=r("div",h.CSS.toggler,{innerHTML:d.a});return t.addEventListener("click",()=>{this.togglerClicked()}),t}createPopover(){return this.popover=new a({items:this.items}),this.popover.render()}togglerClicked(){this.popover.opened?(this.popover.close(),this.onClose()):(this.popover.open(),this.onOpen())}show(t){const e=t();Object.entries(e).forEach(([t,e])=>{this.wrapper.style[t]=e}),this.wrapper.classList.add(h.CSS.toolboxShowed)}hide(){this.popover.close(),this.wrapper.classList.remove(h.CSS.toolboxShowed)}}const p='<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M8 8L12 12M12 12L16 16M12 12L16 8M12 12L8 16"/></svg>',u='<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M12 7V12M12 17V12M17 12H12M12 12H7"/></svg>',f="tc-wrap",g="tc-wrap--readonly",m="tc-table",w="tc-row",b="tc-table--heading",v="tc-row--selected",x="tc-cell",C="tc-cell--selected",y="tc-add-row",k="tc-add-column";class R{constructor(t,e,o,r){this.readOnly=t,this.api=e,this.data=o,this.config=r,this.wrapper=null,this.table=null,this.toolboxColumn=this.createColumnToolbox(),this.toolboxRow=this.createRowToolbox(),this.createTableWrapper(),this.hoveredRow=0,this.hoveredColumn=0,this.selectedRow=0,this.selectedColumn=0,this.tunes={withHeadings:!1},this.resize(),this.fill(),this.focusedCell={row:0,column:0},this.documentClicked=t=>{const e=null!==t.target.closest("."+m),o=null===t.target.closest("."+f);(e||o)&&this.hideToolboxes();const r=t.target.closest("."+y),i=t.target.closest("."+k);r&&r.parentNode===this.wrapper?(this.addRow(void 0,!0),this.hideToolboxes()):i&&i.parentNode===this.wrapper&&(this.addColumn(void 0,!0),this.hideToolboxes())},this.readOnly||this.bindEvents()}getWrapper(){return this.wrapper}bindEvents(){document.addEventListener("click",this.documentClicked),this.table.addEventListener("mousemove",function(t,e){let o=0;return function(...r){const i=(new Date).getTime();if(!(i-o<t))return o=i,e(...r)}}(150,t=>this.onMouseMoveInTable(t)),{passive:!0}),this.table.onkeypress=t=>this.onKeyPressListener(t),this.table.addEventListener("keydown",t=>this.onKeyDownListener(t)),this.table.addEventListener("focusin",t=>this.focusInTableListener(t))}createColumnToolbox(){return new h({api:this.api,cssModifier:"column",items:[{label:this.api.i18n.t("Add column to left"),icon:'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.9167 14.9167L11.5833 18.25M11.5833 18.25L8.25 14.9167M11.5833 18.25L11.5833 10.0833C11.5833 9.19928 11.9345 8.35143 12.5596 7.72631C13.1848 7.10119 14.0326 6.75 14.9167 6.75"/></svg>',onClick:()=>{this.addColumn(this.selectedColumn,!0),this.hideToolboxes()}},{label:this.api.i18n.t("Add column to right"),icon:'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.13333 14.9167L12.4667 18.25M12.4667 18.25L15.8 14.9167M12.4667 18.25L12.4667 10.0833C12.4667 9.19928 12.1155 8.35143 11.4904 7.72631C10.8652 7.10119 10.0174 6.75 9.13333 6.75"/></svg>',onClick:()=>{this.addColumn(this.selectedColumn+1,!0),this.hideToolboxes()}},{label:this.api.i18n.t("Delete column"),icon:p,hideIf:()=>1===this.numberOfColumns,confirmationRequired:!0,onClick:()=>{this.deleteColumn(this.selectedColumn),this.hideToolboxes()}}],onOpen:()=>{this.selectColumn(this.hoveredColumn),this.hideRowToolbox()},onClose:()=>{this.unselectColumn()}})}createRowToolbox(){return new h({api:this.api,cssModifier:"row",items:[{label:this.api.i18n.t("Add row above"),icon:'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.8833 15.8333L18.2167 12.5M18.2167 12.5L14.8833 9.16667M18.2167 12.5L10.05 12.5C9.16595 12.5 8.31811 12.8512 7.69299 13.4763C7.06787 14.1014 6.71667 14.9493 6.71667 15.8333"/></svg>',onClick:()=>{this.addRow(this.selectedRow,!0),this.hideToolboxes()}},{label:this.api.i18n.t("Add row below"),icon:'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.8833 9.16666L18.2167 12.5M18.2167 12.5L14.8833 15.8333M18.2167 12.5H10.05C9.16594 12.5 8.31809 12.1488 7.69297 11.5237C7.06785 10.8986 6.71666 10.0507 6.71666 9.16666"/></svg>',onClick:()=>{this.addRow(this.selectedRow+1,!0),this.hideToolboxes()}},{label:this.api.i18n.t("Delete row"),icon:p,hideIf:()=>1===this.numberOfRows,confirmationRequired:!0,onClick:()=>{this.deleteRow(this.selectedRow),this.hideToolboxes()}}],onOpen:()=>{this.selectRow(this.hoveredRow),this.hideColumnToolbox()},onClose:()=>{this.unselectRow()}})}moveCursorToNextRow(){this.focusedCell.row!==this.numberOfRows?(this.focusedCell.row+=1,this.focusCell(this.focusedCell)):(this.addRow(),this.focusedCell.row+=1,this.focusCell(this.focusedCell),this.updateToolboxesPosition(0,0))}getCell(t,e){return this.table.querySelectorAll(`.${w}:nth-child(${t}) .${x}`)[e-1]}getRow(t){return this.table.querySelector(`.${w}:nth-child(${t})`)}getRowByCell(t){return t.parentElement}getRowFirstCell(t){return t.querySelector(`.${x}:first-child`)}setCellContent(t,e,o){this.getCell(t,e).innerHTML=o}addColumn(t=-1,e=!1){let o=this.numberOfColumns;for(let r=1;r<=this.numberOfRows;r++){let i;const n=this.createCell();if(t>0&&t<=o?(i=this.getCell(r,t),s(n,i)):i=this.getRow(r).appendChild(n),1===r){const i=this.getCell(r,t>0?t:o+1);i&&e&&l(i)}}this.addHeadingAttrToFirstRow()}addRow(t=-1,e=!1){let o,i=r("div",w);this.tunes.withHeadings&&this.removeHeadingAttrFromFirstRow();let n=this.numberOfColumns;if(t>0&&t<=this.numberOfRows){o=s(i,this.getRow(t))}else o=this.table.appendChild(i);this.fillRow(o,n),this.tunes.withHeadings&&this.addHeadingAttrToFirstRow();const a=this.getRowFirstCell(o);return a&&e&&l(a),o}deleteColumn(t){for(let e=1;e<=this.numberOfRows;e++){const o=this.getCell(e,t);if(!o)return;o.remove()}}deleteRow(t){this.getRow(t).remove(),this.addHeadingAttrToFirstRow()}createTableWrapper(){if(this.wrapper=r("div",f),this.table=r("div",m),this.readOnly&&this.wrapper.classList.add(g),this.wrapper.appendChild(this.toolboxRow.element),this.wrapper.appendChild(this.toolboxColumn.element),this.wrapper.appendChild(this.table),!this.readOnly){const t=r("div",k,{innerHTML:u}),e=r("div",y,{innerHTML:u});this.wrapper.appendChild(t),this.wrapper.appendChild(e)}}computeInitialSize(){const t=this.data&&this.data.content,e=Array.isArray(t),o=!!e&&t.length,r=e?t.length:void 0,i=o?t[0].length:void 0,n=Number.parseInt(this.config&&this.config.rows),s=Number.parseInt(this.config&&this.config.cols),l=!isNaN(n)&&n>0?n:void 0,a=!isNaN(s)&&s>0?s:void 0;return{rows:r||l||2,cols:i||a||2}}resize(){const{rows:t,cols:e}=this.computeInitialSize();for(let e=0;e<t;e++)this.addRow();for(let t=0;t<e;t++)this.addColumn()}fill(){const t=this.data;if(t&&t.content)for(let e=0;e<t.content.length;e++)for(let o=0;o<t.content[e].length;o++)this.setCellContent(e+1,o+1,t.content[e][o])}fillRow(t,e){for(let o=1;o<=e;o++){const e=this.createCell();t.appendChild(e)}}createCell(){return r("div",x,{contentEditable:!this.readOnly})}get numberOfRows(){return this.table.childElementCount}get numberOfColumns(){return this.numberOfRows?this.table.querySelectorAll(`.${w}:first-child .${x}`).length:0}get isColumnMenuShowing(){return 0!==this.selectedColumn}get isRowMenuShowing(){return 0!==this.selectedRow}onMouseMoveInTable(t){const{row:e,column:o}=this.getHoveredCell(t);this.hoveredColumn=o,this.hoveredRow=e,this.updateToolboxesPosition()}onKeyPressListener(t){if("Enter"===t.key){if(t.shiftKey)return!0;this.moveCursorToNextRow()}return"Enter"!==t.key}onKeyDownListener(t){"Tab"===t.key&&t.stopPropagation()}focusInTableListener(t){const e=t.target,o=this.getRowByCell(e);this.focusedCell={row:Array.from(this.table.querySelectorAll("."+w)).indexOf(o)+1,column:Array.from(o.querySelectorAll("."+x)).indexOf(e)+1}}hideToolboxes(){this.hideRowToolbox(),this.hideColumnToolbox(),this.updateToolboxesPosition()}hideRowToolbox(){this.unselectRow(),this.toolboxRow.hide()}hideColumnToolbox(){this.unselectColumn(),this.toolboxColumn.hide()}focusCell(){this.focusedCellElem.focus()}get focusedCellElem(){const{row:t,column:e}=this.focusedCell;return this.getCell(t,e)}updateToolboxesPosition(t=this.hoveredRow,e=this.hoveredColumn){this.isColumnMenuShowing||e>0&&e<=this.numberOfColumns&&this.toolboxColumn.show(()=>({left:`calc((100% - var(--cell-size)) / (${this.numberOfColumns} * 2) * (1 + (${e} - 1) * 2))`})),this.isRowMenuShowing||t>0&&t<=this.numberOfRows&&this.toolboxRow.show(()=>{const e=this.getRow(t),{fromTopBorder:o}=n(this.table,e),{height:r}=e.getBoundingClientRect();return{top:Math.ceil(o+r/2)+"px"}})}setHeadingsSetting(t){this.tunes.withHeadings=t,t?(this.table.classList.add(b),this.addHeadingAttrToFirstRow()):(this.table.classList.remove(b),this.removeHeadingAttrFromFirstRow())}addHeadingAttrToFirstRow(){for(let t=1;t<=this.numberOfColumns;t++){let e=this.getCell(1,t);e&&e.setAttribute("heading",this.api.i18n.t("Heading"))}}removeHeadingAttrFromFirstRow(){for(let t=1;t<=this.numberOfColumns;t++){let e=this.getCell(1,t);e&&e.removeAttribute("heading")}}selectRow(t){const e=this.getRow(t);e&&(this.selectedRow=t,e.classList.add(v))}unselectRow(){if(this.selectedRow<=0)return;const t=this.table.querySelector("."+v);t&&t.classList.remove(v),this.selectedRow=0}selectColumn(t){for(let e=1;e<=this.numberOfRows;e++){const o=this.getCell(e,t);o&&o.classList.add(C)}this.selectedColumn=t}unselectColumn(){if(this.selectedColumn<=0)return;let t=this.table.querySelectorAll("."+C);Array.from(t).forEach(t=>{t.classList.remove(C)}),this.selectedColumn=0}getHoveredCell(t){let e=this.hoveredRow,o=this.hoveredColumn;const{width:r,height:i,x:n,y:s}=function(t,e){const o=t.getBoundingClientRect(),{width:r,height:i,x:n,y:s}=o,{clientX:l,clientY:a}=e;return{width:r,height:i,x:l-n,y:a-s}}(this.table,t);return n>=0&&(o=this.binSearch(this.numberOfColumns,t=>this.getCell(1,t),({fromLeftBorder:t})=>n<t,({fromRightBorder:t})=>n>r-t)),s>=0&&(e=this.binSearch(this.numberOfRows,t=>this.getCell(t,1),({fromTopBorder:t})=>s<t,({fromBottomBorder:t})=>s>i-t)),{row:e||this.hoveredRow,column:o||this.hoveredColumn}}binSearch(t,e,o,r){let i,s=0,l=t+1,a=0;for(;s<l-1&&a<10;){i=Math.ceil((s+l)/2);const t=e(i),c=n(this.table,t);if(o(c))l=i;else{if(!r(c))break;s=i}a++}return i}getData(){const t=[];for(let e=1;e<=this.numberOfRows;e++){const o=this.table.querySelector(`.${w}:nth-child(${e})`),r=Array.from(o.querySelectorAll("."+x));r.every(t=>!t.textContent.trim())||t.push(r.map(t=>t.innerHTML))}return t}destroy(){document.removeEventListener("click",this.documentClicked)}}o(1),e.default=class{static get isReadOnlySupported(){return!0}static get enableLineBreaks(){return!0}constructor({data:t,config:e,api:o,readOnly:r}){this.api=o,this.readOnly=r,this.config=e,this.data={withHeadings:this.getConfig("withHeadings",!1,t),content:t&&t.content?t.content:[]},this.table=null}static get toolbox(){return{icon:'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" d="M10 5V18.5"/><path stroke="currentColor" stroke-width="2" d="M5 10H19"/><rect width="14" height="14" x="5" y="5" stroke="currentColor" stroke-width="2" rx="4"/></svg>',title:"Table"}}render(){return this.table=new R(this.readOnly,this.api,this.data,this.config),this.container=r("div",this.api.styles.block),this.container.appendChild(this.table.getWrapper()),this.table.setHeadingsSetting(this.data.withHeadings),this.container}renderSettings(){return[{label:this.api.i18n.t("With headings"),icon:'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" d="M5 10H19"/><rect width="14" height="14" x="5" y="5" stroke="currentColor" stroke-width="2" rx="4"/></svg>',isActive:this.data.withHeadings,closeOnActivate:!0,toggle:!0,onActivate:()=>{this.data.withHeadings=!0,this.table.setHeadingsSetting(this.data.withHeadings)}},{label:this.api.i18n.t("Without headings"),icon:'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" d="M10 5V18.5"/><path stroke="currentColor" stroke-width="2" d="M14 5V18.5"/><path stroke="currentColor" stroke-width="2" d="M5 10H19"/><path stroke="currentColor" stroke-width="2" d="M5 14H19"/><rect width="14" height="14" x="5" y="5" stroke="currentColor" stroke-width="2" rx="4"/></svg>',isActive:!this.data.withHeadings,closeOnActivate:!0,toggle:!0,onActivate:()=>{this.data.withHeadings=!1,this.table.setHeadingsSetting(this.data.withHeadings)}}]}save(){const t=this.table.getData();return{withHeadings:this.data.withHeadings,content:t}}destroy(){this.table.destroy()}getConfig(t,e,o){const r=this.data||o;return r?r[t]?r[t]:e:this.config&&this.config[t]?this.config[t]:e}static get pasteConfig(){return{tags:["TABLE","TR","TH","TD"]}}onPaste(t){const e=t.detail.data,o=e.querySelector(":scope > thead, tr:first-of-type th"),r=Array.from(e.querySelectorAll("tr")).map(t=>Array.from(t.querySelectorAll("th, td")).map(t=>t.innerHTML));this.data={withHeadings:null!==o,content:r},this.table.wrapper&&this.table.wrapper.replaceWith(this.render())}}}]).default}));
