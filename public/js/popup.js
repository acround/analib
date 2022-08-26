var __opacoDiv = '<div id="__opaco" style="display:none;"></div>';
//var __popupDiv = '<div id="__popup" style="display:none;"><div class="topOfWindow"><div class="topLeftOfWindow"></div><div class="topRightOfWindow"></div></div><div class="mainRowOfWindow"><div class="mainRowTop"><div style="float: right;"><p class="popupHeader"><span class="closeButton" onclick="closePopup();"></span></p></div></div><div class="popupWorkPlace"><div class="popupBody"></div></div></div><div class="rightOfWindow"></div><div class="bottomOfWindow"><div class="bottomLeftOfWindow"></div><div class="bottomRightOfWindow"></div></div></div>';
        var __popupDiv = '<div id="__popup" style="display:none;"><div class="topOfWindow"><div class="topLeftOfWindow"></div><div class="topRightOfWindow"></div></div><div class="leftOfWindow"></div><div class="mainRowOfWindow"><div class="mainRowTop"><div style="float: right;"><p class="popupHeader"><span class="closeButton" onclick="closePopup();"></span></p></div></div><div class="popupWorkPlace"><div class="popupBody"></div></div></div><div class="rightOfWindow"></div><div class="bottomOfWindow"><div class="bottomLeftOfWindow"></div><div class="bottomRightOfWindow"></div></div></div>';
//var __popupBig = '<div id="__bigPopup" style="display:none;"><div class="topOfWindow"><div class="topLeftOfWindow"></div><div class="topRightOfWindow"></div></div><div class="mainRowOfWindow"><div class="mainRowTop"><div style="float: right;"><p class="popupHeader"><span class="closeButton" onclick="closePopup();"></span></p></div></div><div class="popupWorkPlace"><div class="popupBody"></div></div></div><div class="rightOfWindow"></div><div class="bottomOfWindow"><div class="bottomLeftOfWindow"></div><div class="bottomRightOfWindow"></div></div></div>';
        var __popupBig = '<div id="__bigPopup" style="display:none;"><div class="topOfWindow"><div class="topLeftOfWindow"></div><div class="topRightOfWindow"></div></div><div class="leftOfWindow"></div><div class="mainRowOfWindow"><div class="mainRowTop"><div style="float: right;"><p class="popupHeader"><span class="closeButton" onclick="closePopup();"></span></p></div></div><div class="popupWorkPlace"><div class="popupBody"></div></div></div><div class="rightOfWindow"></div><div class="bottomOfWindow"><div class="bottomLeftOfWindow"></div><div class="bottomRightOfWindow"></div></div></div>';
        var __popupContainer = null;
        var __popupAnimationSpeed = 'fast';
        var __popupQueue = new Array();
        function alignCenterMiddle(el) {
        //get margin left
        var marginLeft = Math.max(40, parseInt($(window).width() / 2 - $(el).width() / 2)) + 'px';
                //get margin top
                var marginTop = Math.max(40, parseInt($(window).height() / 2 - $(el).height() / 2)) + 'px';
                //return updated element
                return $(el).css({'margin-left': marginLeft, 'margin-top': marginTop});
        }
;
        function getPopupContainer() {
        return __popupContainer;
        }

function __popupResize() {
$('#__opaco').height(body.height()).width(body.width());
        alignCenterMiddle(__popupContainer);
}

function __savePopup() {
if (__popupContainer) {
var inputs = {};
        var textareas = {};
        var selects = {};
        var name;
        $('input[type=text], input[type=password], input[type=hidden]', __popupContainer).each(function (ndx, el) {
name = $(el).attr('name');
        if (name) {
inputs[name] = $(el).val();
        }
});
        $('textarea', __popupContainer).each(function (ndx, el) {
name = $(el).attr('name');
        if (name) {
textareas[name] = $(el).val();
        }
});
        $('select', __popupContainer).each(function (ndx, el) {
name = $(el).attr('name');
        if (name) {
selects[name] = $(el).val();
        }
});
        var save = {
        html: $(__popupContainer).html(),
                inputs: inputs,
                textareas: textareas,
                selects: selects,
                params: __popupContainer.params,
                width: __popupContainer.width(),
                height: __popupContainer.height()
        }
__popupQueue.push(save);
        }
return false;
}

