"use strict";var _createClass=function(){function t(t,i){for(var e=0;e<i.length;e++){var n=i[e];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}return function(i,e,n){return e&&t(i.prototype,e),n&&t(i,n),i}}();function _classCallCheck(t,i){if(!(t instanceof i))throw new TypeError("Cannot call a class as a function")}var Tweezer=function(){function t(){var i=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};_classCallCheck(this,t),this.duration=i.duration||300,this.ease=i.easing||this.easeInOutQuad,this.start=i.start,this.end=i.end,this.frame=null,this.next=null,this.isRunning=!1,this.events={},this.direction=this.start<this.end?"up":"down"}return _createClass(t,[{key:"begin",value:function(){return this.isRunning||this.next===this.end||(this.frame=requestAnimationFrame(this._tick.bind(this))),this}},{key:"stop",value:function(){return cancelAnimationFrame(this.frame),this.isRunning=!1,this.frame=null,this.timeStart=null,this.next=null,this}},{key:"on",value:function(t,i){return this.events[t]=this.events[t]||[],this.events[t].push(i),this}},{key:"emit",value:function(t,i){var e=this,n=this.events[t];n&&n.forEach(function(t){return t.call(e,i)})}},{key:"_tick",value:function(t){this.isRunning=!0;var i=this.next||this.start;this.timeStart||(this.timeStart=t),this.timeElapsed=t-this.timeStart,this.next=Math.round(this.ease(this.timeElapsed,this.start,this.end-this.start,this.duration)),this._shouldTick(i)?(this.emit("tick",this.next),this.frame=requestAnimationFrame(this._tick.bind(this))):(this.emit("tick",this.end),this.emit("done",null))}},{key:"_shouldTick",value:function(t){return{up:this.next<this.end&&t<=this.next,down:this.next>this.end&&t>=this.next}[this.direction]}},{key:"easeInOutQuad",value:function(t,i,e,n){return(t/=n/2)<1?e/2*t*t+i:-e/2*(--t*(t-2)-1)+i}}]),t}();
