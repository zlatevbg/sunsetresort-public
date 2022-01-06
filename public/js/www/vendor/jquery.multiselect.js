!function(e,t){var s=0,i=/[\-\[\]{}()*+?.,\\\^$|#\s]/g;e.widget("multiselect.multiselect",{options:{header:!0,maxHeight:192,width:"100%",classes:"",checkAllText:"Check all",uncheckAllText:"Uncheck all",noneSelectedText:"Select options",noneSelectedSingleText:"Select option",selectedText:"# selected",selectedList:3,speed:null,autoOpen:!1,multiple:!0,showSubText:!1,searchDelay:100,filter:!0,filterMinOptions:10,filterLabel:"",filterPlaceholder:"Enter keywords",autoReset:!0,position:{}},_create:function(){var t=this.element.hide(),i=this.options;this.speed=i.speed||e.fx.speeds._default,this._isOpen=!1,this._namespaceID=this.eventNamespace||"multiselect"+s,this.wrapper=e("<div />").addClass("multiselectWrapper"),t.wrap(this.wrapper),this.button=e('<button type="button"><span class="caret"></span></button>').addClass("btn btn-block btn-default").addClass(i.classes).attr({title:t.attr("title"),"aria-haspopup":!0,tabIndex:t.attr("tabIndex")}).insertAfter(t),this.buttonlabel=e("<div />").html(i.multiple?i.noneSelectedText:i.noneSelectedSingleText).appendTo(this.button),this.menu=e("<section />").addClass("dropdown-menu").addClass(i.classes).insertAfter(this.button),i.multiple||this.menu.addClass("multiselect-single"),this.header=e("<header />").appendTo(this.menu),this.filterWrapper=e('<div class="multiselect-filter">'+i.filterLabel+"</div>").appendTo(this.header),this.filter=e('<input class="form-control" placeholder="'+i.filterPlaceholder+'" type="search" />').appendTo(this.filterWrapper),this.headerLinkContainer=e("<ul />").html(function(){return i.header===!0?'<li><a class="multiselect-all" href="#"><span class="glyphicon glyphicon-ok"></span>'+i.checkAllText+'</a></li><li><a class="multiselect-none" href="#"><span class="glyphicon glyphicon-remove"></span>'+i.uncheckAllText+"</a></li>":"string"==typeof i.header?"<li>"+i.header+"</li>":""}).append('<li class="multiselect-close"><a href="#" class="multiselect-close"><span class="glyphicon glyphicon-remove-circle"></span></a></li>').appendTo(this.header),this.checkboxContainer=e('<ul tabindex="-1" />').appendTo(this.menu),this._bindEvents(),this.refresh(!0),this.updateCache(),s++},_init:function(){this.options.header===!1&&this.header.hide(),this.options.filter===!1&&this.filterWrapper.hide(),this.options.multiple||this.headerLinkContainer.find(".multiselect-all, .multiselect-none").hide(),this.options.autoOpen&&this.open(),this.element.is(":disabled")&&this.disable()},refresh:function(t){var i=this.options,l=[],n="",o=this.element.attr("id")||s++,a=this;this.element.find("option").each(function(t){var s,h="multiselect-"+(this.id||o+"-option-"+t),c=[],r=this.className+" "+(i.multiple?"checkbox":"radio"),u=e(this).data("subText");"OPTGROUP"===this.parentNode.tagName&&(s=this.parentNode.getAttribute("label"),e.inArray(s,l)===-1&&(n+='<li class="multiselect-optgroup '+this.parentNode.className+(this.parentNode.disabled?" disabled multiselect-state-disabled":"")+'"><a href="#">'+s+"</a></li>",l.push(s),this.parentNode.disabled&&e(this.parentNode).children().each(a._toggleState("disabled",!0)))),this.disabled&&(c.push("multiselect-state-disabled"),r+=" disabled"),this.selected&&!i.multiple&&c.push("selected"),n+='<li class="'+r+'">',n+='<label for="'+h+'" title="'+this.title+'" class="'+c.join(" ")+'">',n+='<input id="'+h+'" name="multiselect_'+o+'" type="'+(i.multiple?"checkbox":"radio")+'" value="'+this.value+'" title="'+this.title+'"',this.selected&&(n+=" checked",n+=' aria-selected="true"'),this.disabled&&(n+=" disabled",n+=' aria-disabled="true"'),n+=" />"+this.innerHTML+(u?'<span class="sub-text">/ '+u+"</span>":"")+"</label></li>"}),this.checkboxContainer.html(n),this.optgroups=this.checkboxContainer.find("li.multiselect-optgroup"),this.labels=this.checkboxContainer.find("label"),this.inputs=this.labels.children("input"),i.filterMinOptions&&this.inputs.length>=i.filterMinOptions?this._setOption("filter",!0):this._setOption("filter",!1),this._setButtonWidth(),this.button[0].defaultValue=this.update(),t||this._trigger("refresh")},update:function(){var t,s=this,i=s.inputs.filter(":checked"),l=i.length;return t=0===l?s.options.multiple?s.options.noneSelectedText:s.options.noneSelectedSingleText:e.isFunction(s.options.selectedText)?s.options.selectedText.call(s,l,s.inputs.length,i.get()):/\d/.test(s.options.selectedList)&&s.options.selectedList>0&&l<=s.options.selectedList?i.map(function(){var t=e(this).next(),i=t.length?t[0].outerHTML:null;return e(this)[0].nextSibling.nodeValue+(s.options.showSubText&&i?i:"")}).get().join(", "):s.options.selectedText.replace("#",l).replace("#",s.inputs.length),s._setButtonValue(t),t},_setButtonValue:function(e){this.button.find("div").html(e)},_bindEvents:function(){var t=this;t.button.on("click",function(){return t[t._isOpen?"close":"open"](),!1}).on("keydown",function(e){switch(e.which){case 27:case 38:case 37:t.close();break;case 39:case 40:t.open()}}),t.menu.on("keydown",function(e){switch(e.which){case 27:e.preventDefault(),t.close()}}),t.filter.on("keydown",function(e){switch(e.which){case 13:e.preventDefault()}}).on("focus",function(){t.labels.removeClass("selected"),t.optgroups.removeClass("selected")}).on("keyup search input paste cut",e.debounce(t.options.searchDelay,function(){t._handler()})),e(document).on("multiselectrefresh",function(){t.updateCache(),t._handler()}).on("multiselectclose",function(){t._reset()}).on("mousedown."+t._namespaceID,function(s){var i=s.target;!t._isOpen||i===t.button[0]||i===t.menu[0]||e.contains(t.menu[0],i)||e.contains(t.button[0],i)||t.close()}),t.header.on("click","a",function(s){e(this).hasClass("multiselect-close")?t.close():t[e(this).hasClass("multiselect-all")?"checkAll":"uncheckAll"](),s.preventDefault()}).on("focus","a",function(){t.labels.removeClass("selected"),t.optgroups.removeClass("selected")}),t.checkboxContainer.on("click",".multiselect-optgroup a",function(s){s.preventDefault();var i=e(this).parent().nextUntil("li.multiselect-optgroup").find("input:visible:not(:disabled)");if(i.length){var l=i.get(),n=e(this).parent().text();if(t._trigger("beforeoptgrouptoggle",s,{inputs:l,label:n})===!1)return;t._toggleChecked(i.filter(":checked").length!==i.length,i),t._trigger("optgrouptoggle",s,{inputs:l,label:n,checked:l[0].checked})}}).on("mouseenter","label",function(){e(this).hasClass("multiselect-state-disabled")||e(this).addClass("selected").find("input").focus()}).on("mouseleave","label",function(){e(this).hasClass("multiselect-state-disabled")||t.labels.removeClass("selected")}).on("mouseenter",".multiselect-optgroup",function(){e(this).hasClass("multiselect-state-disabled")||e(this).addClass("selected").find("a").focus()}).on("mouseleave",".multiselect-optgroup",function(){e(this).hasClass("multiselect-state-disabled")||t.optgroups.removeClass("selected")}).on("keydown","label, .multiselect-optgroup a",function(s){switch(9!=s.which&&s.preventDefault(),s.which){case 9:e(this).parent()[s.shiftKey?"prevAll":"nextAll"]("li:not(:hidden, .disabled)").first().length&&(s.preventDefault(),s.shiftKey?t._traverse(38,this):t._traverse(40,this));break;case 38:case 40:case 37:case 39:t._traverse(s.which,this);break;case 13:var i=e(this).find("input");i.length?i[0].click():this.click()}}).on("click",'input[type="checkbox"], input[type="radio"]',function(s){var i=this.value,l=this.checked;return this.disabled||t._trigger("click",s,{value:i,text:this.title,checked:l})===!1?void s.preventDefault():(t._toggleChecked(l,e(this)),void(t.options.multiple||(t.labels.removeClass("multiselect-state-selected"),e(this).closest("label").toggleClass("multiselect-state-selected",l),t.close())))}).on("focus","input, .multiselect-optgroup a",function(){var s=e(this).parent();s.hasClass("selected")||(t.checkboxContainer.scrollTop(0),s.trigger("mouseenter"))})},_handler:function(t){var s=e.trim(this.filter[0].value.toLowerCase()),l=this.rows,n=this.inputs;if(s){l.hide();var o=new RegExp(s.replace(i,"\\$&"),"gi");this._trigger("filter",t,e.map(this.cache,function(e,t){return e.search(o)!==-1?(l.eq(t).show(),n.get(t)):null}))}else l.show();this.checkboxContainer.find(".multiselect-optgroup").each(function(){var t=e(this).nextUntil(".multiselect-optgroup").filter(function(){return"none"!==e.css(this,"display")}).length;e(this)[t?"show":"hide"]()})},_reset:function(){this.filter.val("").trigger("keyup")},updateCache:function(){this.rows=this.checkboxContainer.find("li:not(.multiselect-optgroup)"),this.cache=this.element.children().map(function(){var t=e(this);return"optgroup"===this.tagName.toLowerCase()&&(t=t.children()),t.map(function(){var e=t.data("subText");return this.innerHTML.toLowerCase()+(e?" "+e.toLowerCase():"")}).get()}).get()},_setButtonWidth:function(){this.button.outerWidth(this.options.width)},_traverse:function(t,s){e(s).trigger("mouseleave");var i=38===t||37===t,l=e(s).parent()[i?"prevAll":"nextAll"]("li:not(:hidden, .disabled)").first();l.hasClass("multiselect-optgroup")?l.trigger("mouseenter"):l.length?l.find("label").trigger("mouseenter"):(this.checkboxContainer.find("label")[i?"last":"first"]().trigger("mouseenter"),i?this.checkboxContainer.scrollTop(this.checkboxContainer[0].scrollHeight):this.checkboxContainer.scrollTop(0))},_toggleState:function(e,t){return function(){this.disabled||(this[e]=t),t?this.setAttribute("aria-selected",!0):this.removeAttribute("aria-selected")}},_toggleChecked:function(t,s){var i=this,l=s&&s.length?s:this.inputs;this.options.filter&&(l=l.not(":hidden")),this.options.multiple||this.inputs.each(this._toggleState("checked",!1)),l.each(this._toggleState("checked",t)),this.update();var n=l.map(function(){return this.value}).get();this.element.find("option").each(function(){i.options.multiple||i._toggleState("selected",!1).call(this),!this.disabled&&e.inArray(this.value,n)>-1&&i._toggleState("selected",t).call(this)}),l.length&&this.element.trigger("change")},_toggleDisabled:function(t){this.button.attr({disabled:t,"aria-disabled":t})[t?"addClass":"removeClass"]("multiselect-state-disabled");var s=this.checkboxContainer.find("input"),i="multiselect-disabled";s=t?s.filter(":enabled").data(i,!0):s.filter(function(){return e.data(this,i)===!0}).removeData(i),s.attr({disabled:t,"arial-disabled":t}).parent()[t?"addClass":"removeClass"]("multiselect-state-disabled"),this.element.attr({disabled:t,"aria-disabled":t})},open:function(e){this._trigger("beforeopen")===!1||this.button.hasClass("multiselect-state-disabled")||this._isOpen||(this.checkboxContainer.css("max-height",this.options.maxHeight),this.position(),this.menu.fadeIn(this.speed),this.options.filter&&this.filter.focus(),this.checkboxContainer.scrollTop(0),this.button.addClass("multiselect-state-active"),this._isOpen=!0,this._trigger("open"))},close:function(){this._trigger("beforeclose")!==!1&&(this.menu.hide(),this.button.removeClass("multiselect-state-active").trigger("blur").trigger("mouseleave"),this._isOpen=!1,this._trigger("close"))},enable:function(){this._toggleDisabled(!1)},disable:function(){this._toggleDisabled(!0)},checkAll:function(e){this._toggleChecked(!0),this._trigger("checkAll")},uncheckAll:function(){this._toggleChecked(!1),this._trigger("uncheckAll")},getChecked:function(){return this.checkboxContainer.find("input").filter(":checked")},destroy:function(){return e.Widget.prototype.destroy.call(this),e(document).off(this._namespaceID),this._reset(),this.button.remove(),this.menu.remove(),this.element.show(),this},isOpen:function(){return this._isOpen},widget:function(){return this.menu},getButton:function(){return this.button},position:function(){e.ui.position&&!e.isEmptyObject(this.options.position)&&(this.options.position.of=this.options.position.of||this.button,this.menu.show().position(this.options.position).hide())},_setOption:function(t,s){switch(t){case"header":this.header[s?"show":"hide"]();break;case"filter":this.filterWrapper[s?"show":"hide"]();break;case"checkAllText":this.header.find("a.multiselect-all").contents().last().replaceWith(s);break;case"uncheckAllText":this.header.find("a.multiselect-none").contents().last().replaceWith(s);break;case"maxHeight":this.checkboxContainer.css("max-height",parseInt(s,10));break;case"width":this.options[t]=s,this._setButtonWidth();break;case"selectedText":case"selectedList":case"noneSelectedText":case"noneSelectedSingleText":this.options[t]=s,this.update();break;case"classes":this.menu.add(this.button).removeClass(this.options.classes).addClass(s);break;case"multiple":this.menu.toggleClass("multiselect-single",!s),this.options.multiple=s,this.element[0].multiple=s,this.refresh();break;case"position":this.position()}e.Widget.prototype._setOption.apply(this,arguments)}})}(jQuery);