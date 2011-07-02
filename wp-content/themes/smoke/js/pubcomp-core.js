jQuery(function($){
    if (typeof(Pubcomp) == 'undefined') {
        var Pubcomp = {};
    }
    
    if (typeof(Pubcomp.FormUtil) == 'undefined') {
        Pubcomp.FormUtil = {};
    }

    if (typeof(Pubcomp.Growl) == 'undefined') {
        Pubcomp.Growl = {};
    }

    Pubcomp.FormUtil.showError = function(field_ele, message, style) {
        if (typeof(style) == "undefined") {
            style="tooltip-error";
        } else {
            style = "tooltip-"+style;
        }
        for (var i =1; i<1000; i++) {
            if ($("#field__error_"+i).length == 0) {
                var error_id = 'field__error_'+i
                break;
            }
        }
    
        $('<div class="'+style+' tooltip form_error" id="'+error_id+'">'+message+'</div>').insertAfter($(field_ele))
        $("#"+error_id).position({ of: $(field_ele), my: "left center", at: "right center", offset: "15 0"});
        $(field_ele).focus(function() { 
            $("#"+error_id).remove();
            $(this).unbind("focus", false);
        });
    }
    Pubcomp.Growl.init = function () {
        $(document).ready(function() {
            $.extend($.gritter.options, { 
                fade_in_speed: 100, // how fast notifications fade in (string or int)
                fade_out_speed: 100, // how fast the notices fade out
                time: 5000 // hang on the screen for...
            });
        });
    }

    Pubcomp.Growl.message = function(title, text, type) {
        var img = '/pubcomp/images'
        if (typeof(Pubcomp.Growl.image_dir) != "undefined") {
            img = Pubcomp.Growl.image_dir;
        }
        if (typeof(type) == "undefined" || type == "info") {
            img += 'message-info.png';
        } else if (type == "error") {
            img +='message-error.png'; 
        } else if (type == "success") {
            img +='message-success.png';
        } else if (type == "warning") {
            img +='message-warning.png';
        }     
        
        $.gritter.add({"title": title, "text":text, "image": img});
        return false;
    }

    Pubcomp.Growl.clear = function() {
        if (typeof($.gritter) != 'undefined') {
            $.gritter.removeAll();
        }
        return false;
    }

    Pubcomp.Growl.init();
});
