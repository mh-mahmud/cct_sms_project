var commonLib = function(){
    
    return{
        // https://github.com/ankurk91/vue-bootstrap-datetimepicker#readme
        dateTimePicker : function(refClass){
            jQuery(refClass).datetimepicker({
                format:'Y-MM-DD HH:mm',
            });
        },
        // https://github.com/CodeSeven/toastr
        iniToastrNotification : function(type, title, msg) {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "positionClass": "toast-top-right",
                "onclick": null,
                "progressBar" : true,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "9500",
                "extendedTimeOut": "5000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            if (type == "warning") {
                toastr.warning(msg, title);
            } else if (type == "error") {
                toastr.error(msg, title);
            } else if (type == "success") {
                toastr.success(msg, title);
            } else {
                toastr.info(msg, title);
            }
        },
        blockUI: function(options) {
            var getGlobalImgPath = BASE_URL + 'assets/app/media/img/';
            options = $.extend(true, {}, options);
            var html = '';
            if (options.animate) { 
                html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '">' + '<div class="block-spinner-bar"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>' + '</div>';
            } else if (options.iconOnly) { 
                html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="' + getGlobalImgPath + 'spinner/loading-spinner-grey.gif" align=""></div>';
            } else if (options.textOnly) {
                html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><span>&nbsp;&nbsp;' + (options.message ? options.message : 'LOADING...') + '</span></div>';
            } else {
                html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="' + getGlobalImgPath + 'spinner/loading-spinner-grey.gif" align=""><span>&nbsp;&nbsp;' + (options.message ? options.message : 'LOADING...') + '</span></div>';
            }

            if (options.target) { // element blocking                
                var el = $(options.target);
                if (el.height() <= ($(window).height())) {
                    options.cenrerY = true;
                }                
                el.block({
                    message: html,
                    baseZ: options.zIndex ? options.zIndex : 1000,
                    centerY: options.cenrerY !== undefined ? options.cenrerY : false,
                    css: {
                        top: options.top ? options.top : '10%',
                        border: '0',
                        padding: '0',
                        backgroundColor: 'none'
                    },
                    overlayCSS: {
                        backgroundColor: options.overlayColor ? options.overlayColor : '#555',
                        opacity: options.boxed ? 0.05 : 0.1,
                        cursor: 'wait'
                    }
                });
            } else { // page blocking
                $.blockUI({
                    message: html,
                    baseZ: options.zIndex ? options.zIndex : 1000,
                    css: {
                        border: '0',
                        padding: '0',
                        backgroundColor: 'none'
                    },
                    overlayCSS: {
                        backgroundColor: options.overlayColor ? options.overlayColor : '#555',
                        opacity: options.boxed ? 0.05 : 0.1,
                        cursor: 'wait'
                    }
                });
            }
        },

        // Apper function to  un-block element(finish loading)
        unblockUI: function(target) {
            if (target) {
                $(target).unblock({
                    onUnblock: function() {
                        $(target).css('position', '');
                        $(target).css('zoom', '');
                    }
                });
            } else {
                $.unblockUI();
            }
        },
        select2:function(refClass){
            $(refClass).select2({
                theme: "bootstrap"
            });
        },
        closeBootstrapModal: function(refClass){
            $(refClass).modal('hide');
            $('.modal-backdrop').remove();
        }

        
    };


}();