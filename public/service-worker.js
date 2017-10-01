"use strict";function setOfCachedUrls(e){return e.keys().then(function(e){return e.map(function(e){return e.url})}).then(function(e){return new Set(e)})}var precacheConfig=[["index.html","b2c18b37f76ab1977ea18d5613f6ea65"],["service-worker.js","0278e1917d66238ab6be17cee48a2b45"],["static/css/app.ed5c70ae84a90758cb0d11878f5841bd.css","dfbeae3e6d512c5adb86aac75af14a6b"],["static/css/bootstrap.min.css","3ce8c46266a572488f1aff4293c66df2"],["static/css/font-awesome.min.css","269550530cc127b6aa5a35925a7de6ce"],["static/js/app.d08dc995ef181516a915.js","6ce88b84e7182053e8efadd35dde3886"],["static/js/manifest.1afdc936bfc6be4a876f.js","b948b5813866eb0fc15c5c2063c871bb"],["static/js/pikaday/css/pikaday.css","f5114c6561620e3dc536fec947dc001b"],["static/js/pikaday/css/site.css","9c2c7d8207ba912003bacba6ef2baa6c"],["static/js/pikaday/css/theme.css","cc907317527e2b046e7ffb69e1906cd1"],["static/js/pikaday/css/triangle.css","7efc0179499af8b4f6588c142ae0cdca"],["static/js/pikaday/pikaday.js","f893cb2b505868c24c1426c0140f7246"],["static/js/pikaday/plugins/pikaday.jquery.js","64665444c2e65c4d13e6ca8a5cf1fb11"],["static/js/vendor.c4474a783072ae78d734.js","28eff5b320d7b20209439b289abd0e77"]],cacheName="sw-precache-v3-my-vue-app-"+(self.registration?self.registration.scope:""),ignoreUrlParametersMatching=[/^utm_/],addDirectoryIndex=function(e,t){var a=new URL(e);return"/"===a.pathname.slice(-1)&&(a.pathname+=t),a.toString()},cleanResponse=function(e){return e.redirected?("body"in e?Promise.resolve(e.body):e.blob()).then(function(t){return new Response(t,{headers:e.headers,status:e.status,statusText:e.statusText})}):Promise.resolve(e)},createCacheKey=function(e,t,a,n){var s=new URL(e);return n&&s.pathname.match(n)||(s.search+=(s.search?"&":"")+encodeURIComponent(t)+"="+encodeURIComponent(a)),s.toString()},isPathWhitelisted=function(e,t){if(0===e.length)return!0;var a=new URL(t).pathname;return e.some(function(e){return a.match(e)})},stripIgnoredUrlParameters=function(e,t){var a=new URL(e);return a.hash="",a.search=a.search.slice(1).split("&").map(function(e){return e.split("=")}).filter(function(e){return t.every(function(t){return!t.test(e[0])})}).map(function(e){return e.join("=")}).join("&"),a.toString()},hashParamName="_sw-precache",urlsToCacheKeys=new Map(precacheConfig.map(function(e){var t=e[0],a=e[1],n=new URL(t,self.location),s=createCacheKey(n,hashParamName,a,!1);return[n.toString(),s]}));self.addEventListener("install",function(e){e.waitUntil(caches.open(cacheName).then(function(e){return setOfCachedUrls(e).then(function(t){return Promise.all(Array.from(urlsToCacheKeys.values()).map(function(a){if(!t.has(a)){var n=new Request(a,{credentials:"same-origin"});return fetch(n).then(function(t){if(!t.ok)throw new Error("Request for "+a+" returned a response with status "+t.status);return cleanResponse(t).then(function(t){return e.put(a,t)})})}}))})}).then(function(){return self.skipWaiting()}))}),self.addEventListener("activate",function(e){var t=new Set(urlsToCacheKeys.values());e.waitUntil(caches.open(cacheName).then(function(e){return e.keys().then(function(a){return Promise.all(a.map(function(a){if(!t.has(a.url))return e.delete(a)}))})}).then(function(){return self.clients.claim()}))}),self.addEventListener("fetch",function(e){if("GET"===e.request.method){var t,a=stripIgnoredUrlParameters(e.request.url,ignoreUrlParametersMatching);(t=urlsToCacheKeys.has(a))||(a=addDirectoryIndex(a,"index.html"),t=urlsToCacheKeys.has(a));t&&e.respondWith(caches.open(cacheName).then(function(e){return e.match(urlsToCacheKeys.get(a)).then(function(e){if(e)return e;throw Error("The cached response that was expected is missing.")})}).catch(function(t){return console.warn('Couldn\'t serve response for "%s" from cache: %O',e.request.url,t),fetch(e.request)}))}});