var App = function () {

     // IE mode
    var isRTL = false;
    var isIE8 = false;
    var isIE9 = false;
    var isIE10 = false;
    var isIE11 = false;

    var responsive = true;

    var responsiveHandlers = [];

    var homeBase = "";

    var windowState = "";

    var numberFormat = function(n) {
        return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    }

    var callAPI = function(url, params, async) {
        var req = $.ajax({
            async: (async == undefined ? true : async),
            url : homeBase + url,
            data : JSON.stringify(params),
            type : "post",
            dataType : 'json',
            contentType: false,
            processData: false,
            success : function(data){
                return data;
            },
            error : function() {
                return -1;
            }
        });

        var obj = {};
        defer = $.Deferred();
        defer.promise( obj );

        req.done(function(ret) {
            if (ret.err_code == 0)
                defer.resolve(ret);
            else
                defer.reject(ret);
        })
        .fail(function(ret) {
            ret = {
                err_code: -1,
                err_msg: "Cannot connect to server."
            }

            defer.reject(ret);
        });

        return defer;
    }

    // runs callback functions set by addResponsiveHandler().
    var _runResizeHandlers = function() {
        // reinitialize other subscribed elements
        for (var i = 0; i < resizeHandlers.length; i++) {
            var each = resizeHandlers[i];
            each.call();
        }
    };

    // handle the layout reinitialization on window resize
    var handleOnResize = function() {
        var resize;
        if (isIE8) {
            var currheight;
            $(window).resize(function() {
                if (currheight == document.documentElement.clientHeight) {
                    return; //quite event since only body resized not window.
                }
                if (resize) {
                    clearTimeout(resize);
                }
                resize = setTimeout(function() {
                    _runResizeHandlers();
                }, 50); // wait 50ms until window resize finishes.                
                currheight = document.documentElement.clientHeight; // store last body client height
            });
        } else {
            $(window).resize(function() {
                if (resize) {
                    clearTimeout(resize);
                }
                resize = setTimeout(function() {
                    _runResizeHandlers();
                }, 50); // wait 50ms until window resize finishes.
            });
        }
    };

    //public function to add callback a function which will be called on window resize
    var addResizeHandler = function(func) {
        resizeHandlers.push(func);
    }

    //public functon to call _runresizeHandlers
    var runResizeHandlers = function() {
        _runResizeHandlers();
    }

    var initSlimScroll = function(el) {
        $(el).each(function() {
            if ($(this).attr("data-initialized")) {
                return; // exit
            }

            var height;

            if ($(this).attr("data-height")) {
                height = $(this).attr("data-height");
            } else {
                height = $(this).css('height');
            }

            $(this).slimScroll({
                allowPageScroll: true, // allow page scroll when the element scroll is ended
                size: '7px',
                color: ($(this).attr("data-handle-color") ? $(this).attr("data-handle-color") : '#bbb'),
                wrapperClass: ($(this).attr("data-wrapper-class") ? $(this).attr("data-wrapper-class") : 'slimScrollDiv'),
                railColor: ($(this).attr("data-rail-color") ? $(this).attr("data-rail-color") : '#eaeaea'),
                position: isRTL ? 'left' : 'right',
                height: height,
                alwaysVisible: ($(this).attr("data-always-visible") == "1" ? true : false),
                railVisible: ($(this).attr("data-rail-visible") == "1" ? true : false),
                disableFadeOut: true
            });

            $(this).attr("data-initialized", "1");
        });
    }

    var destroySlimScroll = function(el) {
        $(el).each(function() {
            if ($(this).attr("data-initialized") === "1") { // destroy existing instance before updating the height
                $(this).removeAttr("data-initialized");
                $(this).removeAttr("style");

                var attrList = {};

                // store the custom attribures so later we will reassign.
                if ($(this).attr("data-handle-color")) {
                    attrList["data-handle-color"] = $(this).attr("data-handle-color");
                }
                if ($(this).attr("data-wrapper-class")) {
                    attrList["data-wrapper-class"] = $(this).attr("data-wrapper-class");
                }
                if ($(this).attr("data-rail-color")) {
                    attrList["data-rail-color"] = $(this).attr("data-rail-color");
                }
                if ($(this).attr("data-always-visible")) {
                    attrList["data-always-visible"] = $(this).attr("data-always-visible");
                }
                if ($(this).attr("data-rail-visible")) {
                    attrList["data-rail-visible"] = $(this).attr("data-rail-visible");
                }

                $(this).slimScroll({
                    wrapperClass: ($(this).attr("data-wrapper-class") ? $(this).attr("data-wrapper-class") : 'slimScrollDiv'),
                    destroy: true
                });

                var the = $(this);

                // reassign custom attributes
                $.each(attrList, function(key, value) {
                    the.attr(key, value);
                });

            }
        });
    }

    var handleInit = function() {

        if ($('body').css('direction') === 'rtl') {
            isRTL = true;
        }

        isIE8 = !! navigator.userAgent.match(/MSIE 8.0/);
        isIE9 = !! navigator.userAgent.match(/MSIE 9.0/);
        isIE10 = !! navigator.userAgent.match(/MSIE 10.0/);
        isIE11 = !! navigator.userAgent.match(/MSIE 11.0/);
        
        if (isIE10) {
            jQuery('html').addClass('ie10'); // detect IE10 version
        }
        if (isIE11) {
            jQuery('html').addClass('ie11'); // detect IE11 version
        }
    }

    // runs callback functions set by App.addResponsiveHandler().
    var runResponsiveHandlers = function () {
        // reinitialize other subscribed elements
        for (var i in responsiveHandlers) {
            var each = responsiveHandlers[i];
            each.call();
        }
    }

    // handle the layout reinitialization on window resize
    var handleResponsiveOnResize = function () {
        var resize;
        if (isIE8) {
            var currheight;
            $(window).resize(function () {
                if (currheight == document.documentElement.clientHeight) {
                    return; //quite event since only body resized not window.
                }
                if (resize) {
                    clearTimeout(resize);
                }
                resize = setTimeout(function () {
                    runResponsiveHandlers();
                }, 50); // wait 50ms until window resize finishes.                
                currheight = document.documentElement.clientHeight; // store last body client height
            });
        } else {
            $(window).resize(function () {
                if (resize) {
                    clearTimeout(resize);
                }
                resize = setTimeout(function () {
                    runResponsiveHandlers();
                }, 50); // wait 50ms until window resize finishes.
            });
        }
    }

    var handleIEFixes = function() {
        //fix html5 placeholder attribute for ie7 & ie8
        if (isIE8 || isIE9) { // ie8 & ie9
            // this is html5 placeholder fix for inputs, inputs with placeholder-no-fix class will be skipped(e.g: we need this for password fields)
            jQuery('input[placeholder]:not(.placeholder-no-fix), textarea[placeholder]:not(.placeholder-no-fix)').each(function () {

                var input = jQuery(this);

                if (input.val() == '' && input.attr("placeholder") != '') {
                    input.addClass("placeholder").val(input.attr('placeholder'));
                }

                input.focus(function () {
                    if (input.val() == input.attr('placeholder')) {
                        input.val('');
                    }
                });

                input.blur(function () {
                    if (input.val() == '' || input.val() == input.attr('placeholder')) {
                        input.val(input.attr('placeholder'));
                    }
                });
            });
        }
    }

    // Handles scrollable contents using jQuery SlimScroll plugin.
    var handleScrollers = function () {
        $('.scroller').each(function () {
            var height;
            if ($(this).attr("data-height")) {
                height = $(this).attr("data-height");
            } else {
                height = $(this).css('height');
            }
            $(this).slimScroll({
                allowPageScroll: true, // allow page scroll when the element scroll is ended
                size: '7px',
                color: ($(this).attr("data-handle-color")  ? $(this).attr("data-handle-color") : '#bbb'),
                railColor: ($(this).attr("data-rail-color")  ? $(this).attr("data-rail-color") : '#eaeaea'),
                position: isRTL ? 'left' : 'right',
                height: height,
                alwaysVisible: ($(this).attr("data-always-visible") == "1" ? true : false),
                railVisible: ($(this).attr("data-rail-visible") == "1" ? true : false),
                disableFadeOut: true
            });
        });
    }

    var handleSearch = function() {    
        $('.search-btn').click(function () {            
            if($('.search-btn').hasClass('show-search-icon')){
                if ($(window).width()>767) {
                    $('.search-box').fadeOut(300);
                } else {
                    $('.search-box').fadeOut(0);
                }
                $('.search-btn').removeClass('show-search-icon');
            } else {
                if ($(window).width()>767) {
                    $('.search-box').fadeIn(300);
                } else {
                    $('.search-box').fadeIn(0);
                }
                $('.search-btn').addClass('show-search-icon');
            } 
        }); 

        // close search box on body click
        if($('.search-btn').size() != 0) {
            $('.search-box, .search-btn').on('click', function(e){
                e.stopPropagation();
            });

            $('body').on('click', function() {
                if ($('.search-btn').hasClass('show-search-icon')) {
                    $('.search-btn').removeClass("show-search-icon");
                    $('.search-box').fadeOut(300);
                }
            });
        }
    }

    var handleMenu = function() {
        $(".header .navbar-toggle").click(function () {
            if ($(".header .navbar-collapse").hasClass("open")) {
                $(".header .navbar-collapse").slideDown(300)
                .removeClass("open");
            } else {             
                $(".header .navbar-collapse").slideDown(300)
                .addClass("open");
            }
        });
    }
    var handleSubMenuExt = function() {
        $(".header-navigation .dropdown").on("hover", function() {
            if ($(this).children(".header-navigation-content-ext").show()) {
                if ($(".header-navigation-content-ext").height()>=$(".header-navigation-description").height()) {
                    $(".header-navigation-description").css("height", $(".header-navigation-content-ext").height()+22);
                }
            }
            var cur_obj = $(this);
            $(document).find('.dropdown').each(function() {
                if (cur_obj !=$(this))
                    $(this).removeClass("open");
            });
            $('.search-box').hide();
        });
    }

    var handleSidebarMenu = function () {
        $(".sidebar .dropdown .expand-mark").click(function (event) {
            item = $(this).parent();
            event.preventDefault();
            if (item.hasClass("expanded") == false) {
                item.addClass("expanded");
                item.siblings(".dropdown-menu").slideDown(300);
                $(this).addClass("fa-folder-open").removeClass("fa-folder");
            } else {
                item.removeClass("expanded");
                item.siblings(".dropdown-menu").slideUp(300);
                $(this).removeClass("fa-folder-open").addClass("fa-folder");
            }
        });
    }    

    var handleBootstrapSwitch = function() {
        if (!$().bootstrapSwitch) {
            return;
        }
        $('.make-switch').bootstrapSwitch();
    };

    function handleDifInits() { 
        $(".header .navbar-toggle span:nth-child(2)").addClass("short-icon-bar");
        $(".header .navbar-toggle span:nth-child(4)").addClass("short-icon-bar");
    }

    function handleUniform() {
        if (!jQuery().uniform) {
            return;
        }
        var test = $("input[type=checkbox]:not(.toggle), input[type=radio]:not(.toggle, .star)");
        if (test.size() > 0) {
            test.each(function () {
                    if ($(this).parents(".checker").size() == 0) {
                        $(this).show();
                        $(this).uniform();
                    }
                });
        }
    }

    var handleFancybox = function () {
        if (!jQuery.fancybox) {
            return;
        }
        
        jQuery(".fancybox").each(function() {
            $this = $(this);
            width = $this.attr('fancy-width');
            switch(width) {
                case "max":
                    width = $(window).width() - 60;
                    break;
                case undefined:
                case '':
                    width = undefined;
                    break;
                default:
                    width = toInt(width);
                    break;
            }
            height = $this.attr('fancy-height');
            switch(height) {
                case "max":
                    height = $(window).height() - 160;
                    break;
                case undefined:
                case '':
                    height = undefined;
                    break;
                default:
                    height = toInt(height);
                    break;
            }
            $this.fancybox({
                'type' : 'iframe',
                'width' : width,
                'height' : height,
                'autoSize': false,
                'closeBtn': false,
                'afterClose': function () {
                    $this.trigger('fancyboxAfterClose');
                }
            });
        });
        
        jQuery(".fancybox-image").fancybox({
            prevEffect : 'none',
            nextEffect : 'none'
        });
        
        jQuery(".fancybox-fast-view").fancybox();

        if (jQuery(".fancybox-button").size() > 0) {            
            jQuery(".fancybox-button").fancybox({
                groupAttr: 'data-rel',
                prevEffect: 'none',
                nextEffect: 'none',
                closeBtn: true,
                helpers: {
                    title: {
                        type: 'inside'
                    }
                }
            });

            $('.fancybox-video').fancybox({
                type: 'iframe'
            });
        }
    }

    // Handles Bootstrap Accordions.
    var handleAccordions = function () {
       
        jQuery('body').on('shown.bs.collapse', '.accordion.scrollable', function (e) {
            App.scrollTo($(e.target), -100);
        });
        
    }

    // Handles Bootstrap Tabs.
    var handleTabs = function () {
        // fix content height on tab click
        /*
        $('body').on('shown.bs.tab', '.nav.nav-tabs', function () {
            handleSidebarAndContentHeight();
        });
        */

        //activate tab if tab id provided in the URL
        if (location.hash) {
            var tabid = location.hash.substr(1);
            $('a[href="#' + tabid + '"]').click();
        }

        $('.tabbable-hover .nav-tabs > li ').hover(function() {
            $(this).find('a').tab('show');
        });
    }

    var handleMobiToggler = function () {
        $(".mobi-toggler").on("click", function(event) {
            event.preventDefault();//the default action of the event will not be triggered
            
            $(".header").toggleClass("menuOpened");
            $(".header").find(".header-navigation").toggle(300);
        });
    }

    var handleTheme = function () {
    
        var panel = $('.color-panel');
    
        // handle theme colors
        var setColor = function (color) {
            $('#style-color').attr("href", "../../assets/frontend/layout/css/themes/" + color + ".css");
            $('.corporate .site-logo img').attr("src", "../../assets/frontend/layout/img/logos/logo-corp-" + color + ".png");
            $('.ecommerce .site-logo img').attr("src", "../../assets/frontend/layout/img/logos/logo-shop-" + color + ".png");
        }

        $('.icon-color', panel).click(function () {
            $('.color-mode').show();
            $('.icon-color-close').show();
        });

        $('.icon-color-close', panel).click(function () {
            $('.color-mode').hide();
            $('.icon-color-close').hide();
        });

        $('li', panel).click(function () {
            var color = $(this).attr("data-style");
            setColor(color);
            $('.inline li', panel).removeClass("current");
            $(this).addClass("current");
        });
    }

    //Set of functions to manage the animations of the elements
    var animateElements = function() {
        if($('.animate-if-visible').length) {
            $('.animate-if-visible').appear();
            $(document.body).on('appear', '.animate-if-visible', function(e, $affected) {
                // this code is executed for each appeared element
                var element = $(this);
                var animationOptions = element.data('animation-options');
                runAnimationTransition(element, animationOptions);
            });
        }
        if($('.animate-group').length) {
            $('.animate-group').appear();
            $(document.body).on('appear', '.animate-group', function(e, $affected) {
                var element = $(this);
                var animationInterval = 200;
                if( typeof $(this).data('animation-interval') !== 'undefined') {
                    animationInterval = parseInt($(this).data('animation-interval'));
                }
                var totalAnimations = 0;
                var elements = [];

                element.find('.animate').each(function(n) {
                    elements[n] = $(this);
                    totalAnimations++;
                });
                runAnimationGroup(elements, totalAnimations, 0, animationInterval);

            });
        }
        // Force appear. This is suitable in cases when page is in initial state (not scrolled and not resized)
        $.force_appear();
    };

    //Set all Animated Elements
    var runAnimationGroup = function(element, totalAnimations, actual, animationInterval) {
        if(actual < totalAnimations) {
            var animationOptions = element[actual].data('animation-options');
            setTimeout(function() {
                runAnimationTransition(element[actual], animationOptions);
                actual++;
                runAnimationGroup(element, totalAnimations, actual, animationInterval);
            }, animationInterval);
        }
    };
    var runElementsAnimation = function(element) {
        var animationOptions = element.data('animation-options');
        if( typeof animationOptions == 'undefined') {
            animationOptions = new Object;
            animationOptions.animation = "Fade";
        }
        switch (animationOptions.animation) {
            case 'scaleIn':
                element.css({
                    opacity: 0,
                    scale: 0.6
                });
                break;
            case 'scaleToBottom':

                original_height = element.height();

                element.data('original-height', original_height).data('original-width', element.width()).css({
                    opacity: 0,
                    transform: 'translateY(-' + original_height / 2 + 'px); scaleY(0.001)'
                });
                break;
            case 'scaleToRight':

                original_width = element.width();

                element.data('original-height', element.height()).data('original-width', original_width).css({
                    opacity: 0,
                    transform: 'translateX(-' + original_width / 2 + 'px); scaleX(0.001)'
                });
                break;
            case 'scaleToTop':

                original_height = element.height();

                element.data('original-height', original_height).data('original-width', element.width()).css({
                    opacity: 0,
                    transform: 'translateY(' + original_height / 2 + 'px); scaleY(0.001)'
                });
                break;
            case 'scaleToLeft':

                original_width = element.width();

                element.data('original-height', element.height()).data('original-width', original_width).css({
                    opacity: 0,
                    transform: 'translateX(' + original_width / 2 + 'px); scaleX(0.001)'
                });
                break;
            default :
                element.css({
                    opacity: 0
                });
                break;
        }
    };
    var runAnimateProgressBar = function() {
        if($('.animate-bar').length) {
            $('.animate-bar').appear();
            $(document.body).on('appear', '.animate-bar', function(e, $affected) {
                $(this).progressbar({
                    display_text: 'center',
                    use_percentage: true
                });
            });
        }
    };
    var runAnimationTransition = function(element, animationOptions) {
        if( typeof animationOptions == 'undefined') {
            animationOptions = new Object;
            animationOptions.animation = "fadeIn";
        }
        var animationType = animationOptions.animation;
        var animationDelay = animationOptions.delay;
        var animationDuration = animationOptions.duration;
        var animationEasing = animationOptions.easing;
        if( typeof animationType === 'undefined') {
            animationType = "fadeIn";
        }
        if( typeof animationDelay === 'undefined') {
            animationDelay = 0;
        }
        if( typeof animationDuration === 'undefined') {
            animationDuration = 300;
        }
        if( typeof animationEasing === 'undefined') {
            animationEasing = 'linear';
        }
        switch (animationType) {
            case 'scaleIn':
                element.transition({
                    opacity: 1,
                    scale: 1,
                    duration: animationDuration,
                    delay: animationDelay,
                    easing: animationEasing
                });
                break;
            case 'scaleToRight':
            case 'scaleToLeft':
                element.transition({
                    opacity: 1,
                    transform: 'scaleX(1)',
                    duration: animationDuration,
                    delay: animationDelay,
                    easing: animationEasing
                });
                break;
            case 'scaleToBottom':
            case 'scaleToTop':
                element.transition({
                    opacity: 1,
                    transform: 'scaleY(1)',
                    duration: animationDuration,
                    delay: animationDelay,
                    easing: animationEasing
                });
                break;
            default:

                animationDuration = animationDuration / 1000 * 2 + 's';
                animationDelay = animationDelay / 1000 * 2 + 's';
                element.css({
                    opacity: 1,
                    'animation-fill-mode': 'both',
                    'animation-duration': animationDuration,
                    'animation-delay': animationDelay,
                    'animation-name': animationType
                });
                break;
        }
    };

    var handleAnimations = function() {
        $('.animate-group').each(function() {
            $(this).find('.animate').each(function() {
                runElementsAnimation($(this));
            });
        });
        $('.animate-if-visible').each(function() {
            runElementsAnimation($(this));
        });
    };

    ////////////////////////////////////////
    // START ---------------- Handle Pickers
    ////////////////////////////////////////
    var handleDatePickers = function () {

        if (jQuery().datepicker) {
            $('.date-picker').datepicker({
                orientation: "right",
                autoclose: true
            });
            //$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }

        /* Workaround to restrict daterange past date select: http://stackoverflow.com/questions/11933173/how-to-restrict-the-selectable-date-ranges-in-bootstrap-datepicker */
    }

    var handleTimePickers = function () {

        if (jQuery().timepicker) {
            $('.timepicker-default').timepicker({
                autoclose: true,
                showSeconds: true,
                minuteStep: 1
            });

            $('.timepicker-no-seconds').timepicker({
                autoclose: true,
                minuteStep: 5
            });

            $('.timepicker-24').timepicker({
                autoclose: true,
                minuteStep: 5,
                showSeconds: false,
                showMeridian: false
            });

            // handle input group button click
            $('.timepicker').parent('.input-group').on('click', '.input-group-btn', function(e){
                e.preventDefault();
                $(this).parent('.input-group').find('.timepicker').timepicker('showWidget');
            });
        }
    }

    var handleDateRangePickers = function () {
        if (!jQuery().daterangepicker) {
            return;
        }

        $('#defaultrange').daterangepicker({
                format: 'MM/DD/YYYY',
                separator: ' to ',
                startDate: moment().subtract('days', 29),
                endDate: moment(),
                minDate: '01/01/2012',
                maxDate: '12/31/2018',
            },
            function (start, end) {
                $('#defaultrange input').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
        );        

        $('#defaultrange_modal').daterangepicker({
                opens: 'right',
                format: 'MM/DD/YYYY',
                separator: ' to ',
                startDate: moment().subtract('days', 29),
                endDate: moment()
            },
            function (start, end) {
                $('#defaultrange_modal input').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
        );  

        // this is very important fix when daterangepicker is used in modal. in modal when daterange picker is opened and mouse clicked anywhere bootstrap modal removes the modal-open class from the body element.
        // so the below code will fix this issue.
        $('#defaultrange_modal').on('click', function(){
            if ($('#daterangepicker_modal').is(":visible") && $('body').hasClass("modal-open") == false) {
                $('body').addClass("modal-open");
            }
        });

        $('#reportrange').daterangepicker({
                opens: 'right',
                startDate: moment().subtract('days', 29),
                endDate: moment(),
                minDate: '01/01/2012',
                dateLimit: {
                    days: 60
                },
                showDropdowns: true,
                showWeekNumbers: true,
                timePicker: false,
                timePickerIncrement: 1,
                timePicker12Hour: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                    'Last 7 Days': [moment().subtract('days', 6), moment()],
                    'Last 30 Days': [moment().subtract('days', 29), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                },
                buttonClasses: ['btn'],
                applyClass: 'green',
                cancelClass: 'default',
                format: 'MM/DD/YYYY',
                separator: ' to ',
                locale: {
                    applyLabel: 'Apply',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom Range',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    firstDay: 1
                }
            },
            function (start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
        );
        //Set the initial state of the picker label
        $('#reportrange span').html(moment().subtract('days', 29).format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
    }

    var handleDatetimePicker = function () {

        if (!jQuery().datetimepicker) {
            return;
        }

        $(".form_datetime").datetimepicker({
            autoclose: true,
            format: "dd MM yyyy - hh:ii",
            pickerPosition: "bottom-left"
        });

        $(".form_advance_datetime").datetimepicker({
            format: "dd MM yyyy - hh:ii",
            autoclose: true,
            todayBtn: true,
            startDate: "2013-02-14 10:00",
            pickerPosition: "bottom-left",
            minuteStep: 10
        });

        $(".form_meridian_datetime").datetimepicker({
            format: "dd MM yyyy - HH:ii P",
            showMeridian: true,
            autoclose: true,
            pickerPosition: "bottom-left",
            todayBtn: true
        });

        $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
    }
    ////////////////////////////////////////
    // Handle Pickers ------------------ END
    ////////////////////////////////////////

    var handlePrettify = function() {
        if (window.prettyPrint)
            window.prettyPrint();
    }

    var scrolltotop={
        //startline: Integer. Number of pixels from top of doc scrollbar is scrolled before showing control
        //scrollto: Keyword (Integer, or "Scroll_to_Element_ID"). How far to scroll document up when control is clicked on (0=top).
        setting: {startline:100, scrollto: 0, scrollduration:1000, fadeduration:[500, 100]},
        controlHTML: '<i class="icon icon-arrow-up btn-up"></i>', //HTML for control, which is auto wrapped in DIV w/ ID="topcontrol"
        controlattrs: {offsetx:10, offsety:10}, //offset of control relative to right/ bottom of window corner
        anchorkeyword: '#top', //Enter href value of HTML anchors on the page that should also act as "Scroll Up" links

        state: {isvisible:false, shouldvisible:false},

        scrollup:function(){
            if (!this.cssfixedsupport) //if control is positioned using JavaScript
                this.$control.css({opacity:0}) //hide control immediately after clicking it
            var dest=isNaN(this.setting.scrollto)? this.setting.scrollto : parseInt(this.setting.scrollto)
            if (typeof dest=="string" && jQuery('#'+dest).length==1) //check element set by string exists
                dest=jQuery('#'+dest).offset().top
            else
                dest=0
            this.$body.animate({scrollTop: dest}, this.setting.scrollduration);
        },

        keepfixed:function(){
            var $window=jQuery(window)
            var controlx=$window.scrollLeft() + $window.width() - this.$control.width() - this.controlattrs.offsetx
            var controly=$window.scrollTop() + $window.height() - this.$control.height() - this.controlattrs.offsety
            this.$control.css({left:controlx+'px', top:controly+'px'})
        },

        togglecontrol:function(){
            var scrolltop=jQuery(window).scrollTop()
            if (!this.cssfixedsupport)
                this.keepfixed()
            this.state.shouldvisible=(scrolltop>=this.setting.startline)? true : false
            if (this.state.shouldvisible && !this.state.isvisible){
                this.$control.stop().animate({opacity:1}, this.setting.fadeduration[0])
                this.state.isvisible=true
            }
            else if (this.state.shouldvisible==false && this.state.isvisible){
                this.$control.stop().animate({opacity:0}, this.setting.fadeduration[1])
                this.state.isvisible=false
            }
        },
        
        init:function(){
            var mainobj=scrolltotop
            var iebrws=document.all
            mainobj.cssfixedsupport=!iebrws || iebrws && document.compatMode=="CSS1Compat" && window.XMLHttpRequest //not IE or IE7+ browsers in standards mode
            mainobj.$body=(window.opera)? (document.compatMode=="CSS1Compat"? $('html') : $('body')) : $('html,body')
            mainobj.$control=$('<div id="topcontrol">'+mainobj.controlHTML+'</div>')
                .css({position:mainobj.cssfixedsupport? 'fixed' : 'absolute', bottom:mainobj.controlattrs.offsety, right:mainobj.controlattrs.offsetx, opacity:0, cursor:'pointer'})
                .attr({title:'Top'})
                .click(function(){mainobj.scrollup(); return false})
                .appendTo('body')
            if (document.all && !window.XMLHttpRequest && mainobj.$control.text()!='') //loose check for IE6 and below, plus whether control contains any text
                mainobj.$control.css({width:mainobj.$control.width()}) //IE6- seems to require an explicit width on a DIV containing text
            mainobj.togglecontrol()
            $('a[href="' + mainobj.anchorkeyword +'"]').click(function(){
                mainobj.scrollup()
                return false
            })
            $(window).bind('scroll resize', function(e){
                mainobj.togglecontrol()
            })
        }
    }

    var handleScrolltotop = function() {
        scrolltotop.init();
    }

    // js tree
    var handleSidebarMenu1 = function () {

        $('#tree_1').jstree({
            "core" : {
                "themes" : {
                    "responsive": false
                }            
            },
            "types" : {
                "default" : {
                    "icon" : "fa fa-folder icon-state-warning icon-lg"
                },
                "file" : {
                    "icon" : "fa fa-file icon-state-warning icon-lg"
                }
            },
            "plugins": ["types"]
        });

        // handle link clicks in tree nodes(support target="_blank" as well)
        $('#tree_1').on('select_node.jstree', function(e,data) { 
            var link = $('#' + data.selected).find('a');
            if (link.attr("href") != "#" && link.attr("href") != "javascript:;" && link.attr("href") != "") {
                if (link.attr("target") == "_blank") {
                    link.attr("href").target = "_blank";
                }
                document.location.href = link.attr("href");
                return false;
            }
        });
    }

    var initPagebar = function(id, counts, page, size, callback_page) {
        html = "";
        bar_size = 10;
        size = size || 10;
        pages = Math.ceil(counts / size);

        if (page >= pages && page > 1)
            page = 0;

        if (pages > 1) {
            curp = page;
            sp = Math.floor(curp / bar_size) * bar_size;
            ep = sp + bar_size - 1;
            if (ep >= pages)
                ep = pages - 1;
        }

        html += '<ul class="pagination">';
        if (pages > 1) {
            if (curp > 0) {
                html += '<li><a href="javascript:;" page="' + (curp - 1) + '">Prev</a></li>';
            }
            if (sp > 0) {
                html += '<li><a href="javascript:;" page="' + (sp - 1) + '">...</a></li>';
            }
            for (p = sp; p <= ep; p ++) 
            {
                html += '<li class="' + (p == curp ? "active" : "") + '">';
                html += '<a href="javascript:;" page="' + p + '">' + numberFormat(p + 1) + '</a>';
                html += '</li>';
            }
            if (ep < pages - 1) {
                html += '<li><a href="javascript:;" page="' + (ep + 1) + '">...</a></li>';
            }
            if (curp < pages - 1) {
                html += '<li><a href="javascript:;" page="' + (curp + 1) + '">Next</a></li>';
            }
        }

        if (counts > 0) {
            html += '<li class="counts">전체 <em>' + numberFormat(counts) + '</em> 건</li>';
        }
        html += '</ul>';

        $('#' + id).html(html);

        $('#' + id + ' li a').click(function() {
            if (callback_page)
                callback_page($(this).attr('page'));
        });
    }

    var handleAlarm = function () {
        if (windowState == 'visible') {
            // in case of activate window
            callAPI("api/alarm/get", null)
            .done(function(res) {
                if (res.err_code == 0) {
                    alarms = res.alarms;

                    if (alarms) {
                        for(i = 0; i < alarms.length; i ++) {
                            al = alarms[i];
                            alertBox(al.title, al.message, null, 4000);
                        }
                    }
                }

                setTimeout(function() {
                    handleAlarm();
                }, 5000); 
            })
            .fail(function(res) {
                setTimeout(function() {
                    handleAlarm();
                }, 30000); 
            });
        }
        else {
            setTimeout(function() {
                handleAlarm();
            }, 1000);
        }
    }

    var hidden = "hidden";
    var onChangeWindowState = function(evt) {
        v = "visible";
        h = "hidden";
        evtMap = {
            focus: v,
            focusin: v,
            pageshow: v,
            blur: h,
            focusout: h,
            pagehide: h
        }

        evt = (evt || window.event);

        visible = "";

        if (evt.type in evtMap)
            visible = evtMap[evt.type];
        else
            visible = (document[hidden] ? "hidden" : "visible");

        windowState = visible;
        return   
    }

    var handleChangeWindowState = function() {
        if (hidden in document)
            document.addEventListener("visibilitychange", onChangeWindowState);
        else if ((hidden = "mozHidden") in document)
            document.addEventListener("mozvisibilitychange", onChangeWindowState);
        else if ((hidden = "webkitHidden") in document)
            document.addEventListener("webkitvisibilitychange", onChangeWindowState);
        else if ((hidden = "msHidden") in document)
            document.addEventListener("msvisibilitychange", onChangeWindowState);
        else if ("onfocusin" in document)
            document.onfocusin = document.onfocusout = onChangeWindowState;
        else
            window.onpageshow = window.onpagehide = window.onfocus = window.onblur = onChangeWindowState;

        if (document[hidden] !== `undefined`)
            onChangeWindowState({type: (document[hidden] ? "blur" : "focus")});   
    }

    return {
        init: function () {
            handleChangeWindowState();

            // init core variables
            handleTheme();
            handleInit();
            handleResponsiveOnResize();
            handleIEFixes();
            handleSearch();
            handleFancybox();
            handleDifInits();
            handleSidebarMenu();
            handleAccordions();
            handleMenu();
            handleScrollers();
            handleSubMenuExt();
            handleMobiToggler();
            animateElements();
            handleAnimations();

            handleTabs();

            // picker
            handleDatePickers();
            handleTimePickers();
            handleDatetimePicker();
            handleDateRangePickers();

            handleBootstrapSwitch();
            handlePrettify();

            handleScrolltotop();
        },

        initAlarm: function() {
            handleAlarm();
        },

        initTouchspin: function () {
            $(".product-quantity .form-control").TouchSpin({
                buttondown_class: "btn quantity-down",
                buttonup_class: "btn quantity-up"
            });
            $(".quantity-down").html("<i class='fa fa-angle-down'></i>");
            $(".quantity-up").html("<i class='fa fa-angle-up'></i>");
        },

        initFixHeaderWithPreHeader: function () {
            jQuery(window).scroll(function() {                
                if (jQuery(window).scrollTop()>37){
                    jQuery("body").addClass("page-header-fixed");
                }
                else {
                    jQuery("body").removeClass("page-header-fixed");
                }
            });
        },

        initNavScrolling: function () {
            function NavScrolling () {
                if (jQuery(window).scrollTop()>60){
                    jQuery(".header").addClass("reduce-header");
                }
                else {
                    jQuery(".header").removeClass("reduce-header");
                }
            }
            
            NavScrolling();
            
            jQuery(window).scroll(function() {
                NavScrolling ();
            });
        },

        initOWL: function () {
            $(".owl-carousel2-brands").owlCarousel({
                pagination: false,
                navigation: true,
                items: 2,
                addClassActive: true,
                itemsCustom : [
                    [0, 1],
                    [320, 1],
                    [800, 2]
                ],
            });

            $(".owl-carousel5").owlCarousel({
                pagination: false,
                navigation: true,
                items: 5,
                addClassActive: true,
                itemsCustom : [
                    [0, 1],
                    [320, 1],
                    [480, 2],
                    [660, 2],
                    [700, 3],
                    [768, 3],
                    [992, 4],
                    [1024, 4],
                    [1200, 5],
                    [1400, 5],
                    [1600, 5]
                ],
            });

            $(".owl-carousel4").owlCarousel({
                pagination: false,
                navigation: true,
                items: 4,
                addClassActive: true,
            });

            $(".owl-carousel3").owlCarousel({
                pagination: false,
                navigation: true,
                items: 3,
                addClassActive: true,
                itemsCustom : [
                    [0, 1],
                    [320, 1],
                    [480, 2],
                    [700, 3],
                    [768, 2],
                    [1024, 3],
                    [1200, 3],
                    [1400, 3],
                    [1600, 3]
                ],
            });

            $(".owl-carousel2").owlCarousel({
                pagination: false,
                navigation: true,
                items: 2,
                addClassActive: true,
                itemsCustom : [
                    [0, 1],
                    [320, 1],
                    [480, 2],
                    [700, 3],
                    [975, 2],
                    [1200, 2],
                    [1400, 2],
                    [1600, 2]
                ],
            });
        },

        initImageZoom: function () {
            $('.product-main-image').zoom({url: $('.product-main-image img').attr('data-BigImgSrc')});
        },

        initSliderRange: function () {
            $( "#slider-range" ).slider({
              range: true,
              min: 0,
              max: 500,
              values: [ 50, 250 ],
              slide: function( event, ui ) {
                $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
              }
            });
            $( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
            " - $" + $( "#slider-range" ).slider( "values", 1 ) );
        },

        // wrapper function to scroll(focus) to an element
        scrollTo: function (el, offeset) {
            var pos = (el && el.size() > 0) ? el.offset().top : 0;
            if (el) {
                if ($('body').hasClass('page-header-fixed')) {
                    pos = pos - $('.header').height(); 
                }            
                pos = pos + (offeset ? offeset : -1 * el.height());
            }

            jQuery('html,body').animate({
                scrollTop: pos
            }, 'slow');
        },

        //public function to add callback a function which will be called on window resize
        addResponsiveHandler: function (func) {
            responsiveHandlers.push(func);
        },

        scrollTop: function () {
            App.scrollTo();
        },

        gridOption1: function () {
            $(function(){
                $('.grid-v1').mixitup();
            });    
        },

        initPortfolio: function() {
            $('.mix-grid').mixitup();
        },

        handleBlike: function() {
            handleBlike();
        },

        handleBDislike: function() {
            handleBDislike();
        },

        callAPI: function(url, params, async) {
            return callAPI(url, params, async);
        },

        ////////////////////////////////////////
        // START ---------------- Handle Editors
        ////////////////////////////////////////
        initEditor: function(id, height) {
            CKEDITOR.inline(document.getElementById(id), { 
                language: 'en',
                height: height,
                font_names: 'Montserrat, Helvetica, Arial, sans-serif',
                stylesSet: [
                    { name: 'Source Code', element: 'pre', attributes: { 'class': 'prettyprint linenums' }  },
                    { name: 'Javascript Code', element: 'pre', attributes: { 'class': 'prettyprint linenums lang-js' }  },
                    { name: 'HTML Code', element: 'pre', attributes: { 'class': 'prettyprint linenums lang-html' }  },
                    { name: 'CSS Code', element: 'pre', attributes: { 'class': 'prettyprint linenums lang-css' }  },
                    { name: 'PHP Code', element: 'pre', attributes: { 'class': 'prettyprint linenums lang-php' }  },
                    { name: 'Perl Code', element: 'pre', attributes: { 'class': 'prettyprint linenums lang-pl' }  },
                    { name: 'Python Code', element: 'pre', attributes: { 'class': 'prettyprint linenums lang-py' }  },
                    { name: 'Ruby Code', element: 'pre', attributes: { 'class': 'prettyprint linenums lang-rb' }  },
                    { name: 'Java Code', element: 'pre', attributes: { 'class': 'prettyprint linenums lang-java' }  },
                    { name: 'ASP/VB Code', element: 'pre', attributes: { 'class': 'prettyprint linenums lang-vb' }  },
                    { name: 'C/C++ Code', element: 'pre', attributes: { 'class': 'prettyprint linenums lang-cpp' }  },
                    { name: 'C# Code', element: 'pre', attributes: { 'class': 'prettyprint linenums lang-cs' }  },
                    { name: 'XML Code', element: 'pre', attributes: { 'class': 'prettyprint linenums lang-xml' }  },
                    { name: 'Shell Code', element: 'pre', attributes: { 'class': 'prettyprint linenums lang-bsh' }  },
                    { name: 'Comments', element: 'div', attributes: { 'class': 'alert alert-block' } }
                ],
                toolbar: [
                    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
                    { name: 'styles', items: [ 'Styles', 'Format' ] },
                    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                    { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                    { name: 'editing', groups: [ 'find' ], items: [ 'Find', 'Replace' ] },
                    { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
                    { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Smiley'] },
                    { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] }
                ]
            });
        },

        initCommentEditor : function(id, height) {
            CKEDITOR.inline(document.getElementById(id), { 
                    language: 'en',
                    height: height,
                    font_names: 'Montserrat, Helvetica, Arial, sans-serif',
                    toolbar: [
                        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
                        { name: 'styles', items: [ 'Format' ] },
                        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                        { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                        { name: 'editing' },
                        { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Smiley'] }
                    ]
                });
        },
        ////////////////////////////////////////
        // Handle Editors ------------------ END
        ////////////////////////////////////////

        initPagebar: function(id, counts, page, size, callback_page) {
            initPagebar(id, counts, page, size, callback_page);
        },

        ////////////////////////////////////////
        // START --------------- Storage related
        ////////////////////////////////////////
        saveStorage: function(key, value) {
            try {
                localStorage.setItem(key, JSON.stringify(value));
            }
            catch(err) {

            }
        },
        readStorage: function(key) {
            try {
                return JSON.parse(localStorage.getItem(key) || {})
            }
            catch(err) {
                return null;
            }
        },
        ////////////////////////////////////////
        // Storage related------------------ END
        ////////////////////////////////////////

        saveBlogPrevNext: function(key, list_class)
        {
            articles = [];
            $('.pagination .prev-page a').each(function() {
                articles.push({
                    i: null,
                    h: $(this).attr('href'),
                    t: ''
                });
            });
            $('.' + list_class + ' h2 > a').each(function() {
                articles.push({
                    i: $(this).attr('barticle_id'),
                    h: $(this).attr('href'),
                    t: $(this).text().trim().strip_space()
                });
            });
            $('.pagination .next-page a').each(function() {
                articles.push({
                    i: null,
                    h: $(this).attr('href'),
                    t: ''
                });
            });
            App.saveStorage(key, articles);
        },
    };
}();