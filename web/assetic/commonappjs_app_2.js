window.PJAX_ENABLED=true;window.DEBUG=true;$.fn.widgster.Constructor.DEFAULTS.bodySelector=".widget-body";$(function(){var SingAppView=function(){this.pjaxEnabled=window.PJAX_ENABLED;this.debug=window.DEBUG;this.navCollapseTimeout=2500;this.$sidebar=$("#sidebar");this.$content=$("#content");this.$loaderWrap=$(".loader-wrap");this.$navigationStateToggle=$("#nav-state-toggle");this.$navigationCollapseToggle=$("#nav-collapse-toggle");this.settings=window.SingSettings;this.pageLoadCallbacks={};this.resizeCallbacks=[];this.screenSizeCallbacks={xs:{enter:[],exit:[]},sm:{enter:[],exit:[]},md:{enter:[],exit:[]},lg:{enter:[],exit:[]}};this.loading=false;this._resetResizeCallbacks();this._initOnResizeCallbacks();this._initOnScreenSizeCallbacks();this.$sidebar.on("mouseenter",$.proxy(this._sidebarMouseEnter,this));this.$sidebar.on("mouseleave",$.proxy(this._sidebarMouseLeave,this));$(document).on("click",".nav-collapsed #sidebar",$.proxy(this.expandNavigation,this));"ontouchstart"in window&&this.$content.swipe({swipeLeft:$.proxy(this._contentSwipeLeft,this),swipeRight:$.proxy(this._contentSwipeRight,this),threshold:Sing.isScreen("xs")?100:200});this.checkNavigationState();if(this.pjaxEnabled){this.$sidebar.find(".sidebar-nav a:not([data-toggle=collapse], [data-no-pjax], [href=#])").on("click",$.proxy(this._checkLoading,this));$(document).pjax("#sidebar .sidebar-nav a:not([data-toggle=collapse], [data-no-pjax], [href=#])","#content",{fragment:"#content",type:"GET",timeout:4e3});$(document).on("pjax:start",$.proxy(this._changeActiveNavigationItem,this));$(document).on("pjax:start",$.proxy(this._resetResizeCallbacks,this));$(document).on("pjax:send",$.proxy(this.showLoader,this));$(document).on("pjax:success",$.proxy(this._loadScripts,this));$(document).on("sing-app:loaded",$.proxy(this._loadingFinished,this));$(document).on("sing-app:loaded",$.proxy(this._collapseNavIfSmallScreen,this));$(document).on("sing-app:loaded",$.proxy(this.hideLoader,this));$(document).on("pjax:end",$.proxy(this.pageLoaded,this))}this.$navigationStateToggle.on("click",$.proxy(this.toggleNavigationState,this));this.$navigationCollapseToggle.on("click",$.proxy(this.toggleNavigationCollapseState,this));this.$sidebar.find(".collapse").on("show.bs.collapse",function(e){if(e.target!=e.currentTarget)return;var $triggerLink=$(this).prev("[data-toggle=collapse]");$($triggerLink.data("parent")).find(".collapse.in").not($(this)).collapse("hide")}).on("show.bs.collapse",function(e){if(e.target!=e.currentTarget)return;$(this).closest("li").addClass("open")}).on("hide.bs.collapse",function(e){if(e.target!=e.currentTarget)return;$(this).closest("li").removeClass("open")});window.onerror=$.proxy(this._logErrors,this)};SingAppView.prototype._initOnResizeCallbacks=function(){var resizeTimeout,view=this;$(window).on("resize sing-app:content-resize",function(){clearTimeout(resizeTimeout);resizeTimeout=setTimeout(function(){view._runPageCallbacks(view.pageResizeCallbacks);view.resizeCallbacks.forEach(function(fn){fn()})},100)})};SingAppView.prototype._initOnScreenSizeCallbacks=function(){var resizeTimeout,view=this,prevSize=Sing.getScreenSize();$(window).resize(function(){clearTimeout(resizeTimeout);resizeTimeout=setTimeout(function(){var size=Sing.getScreenSize();if(size!=prevSize){view.screenSizeCallbacks[prevSize]["exit"].forEach(function(fn){fn(size,prevSize)});view.screenSizeCallbacks[size]["enter"].forEach(function(fn){fn(size,prevSize)});view.log("screen changed. new: "+size+", old: "+prevSize)}prevSize=size},100)})};SingAppView.prototype._resetResizeCallbacks=function(){this.pageResizeCallbacks={}};SingAppView.prototype.checkNavigationState=function(){if(this.isNavigationStatic()){this.staticNavigationState();if(Sing.isScreen("sm")||Sing.isScreen("xs")){this.collapseNavigation()}}else{if(Sing.isScreen("md")||Sing.isScreen("lg")){var view=this;setTimeout(function(){view.collapseNavigation()},this.navCollapseTimeout)}else{this.collapseNavigation()}}};SingAppView.prototype.toggleNavigationCollapseState=function(){if($("body").is(".nav-collapsed")){this.expandNavigation()}else{this.collapseNavigation()}};SingAppView.prototype.collapseNavigation=function(){if(this.isNavigationStatic()&&(Sing.isScreen("md")||Sing.isScreen("lg")))return;$("body").addClass("nav-collapsed");this.$sidebar.find(".collapse.in").collapse("hide").siblings("[data-toggle=collapse]").addClass("collapsed")};SingAppView.prototype.expandNavigation=function(){if(this.isNavigationStatic()&&(Sing.isScreen("md")||Sing.isScreen("lg")))return;$("body").removeClass("nav-collapsed");this.$sidebar.find(".active .active").closest(".collapse").collapse("show").siblings("[data-toggle=collapse]").removeClass("collapsed")};SingAppView.prototype._sidebarMouseEnter=function(){if(Sing.isScreen("md")||Sing.isScreen("lg")){this.expandNavigation()}};SingAppView.prototype._sidebarMouseLeave=function(){if(Sing.isScreen("md")||Sing.isScreen("lg")){this.collapseNavigation()}};SingAppView.prototype._collapseNavIfSmallScreen=function(){if(Sing.isScreen("xs")||Sing.isScreen("sm")){this.collapseNavigation()}};SingAppView.prototype.toggleNavigationState=function(){if(this.isNavigationStatic()){this.collapsingNavigationState()}else{this.staticNavigationState()}$(window).trigger("sing-app:content-resize")};SingAppView.prototype.staticNavigationState=function(){this.settings.set("nav-static",true).save();$("body").addClass("nav-static")};SingAppView.prototype.collapsingNavigationState=function(){this.settings.set("nav-static",false).save();$("body").removeClass("nav-static");this.collapseNavigation()};SingAppView.prototype.isNavigationStatic=function(){return this.settings.get("nav-static")===true};SingAppView.prototype._changeActiveNavigationItem=function(event,xhr,options){var $newActiveLink=this.$sidebar.find('a[href*="'+this.extractPageName(options.url)+'"]').filter(function(){return this.href===options.url});if(!$newActiveLink.is(".active > .collapse > li > a")){this.$sidebar.find(".active .active").closest(".collapse").collapse("hide")}this.$sidebar.find(".active").removeClass("active");$newActiveLink.closest("li").addClass("active").parents("li").addClass("active")};SingAppView.prototype._contentSwipeLeft=function(){if(Sing.isScreen("lg"))return;if(!$("body").is(".nav-collapsed")){this.collapseNavigation()}};SingAppView.prototype._contentSwipeRight=function(){if(Sing.isScreen("lg"))return;if($("body").is(".chat-sidebar-closing"))return;if($("body").is(".nav-collapsed")){this.expandNavigation()}};SingAppView.prototype.showLoader=function(){var view=this;this.showLoaderTimeout=setTimeout(function(){view.$loaderWrap.removeClass("hide");setTimeout(function(){view.$loaderWrap.removeClass("hiding")},0)},200)};SingAppView.prototype.hideLoader=function(){clearTimeout(this.showLoaderTimeout);this.$loaderWrap.addClass("hiding");var view=this;this.$loaderWrap.one($.support.transition.end,function(){view.$loaderWrap.addClass("hide")}).emulateTransitionEnd(200)};SingAppView.prototype.onResize=function(fn,allPages){allPages=typeof allPages!=="undefined"?allPages:false;if(allPages){this.resizeCallbacks.push(fn)}else{this._addPageCallback(this.pageResizeCallbacks,fn)}};SingAppView.prototype.onPageLoad=function(fn){this._addPageCallback(this.pageLoadCallbacks,fn)};SingAppView.prototype.onScreenSize=function(size,fn,onEnter){onEnter=typeof onEnter!=="undefined"?onEnter:true;this.screenSizeCallbacks[size][onEnter?"enter":"exit"].push(fn)};SingAppView.prototype.pageLoaded=function(){this._runPageCallbacks(this.pageLoadCallbacks)};SingAppView.prototype._addPageCallback=function(callbacks,fn){var pageName=this.extractPageName(location.href);if(!callbacks[pageName]){callbacks[pageName]=[]}callbacks[pageName].push(fn)};SingAppView.prototype._runPageCallbacks=function(callbacks){var pageName=this.extractPageName(location.href);if(callbacks[pageName]){callbacks[pageName].forEach(function(fn){fn()})}};SingAppView.prototype._loadScripts=function(event,data,status,xhr,options){var $bodyContents=$($.parseHTML(data.match(/<body[^>]*>([\s\S.]*)<\/body>/i)[0],document,true)),$scripts=$bodyContents.filter("script[src]").add($bodyContents.find("script[src]")),$templates=$bodyContents.filter('script[type="text/template"]').add($bodyContents.find('script[type="text/template"]')),$existingScripts=$("script[src]"),$existingTemplates=$('script[type="text/template"]');$templates.each(function(){var id=this.id;var matchedTemplates=$existingTemplates.filter(function(){return this.id===id});if(matchedTemplates.length)return;var script=document.createElement("script");script.id=$(this).attr("id");script.type=$(this).attr("type");script.innerHTML=this.innerHTML;document.body.appendChild(script)});var $previous={load:function(fn){fn()}};$scripts.each(function(){var src=this.src;var matchedScripts=$existingScripts.filter(function(){return this.src===src});if(matchedScripts.length)return;var script=document.createElement("script");script.src=$(this).attr("src");$previous.load(function(){document.body.appendChild(script)});$previous=$(script)});var view=this;$previous.load(function(){$(document).trigger("sing-app:loaded");view.log("scripts loaded.")})};SingAppView.prototype.extractPageName=function(url){var pageName=url.split("#")[0].substring(url.lastIndexOf("/")+1).split("?")[0];return pageName===""?"index.html":pageName};SingAppView.prototype._checkLoading=function(e){var oldLoading=this.loading;this.loading=true;if(oldLoading){this.log("attempt to load page while already loading; preventing.");e.preventDefault()}else{this.log(e.currentTarget.href+" loading started.")}return!oldLoading};SingAppView.prototype._loadingFinished=function(){this.loading=false};SingAppView.prototype._logErrors=function(){var errors=JSON.parse(localStorage.getItem("lb-errors"))||{};errors[(new Date).getTime()]=arguments;localStorage.setItem("sing-errors",JSON.stringify(errors));this.debug&&alert("check errors")};SingAppView.prototype.log=function(message){if(this.debug){console.log("SingApp: "+message+" - "+arguments.callee.caller.toString().slice(0,30).split("\n")[0]+" - "+this.extractPageName(location.href))}};window.SingApp=new SingAppView;initAppPlugins();initAppFunctions();initAppFixes();initDemoFunctions()});function initAppPlugins(){!function($){$.fn.transparentGroupFocus=function(){return this.each(function(){$(this).find(".input-group-addon + .form-control").on("blur focus",function(e){$(this).parents(".input-group")[e.type=="focus"?"addClass":"removeClass"]("focus")})})};$(".input-group-transparent, .input-group-no-border").transparentGroupFocus()}(jQuery);!function($){$(document).on("click change","[data-ajax-load], [data-ajax-trigger^=change]",function(e){var $this=$(this),$target=$($this.data("ajax-target"));if($target.length>0){e=$.Event("ajax-load:start",{originalEvent:e});$this.trigger(e);!e.isDefaultPrevented()&&$target.load($this.data("ajax-load"),function(){$this.trigger("ajax-load:end")})}return false});$(document).on("click","[data-toggle^=button]",function(e){return $(e.target).find("input").data("ajax-trigger")!="change"})}(jQuery);!function($){$(document).on("click","table th [data-check-all]",function(){$(this).closest("table").find("input[type=checkbox]").not(this).prop("checked",$(this).prop("checked"))})}(jQuery);!function($){$.fn.animateProgressBar=function(){return this.each(function(){var $bar=$(this).find(".progress-bar");setTimeout(function(){$bar.css("width",$bar.data("width"))},0)})};$(".js-progress-animate").animateProgressBar()}(jQuery)}function initAppFunctions(){!function($){var $loadNotificationsBtn=$("#load-notifications-btn");$loadNotificationsBtn.on("ajax-load:start",function(e){$loadNotificationsBtn.button("loading")});$loadNotificationsBtn.on("ajax-load:end",function(){$loadNotificationsBtn.button("reset")});function moveNotificationsDropdown(){$(".sidebar-status .dropdown-toggle").after($("#notifications-dropdown-menu").detach())}function moveBackNotificationsDropdown(){$("#notifications-dropdown-toggle").after($("#notifications-dropdown-menu").detach())}SingApp.onScreenSize("xs",moveNotificationsDropdown);SingApp.onScreenSize("xs",moveBackNotificationsDropdown,false);Sing.isScreen("xs")&&moveNotificationsDropdown();$(".sidebar-status").on("show.bs.dropdown",function(){$("#sidebar").css("z-index",2)}).on("hidden.bs.dropdown",function(){$("#sidebar").css("z-index","")});$("#nav-state-toggle, #nav-collapse-toggle").tooltip();function initSidebarScroll(){var $sidebarContent=$(".js-sidebar-content");if($("#sidebar").find(".slimScrollDiv").length!=0){$sidebarContent.slimscroll({destroy:true})}$sidebarContent.slimscroll({height:window.innerHeight,size:"4px"})}SingApp.onResize(initSidebarScroll,true);initSidebarScroll();$(document).on("close.widgster",function(e){var $colWrap=$(e.target).closest('.content > .row > [class*="col-"]:not(.widget-container)');if(!$colWrap.find(".widget").not(e.target).length){$colWrap.remove()}})}(jQuery);!function($){var $chatContainer=$("body").addClass("chat-sidebar-container");$(document).on("click","[data-toggle=chat-sidebar]",function(){$chatContainer.toggleClass("chat-sidebar-opened");$(this).find(".chat-notification-sing").remove()});$("#content").on("swipeLeft",function(e){console.log(arguments);if($("body").is(".nav-collapsed")){$chatContainer.addClass("chat-sidebar-opened")}}).on("swipeRight",function(e){if($("body").is(".nav-collapsed.chat-sidebar-opened")){$chatContainer.removeClass("chat-sidebar-opened").addClass("chat-sidebar-closing").one($.support.transition.end,function(){$("body").removeClass("chat-sidebar-closing")}).emulateTransitionEnd(300)}});$(document).on("click",".chat-sidebar-user-group > a",function(){var $this=$(this),$target=$($this.attr("href")),$targetTitle=$target.find(".title");$this.removeClass("active").find(".badge").remove();$target.addClass("open");$(".chat-sidebar-contacts").removeClass("open");$(".chat-sidebar-footer").addClass("open");$(".message-list",$target).slimscroll({height:$target.height()-$targetTitle.height()-parseInt($targetTitle.css("margin-top"))-parseInt($targetTitle.css("margin-bottom")),width:"",size:"4px"});return false});$(document).on("click",".chat-sidebar-chat .js-back",function(){var $chat=$(this).closest(".chat-sidebar-chat").removeClass("open");var $sidebarContacts=$(".chat-sidebar-contacts").addClass("open");$(".chat-sidebar-footer").removeClass("open");return false});$("#chat-sidebar-input").keyup(function(e){if(e.keyCode!=13)return;var val;if((val=$(this).val().trim())=="")return;var $currentMessageList=$(".chat-sidebar-chat.open .message-list"),$message=$('<li class="message from-me">'+'<span class="thumb-sm"><img class="img-circle" src="img/avatar.png" alt="..."></span>'+'<div class="message-body"></div>'+"</li>");$message.appendTo($currentMessageList).find(".message-body").text(val);$(this).val("")});$("#chat-sidebar-search").keyup(function(){var $contacts=$(".chat-sidebar-contacts.open"),$chat=$(".chat-sidebar-chat.open"),val=$(this).val().trim().toUpperCase();if($contacts.length){$(".chat-sidebar-user-group .list-group-item").addClass("hide").filter(function(){return val==""?true:$(this).find(".message-sender").text().toUpperCase().indexOf(val)!=-1}).removeClass("hide")}if($chat.length){$(".chat-sidebar-chat.open .message-list .message").addClass("hide").filter(function(){return val==""?true:$(this).find(".message-body").text().toUpperCase().indexOf(val)!=-1}).removeClass("hide")}});function initChatSidebarScroll(){var $sidebarContent=$(".chat-sidebar-contacts");if($("#chat").find(".slimScrollDiv").length!=0){$sidebarContent.slimscroll({destroy:true})}$sidebarContent.slimscroll({height:window.innerHeight,width:"",size:"4px"})}SingApp.onResize(initChatSidebarScroll,true);initChatSidebarScroll()}(jQuery)}function initAppFixes(){var isWebkit="WebkitAppearance"in document.documentElement.style;if(isWebkit){}}function initDemoFunctions(){!function($){$("#load-notifications-btn").on("ajax-load:end",function(){setTimeout(function(){$("#notifications-list").find(".bg-attention").removeClass("bg-attention")},1e4)});$("#notifications-toggle").find("input").on("ajax-load:end",function(){$("#notifications-list").find("[data-toggle=tooltip]").tooltip()});$('[data-toggle="chat-sidebar"]').one("click",function(){setTimeout(function(){$(".chat-sidebar-user-group:first-of-type .list-group-item:first-child").addClass("active").find(".fa-circle").after('<span class="badge badge-danger pull-right animated bounceInDown">3</span>')},1e3)});setTimeout(function(){var $chatNotification=$("#chat-notification");$chatNotification.removeClass("hide").addClass("animated fadeIn").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",function(){$chatNotification.removeClass("animated fadeIn");setTimeout(function(){$chatNotification.addClass("animated fadeOut").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend",function(){$chatNotification.addClass("hide")})},4e3)});$chatNotification.siblings('[data-toggle="chat-sidebar"]').append('<i class="chat-notification-sing animated bounceIn"></i>')},4e3)}(jQuery)}