function __restorePopup() {
if (__popupQueue.length == 0)
        return;
        if (typeof (__popupContainer.params.onClose) == 'function') {
__popupContainer.onClose();
        }
var afterClose;
        if (typeof (__popupContainer.afterClose) == 'function') {
afterClose = __popupContainer.afterClose;
        } else {
afterClose = null;
        }
__popupContainer.hide(__popupAnimationSpeed);
        var save = __popupQueue.pop();
        $(__popupContainer).html(save.html);
        for (name in save.inputs) {
$('input[name="' + name + '"]', __popupContainer).val(save.inputs[name]);
        }
for (name in save.textareas) {
$('textarea[name="' + name + '"]', __popupContainer).val(save.textareas[name]);
        }
for (name in save.selects) {
$('select[name="' + name + '"]', __popupContainer).val(save.selects[name]);
        }
var params = save.params;
        __popupContainer.params = params;
        __popupContainer.width(save.width);
        __popupContainer.height(save.height);
        if (typeof (params.onPopup) == 'function') {
params.onPopup(__popupContainer, params);
        }
if (typeof (params.onClose) == 'function') {
__popupContainer.onClose = params.onClose;
        } else {
__popupContainer.onClose = null;
        }
alignCenterMiddle(__popupContainer);
        if (typeof (__popupContainer.params.onClose) == 'function') {
__popupContainer.onClose();
        }
if (afterClose) {
afterClose();
        }
__popupContainer.show(__popupAnimationSpeed);
        return false;
}

function __closePopup() {
if (__popupContainer) {
$('#__opaco')
        .removeAttr('style')
        .fadeTo(__popupAnimationSpeed, 0)
        .hide()
        .addClass('hidden')
        ;
        __popupContainer.hide(__popupAnimationSpeed);
        if (typeof (__popupContainer.onClose) == 'function') {
__popupContainer.onClose(__popupContainer);
        }
$(__popupContainer).find('.popupBody').empty();
        __popupContainer = null;
        $(window).unbind('resize', false);
        }
}

function __newPopup(params) {
if (params.big) {
__popupContainer = $('#__bigPopup');
        } else {
__popupContainer = $('#__popup');
        }

if (__popupContainer.length == 0) {
$('body').prepend(__opacoDiv + __popupDiv + __popupBig);
//		$('#__opaco').click(function(){
//			closePopup();
//		});
        if (params.big) {
__popupContainer = $('#__bigPopup');
        } else {
__popupContainer = $('#__popup');
        }
}
__popupContainer.params = params;
        params.container.show();
        if ($(params.container).length) {
__popupContainer.
        width($(params.container).get(0).offsetWidth + 165).
        height($(params.container).get(0).offsetHeight + 180);
        params.container.hide();
        $(__popupContainer).find('.popupBody').html($(params.container).html());
        if (typeof (params.noClear) != 'undefined') {
$(params.container).empty();
        }
}
$('#__opaco')
        .height($(document).outerHeight())
        .width($(document).outerWidth())
        .removeClass('hidden')
        .fadeTo(__popupAnimationSpeed, 0.7);
        alignCenterMiddle(__popupContainer);
        $(window).resize(function () {
$('#__opaco').
        height(window.screen.height).
        width(window.screen.width);
        alignCenterMiddle(__popupContainer);
        });
        $(window).scroll(function () {
alignCenterMiddle(__popupContainer);
        });
        __popupContainer.show(__popupAnimationSpeed);
        if (typeof (params.onPopup) == 'function') {
params.onPopup(__popupContainer, params);
        }
if (typeof (params.onClose) == 'function') {
__popupContainer.onClose = params.onClose;
        } else {
__popupContainer.onClose = null;
        }
if (typeof (params.afterClose) == 'function') {
__popupContainer.afterClose = params.afterClose;
        } else {
__popupContainer.afterClose = null;
        }

return false;
}

function popup(params) {
if (__popupContainer) {
__savePopup();
        __closePopup();
        }
__newPopup(params);
        return false;
}

function closePopup() {
if (__popupQueue.length > 0) {
__restorePopup();
        } else {
__closePopup();
        }
return false;
}

function closeAllPopup() {
if (__popupQueue.length > 0) {
while (__popupQueue.length > 0) {
closePopup();
        }
if (getPopupContainer()) {
closePopup();
        }
}
}
