

/*! 
	ADDON: scrollVert
	Plugin for Cycle2; Copyright (c) 2012 M. Alsup; ver: 20121120 
*/
(function(a){"use strict",a.fn.cycletwo.transitions.scrollVert={before:function(a,b,c,d){a.API.stackSlides(a,b,c,d);var e=a.container.css("overflow","hidden").height();a.cssBefore={top:d?-e:e,left:0,opacity:1,display:"block"},a.animIn={top:0},a.animOut={top:d?e:-e}}}})(jQuery);


/*!
	ADDON: IE-Fade
	Plugin for Cycle2; Copyright (c) 2012 M. Alsup; ver: 20121120 
*/
(function(a){function b(a,b,c){if(a&&c.style.filter){b._filter=c.style.filter;try{c.style.removeAttribute("filter")}catch(d){}}else!a&&b._filter&&(c.style.filter=b._filter)}"use strict",a.extend(a.fn.cycletwo.transitions,{fade:{before:function(c,d,e,f){var g=c.API.getSlideOpts(c.nextSlide).slideCss||{};c.API.stackSlides(d,e,f),c.cssBefore=a.extend(g,{opacity:0,display:"block"}),c.animIn={opacity:1},c.animOut={opacity:0},b(!0,c,e)},after:function(a,c,d){b(!1,a,d)}},fadeout:{before:function(c,d,e,f){var g=c.API.getSlideOpts(c.nextSlide).slideCss||{};c.API.stackSlides(d,e,f),c.cssBefore=a.extend(g,{opacity:1,display:"block"}),c.animOut={opacity:0},b(!0,c,e)},after:function(a,c,d){b(!1,a,d)}}})})(jQuery);


/*! 
	ADDON: swipe
	Plugin for Cycle2; Copyright (c) 2012 M. Alsup; ver: 20121120 
*/
(function(a){"use strict";var b="ontouchend"in document;a.event.special.swipe=a.event.special.swipe||{scrollSupressionThreshold:10,durationThreshold:1e3,horizontalDistanceThreshold:30,verticalDistanceThreshold:75,setup:function(){var b=a(this);b.bind("touchstart",function(c){function g(b){if(!f)return;var c=b.originalEvent.touches?b.originalEvent.touches[0]:b;e={time:(new Date).getTime(),coords:[c.pageX,c.pageY]},Math.abs(f.coords[0]-e.coords[0])>a.event.special.swipe.scrollSupressionThreshold&&b.preventDefault()}var d=c.originalEvent.touches?c.originalEvent.touches[0]:c,e,f={time:(new Date).getTime(),coords:[d.pageX,d.pageY],origin:a(c.target)};b.bind("touchmove",g).one("touchend",function(c){b.unbind("touchmove",g),f&&e&&e.time-f.time<a.event.special.swipe.durationThreshold&&Math.abs(f.coords[0]-e.coords[0])>a.event.special.swipe.horizontalDistanceThreshold&&Math.abs(f.coords[1]-e.coords[1])<a.event.special.swipe.verticalDistanceThreshold&&f.origin.trigger("swipe").trigger(f.coords[0]>e.coords[0]?"swipeleft":"swiperight"),f=e=undefined})})}},a.event.special.swipeleft=a.event.special.swipeleft||{setup:function(){a(this).bind("swipe",a.noop)}},a.event.special.swiperight=a.event.special.swiperight||a.event.special.swipeleft})(jQuery);


/*! 
	ADDON: center
	Plugin for Cycle2; Copyright (c) 2012 M. Alsup; ver: 20140128 
*/
(function(e){"use strict";e.extend(e.fn.cycletwo.defaults,{centerHorz:!1,centerVert:!1}),e(document).on("cycletwo-pre-initialize",function(i,t){function n(){clearTimeout(c),c=setTimeout(l,50)}function s(){clearTimeout(c),clearTimeout(a),e(window).off("resize orientationchange",n)}function o(){t.slides.each(r)}function l(){r.apply(t.container.find("."+t.slideActiveClass)),clearTimeout(a),a=setTimeout(o,50)}function r(){var i=e(this),n=t.container.width(),s=t.container.height(),o=i.outerWidth(),l=i.outerHeight();o&&(t.centerHorz&&n>=o&&i.css("marginLeft",(n-o)/2),t.centerVert&&s>=l&&i.css("marginTop",(s-l)/2))}if(t.centerHorz||t.centerVert){var c,a;e(window).on("resize orientationchange load",n),t.container.on("cycletwo-destroyed",s),t.container.on("cycletwo-initialized cycletwo-slide-added cycletwo-slide-removed",function(){n()}),l()}})})(jQuery);