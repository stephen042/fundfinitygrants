/*! modernizr 3.6.0 (Custom Build) | MIT *
 * https://modernizr.com/download/?-cssanimations-cssgrid_cssgridlegacy-flexbox-setclasses !*/
!function(e,n,t){function r(e,n){return typeof e===n}function s(){var e,n,t,s,o,i,a;for(var l in w)if(w.hasOwnProperty(l)){if(e=[],n=w[l],n.name&&(e.push(n.name.toLowerCase()),n.options&&n.options.aliases&&n.options.aliases.length))for(t=0;t<n.options.aliases.length;t++)e.push(n.options.aliases[t].toLowerCase());for(s=r(n.fn,"function")?n.fn():n.fn,o=0;o<e.length;o++)i=e[o],a=i.split("."),1===a.length?Modernizr[a[0]]=s:(!Modernizr[a[0]]||Modernizr[a[0]]instanceof Boolean||(Modernizr[a[0]]=new Boolean(Modernizr[a[0]])),Modernizr[a[0]][a[1]]=s),C.push((s?"":"no-")+a.join("-"))}}function o(e){var n=x.className,t=Modernizr._config.classPrefix||"";if(_&&(n=n.baseVal),Modernizr._config.enableJSClass){var r=new RegExp("(^|\\s)"+t+"no-js(\\s|$)");n=n.replace(r,"$1"+t+"js$2")}Modernizr._config.enableClasses&&(n+=" "+t+e.join(" "+t),_?x.className.baseVal=n:x.className=n)}function i(e,n){return!!~(""+e).indexOf(n)}function a(){return"function"!=typeof n.createElement?n.createElement(arguments[0]):_?n.createElementNS.call(n,"http://www.w3.org/2000/svg",arguments[0]):n.createElement.apply(n,arguments)}function l(e){return e.replace(/([a-z])-([a-z])/g,function(e,n,t){return n+t.toUpperCase()}).replace(/^-/,"")}function f(e,n){return function(){return e.apply(n,arguments)}}function u(e,n,t){var s;for(var o in e)if(e[o]in n)return t===!1?e[o]:(s=n[e[o]],r(s,"function")?f(s,t||n):s);return!1}function d(e){return e.replace(/([A-Z])/g,function(e,n){return"-"+n.toLowerCase()}).replace(/^ms-/,"-ms-")}function c(n,t,r){var s;if("getComputedStyle"in e){s=getComputedStyle.call(e,n,t);var o=e.console;if(null!==s)r&&(s=s.getPropertyValue(r));else if(o){var i=o.error?"error":"log";o[i].call(o,"getComputedStyle returning null, its possible modernizr test results are inaccurate")}}else s=!t&&n.currentStyle&&n.currentStyle[r];return s}function p(){var e=n.body;return e||(e=a(_?"svg":"body"),e.fake=!0),e}function m(e,t,r,s){var o,i,l,f,u="modernizr",d=a("div"),c=p();if(parseInt(r,10))for(;r--;)l=a("div"),l.id=s?s[r]:u+(r+1),d.appendChild(l);return o=a("style"),o.type="text/css",o.id="s"+u,(c.fake?c:d).appendChild(o),c.appendChild(d),o.styleSheet?o.styleSheet.cssText=e:o.appendChild(n.createTextNode(e)),d.id=u,c.fake&&(c.style.background="",c.style.overflow="hidden",f=x.style.overflow,x.style.overflow="hidden",x.appendChild(c)),i=t(d,e),c.fake?(c.parentNode.removeChild(c),x.style.overflow=f,x.offsetHeight):d.parentNode.removeChild(d),!!i}function g(n,r){var s=n.length;if("CSS"in e&&"supports"in e.CSS){for(;s--;)if(e.CSS.supports(d(n[s]),r))return!0;return!1}if("CSSSupportsRule"in e){for(var o=[];s--;)o.push("("+d(n[s])+":"+r+")");return o=o.join(" or "),m("@supports ("+o+") { #modernizr { position: absolute; } }",function(e){return"absolute"==c(e,null,"position")})}return t}function y(e,n,s,o){function f(){d&&(delete E.style,delete E.modElem)}if(o=r(o,"undefined")?!1:o,!r(s,"undefined")){var u=g(e,s);if(!r(u,"undefined"))return u}for(var d,c,p,m,y,v=["modernizr","tspan","samp"];!E.style&&v.length;)d=!0,E.modElem=a(v.shift()),E.style=E.modElem.style;for(p=e.length,c=0;p>c;c++)if(m=e[c],y=E.style[m],i(m,"-")&&(m=l(m)),E.style[m]!==t){if(o||r(s,"undefined"))return f(),"pfx"==n?m:!0;try{E.style[m]=s}catch(h){}if(E.style[m]!=y)return f(),"pfx"==n?m:!0}return f(),!1}function v(e,n,t,s,o){var i=e.charAt(0).toUpperCase()+e.slice(1),a=(e+" "+P.join(i+" ")+i).split(" ");return r(n,"string")||r(n,"undefined")?y(a,n,s,o):(a=(e+" "+T.join(i+" ")+i).split(" "),u(a,n,t))}function h(e,n,r){return v(e,t,t,n,r)}var C=[],w=[],S={_version:"3.6.0",_config:{classPrefix:"",enableClasses:!0,enableJSClass:!0,usePrefixes:!0},_q:[],on:function(e,n){var t=this;setTimeout(function(){n(t[e])},0)},addTest:function(e,n,t){w.push({name:e,fn:n,options:t})},addAsyncTest:function(e){w.push({name:null,fn:e})}},Modernizr=function(){};Modernizr.prototype=S,Modernizr=new Modernizr;var x=n.documentElement,_="svg"===x.nodeName.toLowerCase(),b="Moz O ms Webkit",P=S._config.usePrefixes?b.split(" "):[];S._cssomPrefixes=P;var T=S._config.usePrefixes?b.toLowerCase().split(" "):[];S._domPrefixes=T;var z={elem:a("modernizr")};Modernizr._q.push(function(){delete z.elem});var E={style:z.elem.style};Modernizr._q.unshift(function(){delete E.style}),S.testAllProps=v,S.testAllProps=h,Modernizr.addTest("cssanimations",h("animationName","a",!0)),Modernizr.addTest("cssgridlegacy",h("grid-columns","10px",!0)),Modernizr.addTest("cssgrid",h("grid-template-rows","none",!0)),Modernizr.addTest("flexbox",h("flexBasis","1px",!0)),s(),o(C),delete S.addTest,delete S.addAsyncTest;for(var N=0;N<Modernizr._q.length;N++)Modernizr._q[N]();e.Modernizr=Modernizr}(window,document);