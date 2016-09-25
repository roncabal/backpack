/*
*
*	Accordion js
*
*/

(function($){
    $.fn.Accordion = function( options ){
        var defaults = {
        };

        var $this = $(this)
            , $li = $this.children("li")
            , $triggers = $li.children("a")
            , $frames = $this.find("li div")
            ;

        var initTriggers = function(triggers){
            triggers.on('click', function(e){
                e.preventDefault();
                var $a = $(this)
                  , target = $($a.parent('li').children("div"));

                if ( $a.parent('li').hasClass('active') ) {
                    target.slideUp();
                    $(this).parent("li").removeClass("active");
                } else {
                    $frames.slideUp();
                    $li.removeClass("active");
                    target.slideDown();
                    $(this).parent("li").addClass("active");
                }
            });
        }

        return this.each(function(){
            if ( options ) {
                $.extend(defaults, options)
            }

            initTriggers($triggers);
        });
    }

    $(function () {
        $('[data-role="accordion"]').each(function () {
            $(this).Accordion();
        })
    })
})(window.jQuery);

/*
*
*	Button Set js
*
*/

(function($){
    $.fn.ButtonSet = function( options ){
        var defaults = {
        };

        var $this = $(this)
            , $buttons = $this.find("button")
            ;

        var initButtons = function(buttons){
            buttons.on('click', function(e){
                e.preventDefault();
                var $a = $(this);
                if ( $a.hasClass('active') ) return false;
                $buttons.removeClass("active");
                $(this).addClass("active");
            });
        }

        return this.each(function(){
            if ( options ) {
                $.extend(defaults, options)
            }

            initButtons($buttons);
        });
    }

    $(function () {
        $('[data-role="button-set"]').each(function () {
            $(this).ButtonSet();
        })
    })
})(window.jQuery);



/**
*
* Carousel - jQuery plugin for MetroUiCss framework
*
*/



(function($) {

    $.Carousel = function(element, options) {

        // default settings
        var defaults = {
            // auto slide change
            auto: true,
            // slide change period
            period: 6000,
            // animation duration
            duration: 1000,
            // animation effect (fade, slide, switch, slowdown)
            effect: 'slide',
            // animation direction (left, right) for some kinds of animation effect
            direction: 'left',
            // markers below the carousel
            markers: 'on',
            // prev and next arrows
            arrows: 'on',
            // stop sliding when cursor over the carousel
            stop: 'on'
        };

        var plugin = this;
        // plugin settings
        plugin.settings = {};

        var $element = $(element); // reference to the jQuery version of DOM element

        var slides, // all slides DOM objects
            currentSlideIndex, // index of current slide
            slideInPosition, // slide start position before it's appear
            slideOutPosition, // slide position after it's disappear
            parentWidth,
            parentHeight,
            animationInProgress,
            autoSlideTimer,
            markers,
            stopAutoSlide = false;

        // initialization
        plugin.init = function () {

            plugin.settings = $.extend({}, defaults, options);

            slides = $element.find('.slides:first-child > .slide');

            // if only one slide
            if (slides.length <= 1) {
                return;
            }

            currentSlideIndex = 0;

            // parent block dimensions
            parentWidth = $element.innerWidth();
            parentHeight = $element.innerHeight();
            // slides positions, used for some kinds of animation
            slideInPosition = getSlideInPosition();
            slideOutPosition = getSlideOutPosition();

            // prepare slide elements
            slides.each(function (index, slide) {
                $slide = $(slide);
                // each slide must have position:absolute
                // if not, set it
                if ($slide.css('position') !== 'absolute') {
                    $slide.css('position', 'absolute');
                }
                // disappear all slides, except first
                if (index !== 0) {
                    $slide.hide();
                }
            });

            if (plugin.settings.arrows === 'on') {
                // prev next buttons handlers
                $element.find('span.control.left').on('click', function(){
                    changeSlide('left');
                    startAutoSlide();
                });
                $element.find('span.control.right').on('click', function(){
                    changeSlide('right');
                    startAutoSlide();
                });
            } else {
                $element.find('span.control').hide();
            }

            // markers
            if (plugin.settings.markers === 'on') {
                insertMarkers();
            }

            // enable auto slide
            if (plugin.settings.auto === true) {
                startAutoSlide();

                // stop sliding when cursor over the carousel
                if (plugin.settings.stop === 'on') {
                    $element.on('mouseenter', function () {
                        stopAutoSlide = true;
                    });
                    $element.on('mouseleave', function () {
                        stopAutoSlide = false;
                        startAutoSlide();
                    });
                }
            }
        };

        /**
         * returns start position for appearing slide {left: xxx}
         */
        var getSlideInPosition = function () {
            var pos;
            if (plugin.settings.direction === 'left') {
                pos = {
                    'left': parentWidth
                }
            } else if (plugin.settings.direction === 'right') {
                pos = {
                    'left': -parentWidth
                }
            }
            return pos;
        };

        /**
         * returns end position of disappearing slide {left: xxx}
         */
        var getSlideOutPosition = function () {
            var pos;
            if (plugin.settings.direction === 'left') {
                pos = {
                    'left': -parentWidth
                }
            } else if (plugin.settings.direction === 'right') {
                pos = {
                    'left': parentWidth
                }
            }
            return pos;
        };

        /**
         * start or restart auto change
         */
        var startAutoSlide = function () {
            clearInterval(autoSlideTimer);
            // start slide changer timer
            autoSlideTimer = setInterval(function () {
                if (stopAutoSlide) {
                    return;
                }
                changeSlide();
            }, plugin.settings.period);
        };

        /**
         * inserts markers below the carousel
         */
        var insertMarkers = function () {
            var div, ul, li, i;

            div = $('<div class="markers"></div>');
            ul = $('<ul></ul>').appendTo(div);

            for (i = 0; i < slides.length; i++) {
                li = $('<li><a href="javascript:void(0)" data-num="' + i + '"></a></li>');
                if (i === 0) {
                    li.addClass('active');
                }
                li.appendTo(ul);
            }

            markers = ul.find('li');

            ul.find('li a').on('click', function () {
                var $this = $(this),
                    index;

                // index of appearing slide
                index = $this.data('num');
                if (index === currentSlideIndex) {
                    return;
                }

                changeSlide(undefined, 'switch', index);
                startAutoSlide();
            });

            div.appendTo($element);
        };

        /**
         * changes slide to next
         */
        var changeSlide = function(direction, effect, slideIndex) {

            var outSlide, // disappearin slide element
                inSlide, // appearing slide element
                nextSlideIndex,
                delta = 1,
                slideDirection = 1;

            effect = effect || plugin.settings.effect;
            // correct slide direction, used for 'slide' and 'slowdown' effects
            if ((effect === 'slide' || effect === 'slowdown') && typeof direction !== 'undefined' && direction !== plugin.settings.direction) {
                slideDirection = -1;
            }
            if (direction === 'left') {
                delta = -1;
            }

            outSlide = $(slides[currentSlideIndex]);

            nextSlideIndex = typeof slideIndex !== 'undefined' ? slideIndex : currentSlideIndex + delta;
            if (nextSlideIndex >= slides.length) {
                nextSlideIndex = 0;
            }
            if (nextSlideIndex < 0) {
                nextSlideIndex = slides.length - 1;
            }

            inSlide = $(slides[nextSlideIndex]);

            if (animationInProgress === true) {
                return;
            }

            // switch effect is quickly, no need to wait
            if (effect !== 'switch') {
                // when animation in progress no other animation occur
                animationInProgress = true;
                setTimeout(function () {
                    animationInProgress = false;
                }, plugin.settings.duration)
            }

            // change slide with selected effect
            switch (effect) {
                case 'switch':
                    changeSlideSwitch(outSlide, inSlide);
                    break;
                case 'slide':
                    changeSlideSlide(outSlide, inSlide, slideDirection);
                    break;
                case 'fade':
                    changeSlideFade(outSlide, inSlide);
                    break;
                case 'slowdown':
                    changeSlideSlowdown(outSlide, inSlide, slideDirection);
                    break;
            }

            currentSlideIndex = nextSlideIndex;

            // switch marker
            if (plugin.settings.markers === 'on') {
                markers.removeClass('active');
                $(markers.get(currentSlideIndex)).addClass('active');
            }

        };
        /**
         * switch effect
         */
        var changeSlideSwitch = function (outSlide, inSlide) {
            inSlide.show().css({'left': 0});
            outSlide.hide();
        };
        /**
         * slide effect
         */
        var changeSlideSlide = function (outSlide, inSlide, slideDirection) {
            var unmovedPosition = {'left': 0},
                duration = plugin.settings.duration;

            if (slideDirection !== -1) {
                inSlide.css(slideInPosition);
                inSlide.show();
                outSlide.animate(slideOutPosition, duration);
                inSlide.animate(unmovedPosition, duration);
            } else {
                inSlide.css(slideOutPosition);
                inSlide.show();
                outSlide.animate(slideInPosition, duration);
                inSlide.animate(unmovedPosition, duration);
            }
        };
        /**
         * slowdown slide effect (custom easing 'doubleSqrt')
         */
        var changeSlideSlowdown = function (outSlide, inSlide, slideDirection) {
            var unmovedPosition = {'left': 0},
                options;

            options = {
                'duration': plugin.settings.duration,
                'easing': 'doubleSqrt'
            };

            if (slideDirection !== -1) {
                inSlide.css(slideInPosition);
                inSlide.show();
                outSlide.animate(slideOutPosition, options);
                inSlide.animate(unmovedPosition, options);
            } else {
                inSlide.css(slideOutPosition);
                inSlide.show();
                outSlide.animate(slideInPosition, options);
                inSlide.animate(unmovedPosition, options);
            }
        };
        /**
         * fade effect
         */
        var changeSlideFade = function (outSlide, inSlide) {
            inSlide.fadeIn(plugin.settings.duration);
            outSlide.fadeOut(plugin.settings.duration);
        };

        plugin.init();

    };

    $.fn.Carousel = function(options) {
        return this.each(function() {
            if (undefined == $(this).data('Carousel')) {
                var plugin = new $.Carousel(this, options);
                $(this).data('Carousel', plugin);
            }
        });
    };

    // easing effect for jquery animation
    $.easing.doubleSqrt = function(t, millisecondsSince, startValue, endValue, totalDuration) {
        var res = Math.sqrt(Math.sqrt(t));
        return res;
    };

})(jQuery);


$(window).ready(function(){
    var allCarousels = $('[data-role=carousel], .carousel');
    allCarousels.each(function (index, carousel) {
        var params = {};
        $carousel = $(carousel);
        params.auto         = $carousel.data('paramAuto');
        params.period       = $carousel.data('paramPeriod');
        params.duration     = $carousel.data('paramDuration');
        params.effect       = $carousel.data('paramEffect');
        params.direction    = $carousel.data('paramDirection');
        params.markers      = $carousel.data('paramMarkers');
        params.arrows       = $carousel.data('paramArrows');
        params.stop         = $carousel.data('paramStop');

        $carousel.Carousel(params);
    })

});

/*
*
*	Dropdown Metro-Ui js
*
*/

(function($){
    $.fn.Dropdown = function( options ){
        var defaults = {
        };

        var $this = $(this)
            ;

        var clearDropdown = function(){
            $(".dropdown-menu").each(function(){
                if ( $(this).css('position') == 'static' ) return;
                $(this).slideUp('fast', function(){});
                $(this).parent().removeClass("active");
            });
        }

        var initSelectors = function(selectors){
            selectors.on('click', function(e){
                e.stopPropagation();
                //$("[data-role=dropdown]").removeClass("active");

                clearDropdown();
                $(this).parents("ul").css("overflow", "visible");

                var $m = $(this).children(".dropdown-menu, .sidebar-dropdown-menu");
                if ($m.css('display') == "block") {
                    $m.slideUp('fast');
                    $(this).removeClass("active");
                } else {
                    $m.slideDown('fast');
                    $(this).addClass("active");
                }
            }).on("mouseleave", function(){
                //$(this).children(".dropdown-menu").hide();
            });
            $('html').on("click", function(e){
                clearDropdown();
            });
        }

        return this.each(function(){
            if ( options ) {
                $.extend(defaults, options)
            }

            initSelectors($this);
        });
    }

    $(function () {
        $('[data-role="dropdown"]').each(function () {
            $(this).Dropdown();
        })
    })
})(window.jQuery);


(function($){
    $.fn.PullDown = function( options ){
        var defaults = {
        };

        var $this = $(this)
            ;

        var initSelectors = function(selectors){

            selectors.on('click', function(e){
                e.preventDefault();
                var $m = $this.parent().children("ul");
                //console.log($m);
                if ($m.css('display') == "block") {
                    $m.slideUp('fast');
                } else {
                    $m.slideDown('fast');
                }
                //$(this).toggleClass("active");
            });
        }

        return this.each(function(){
            if ( options ) {
                $.extend(defaults, options)
            }

            initSelectors($this);
        });
    }

    $(function () {
        $('.menu-pull').each(function () {
            $(this).PullDown();
        })
    })
})(window.jQuery);

/*
*
*	Page Control Metro-Ui js
*
*/

(function($){
    $.fn.PageControl = function( options ){
        var defaults = {
        };

        var $this = $(this)
            , $ul = $this.children("ul")
            , $selectors = $ul.find("li a")
            , $selector = $ul.find(".active a")
            , $frames = $this.find(".frames .frame")
            , $frame = $frames.children(".frame.active")
            ;

        var initSelectors = function(selectors){
            selectors.on('click', function(e){
                e.preventDefault();
                var $a = $(this);
                if (!$a.parent('li').hasClass('active')) {
                    $frames.hide();
                    $ul.find("li").removeClass("active");
                    var target = $($a.attr("href"));
                    target.show();
                    $(this).parent("li").addClass("active");
                }
                if ($(this).parent("li").parent("ul").parent(".page-control").find(".menu-pull-bar").is(":visible")) {
                    $(this).parent("li").parent("ul").slideUp("fast", function () {
                        $(this).css("overflow", "").css("display", "");
                    });
                }
            });

            $(".page-control .menu-pull-bar").text($(".page-control ul li.active a").text());
            $(".page-control ul li a").click(function (e) {
                e.preventDefault();
                $(this).parent("li").parent("ul").parent(".page-control").find(".menu-pull-bar").text($(this).text());
            });
        }

        return this.each(function(){
            if ( options ) {
                $.extend(defaults, options)
            }

            initSelectors($selectors);
        });
    }

    $(function () {
        $('[data-role="page-control"]').each(function () {
            $(this).PageControl();
        })
        $(window).resize(function(){
            if ($(window).width() >= 768) {
                $(".page-control ul").css({
                    display: "block"
                    ,overflow: "visible"
                })
            }
            if ($(window).width() < 768 && $(".page-control ul").css("display") == "block") {
                $(".page-control ul").hide();
            }
        })
    })
})(window.jQuery);


/*
*
*	Rating Metro-Ui Js
*
*/

(function($){
    $.fn.Rating = function( options ){
        var defaults = {
        };

        var $this = $(this)
            ;

        var init = function(el){
            var a = el.find("a");
            var r = Math.round(el.data("rating")) || 0;

            a.each(function(index){
                console.log(index);
                if (index < r) {
                    $(this).addClass("rated");
                }

                $(this).hover(
                    function(){
                        $(this).prevAll().andSelf().addClass("hover");
                        $(this).nextAll().removeClass("hover");
                    },
                    function(){
                        $(this).prevAll().andSelf().removeClass("hover");
                    }
                )
            })

        }

        return this.each(function(){
            if ( options ) {
                $.extend(defaults, options)
            }

            init($this);
        });
    }

    $(function () {
        $('[data-role="rating"]').each(function () {
            $(this).Rating();
        })
    })
})(window.jQuery);


/**
 * Slider - jQuery plugin for MetroUiCss framework
 *
 * there is "change" event triggering when marker moving
 * and "changed" event when stop moving
 *
 * you may use this code to handle events:

$(window).ready(function(){
    $('.slider').on('change', function(e, val){
        console.log('change to ' + val);
    }).on('changed', function(e, val){
        console.log('changed to ' + val);
    });
});

 * and this, to retrieve value

$('.slider').data('value')

 *
 */

(function($) {

    $.slider = function(element, options) {

        // default settings
        var defaults = {
            // start value of slider
            initValue: 0,
            // accuracy
            accuracy: 1
        };

        var plugin = this;
        plugin.settings = {};

        var $element = $(element); // reference to the jQuery version of DOM element

        var complete, // complete part element
            marker, // marker element
            currentValuePerc, // current percents count
            sliderLength,
            sliderOffset,
            sliderStart,
            sliderEnd,
            percentPerPixel,
            markerSize,
            vertical = false;

        // initialization
        plugin.init = function () {

            plugin.settings = $.extend({}, defaults, options);

            // create inside elements
            complete = $('<div class="complete"></div>');
            marker = $('<div class="marker"></div>');

            complete.appendTo($element);
            marker.appendTo($element);

            vertical = $element.hasClass('vertical');

            initGeometry();

            // start value
            currentValuePerc = correctValuePerc(plugin.settings.initValue);
            placeMarkerByPerc(currentValuePerc);

            // init marker handler
            marker.on('mousedown', function (e) {
                e.preventDefault();
                startMoveMarker();
            });

            $element.on('click', function (event) {
                initGeometry();
                movingMarker(event);
                $element.trigger('changed', [currentValuePerc]);
            });

        };

        /**
         * correct percents using "accuracy" parameter
         */
        var correctValuePerc = function (value) {
            var accuracy = plugin.settings.accuracy;
            if (accuracy === 0) {
                return value;
            }
            if (value === 100) {
                return 100;
            }
            value = Math.floor(value / accuracy) * accuracy + Math.round(value % accuracy / accuracy) * accuracy;
            if (value > 100) {
                return 100;
            }
            return value;
        };

        /**
         * convert pixels to percents
         */
        var pixToPerc = function (valuePix) {
            var valuePerc;
            valuePerc = valuePix * percentPerPixel;
            return correctValuePerc(valuePerc);
        };

        /**
         * convert percents to pixels
         */
        var percToPix = function (value) {
            if (percentPerPixel === 0) {
                return 0;
            }
            return value / percentPerPixel;
        };

        /**
         * place marker
         */
        var placeMarkerByPerc = function (valuePerc) {
            var size, size2;

            if (vertical) {
                size = percToPix(valuePerc) + markerSize;
                size2 = sliderLength - size;
                marker.css('top', size2);
                complete.css('height', size);
            } else {
                size = percToPix(valuePerc);
                marker.css('left', size);
                complete.css('width', size);
            }

        };

        /**
         * when mousedown on marker
         */
        var startMoveMarker = function () {
            // register event handlers
            $(document).on('mousemove.sliderMarker', function (event) {
                movingMarker(event);
            });
            $(document).on('mouseup.sliderMarker', function () {
                $(document).off('mousemove.sliderMarker');
                $(document).off('mouseup.sliderMarker');
                $element.data('value', currentValuePerc);
                $element.trigger('changed', [currentValuePerc]);
            });

            initGeometry();
        };

        /**
         * some geometry slider parameters
         */
        var initGeometry = function () {
            if (vertical) {
                sliderLength = $element.height(); // slider element length
                sliderOffset = $element.offset().top; // offset relative to document edge
                markerSize = marker.height();
            } else {
                sliderLength = $element.width();
                sliderOffset = $element.offset().left;
                markerSize = marker.width();

            }

            percentPerPixel = 100 / (sliderLength - markerSize); // it depends on slider element size
            sliderStart = markerSize / 2;
            sliderEnd = sliderLength - markerSize / 2;
        };

        /**
         * moving marker
         */
        var movingMarker = function (event) {
            var cursorPos,
                percents,
                valuePix;

            // cursor position relative to slider start point
            if (vertical) {
                cursorPos = event.pageY - sliderOffset;
            } else {
                cursorPos = event.pageX - sliderOffset;
            }

            // if outside
            if (cursorPos < sliderStart) {
                cursorPos = sliderStart;
            } else if (cursorPos > sliderEnd) {
                cursorPos = sliderEnd;
            }

            // get pixels count
            if (vertical) {
                valuePix = sliderLength - cursorPos - markerSize / 2;
            } else {
                valuePix = cursorPos - markerSize / 2;
            }

            // convert to percent
            percents = pixToPerc(valuePix);

            // place marker
            placeMarkerByPerc(percents);

            currentValuePerc = percents;

            $element.trigger('change', [currentValuePerc]);
        };


        plugin.init();

    };

    $.fn.slider = function(options) {
        return this.each(function() {
            if (undefined == $(this).data('slider')) {
                var plugin = new $.slider(this, options);
                $(this).data('slider', plugin);
            }
        });
    };


})(jQuery);


$(window).ready(function(){
    var allsliders = $('[data-role=slider], .slider');
    allsliders.each(function (index, slider) {
        var params = {};
        $slider = $(slider);
        params.initValue        = $slider.data('paramInitValue');
        params.accuracy       = $slider.data('paramAccuracy');

        $slider.slider(params);
    });
});


/*
*
*	Tile Slider JS
*
*/

$.easing.doubleSqrt = function(t, millisecondsSince, startValue, endValue, totalDuration) {
    var res = Math.sqrt(Math.sqrt(t));
    return res;
};

(function($) {

    $.tileBlockSlider = function(element, options) {

        // настройки по умолчанию
        var defaults = {
            // период смены картинок
            period: 2000,
            // продолжительность анимации
            duration: 1000,
            // направление анимации (up, down, left, right)
            direction: 'up'
        };
        // объект плагина
        var plugin = this;
        // настройки конкретного объекта
        plugin.settings = {};

        var $element = $(element), // reference to the jQuery version of DOM element
            element = element;    // reference to the actual DOM element

        var blocks, // все картинки
            currentBlockIndex, // индекс текущего блока
            slideInPosition, // стартовое положение блока перед началом появления
            slideOutPosition, // финальное положение блока при скрытии
            tileWidth, // размеры плитки
            tileHeight;

        // инициализируем
        plugin.init = function () {

            plugin.settings = $.extend({}, defaults, options);

            // все блоки
            blocks = $element.children(".tile-content");

            // если блок всего 1, то слайдинг не нужен
            if (blocks.length <= 1) {
                return;
            }

            // индекс активного в данный момент блока
            currentBlockIndex = 0;

            // размеры текущей плитки
            tileWidth = $element.innerWidth();
            tileHeight = $element.innerHeight();
            // положение блоков
            slideInPosition = getSlideInPosition();
            slideOutPosition = getSlideOutPosition();

            // подготавливаем блоки к анимации
            blocks.each(function (index, block) {
                block = $(block);
                // блоки должны быть position:absolute
                // возможно этот параметр задан через класс стилей
                // проверяем, и добавляем если это не так
                if (block.css('position') !== 'absolute') {
                    block.css('position', 'absolute');
                }
                // скрываем все блоки кроме первого
                if (index !== 0) {
                    block.css('left', tileWidth);
                }
            });

            // запускаем интервал для смены блоков
            setInterval(function () {
                slideBlock();
            }, plugin.settings.period);
        };

        // смена блоков
        var slideBlock = function() {

            var slideOutBlock, // блок который надо скрыть
                slideInBlock, // блок который надо показать
                mainPosition = {'left': 0, 'top': 0},
                options;

            slideOutBlock = $(blocks[currentBlockIndex]);

            currentBlockIndex++;
            if (currentBlockIndex >= blocks.length) {
                currentBlockIndex = 0;
            }
            slideInBlock = $(blocks[currentBlockIndex]);

            slideInBlock.css(slideInPosition);

            options = {
                duration: plugin.settings.duration,
                easing: 'doubleSqrt'
            };

            slideOutBlock.animate(slideOutPosition, options);
            slideInBlock.animate(mainPosition, options);
        };

        /**
         * возвращает стартовую позицию для блока который должен появиться {left: xxx, top: yyy}
         */
        var getSlideInPosition = function () {
            var pos;
            if (plugin.settings.direction === 'left') {
                pos = {
                    'left': tileWidth,
                    'top': 0
                }
            } else if (plugin.settings.direction === 'right') {
                pos = {
                    'left': -tileWidth,
                    'top': 0
                }
            } else if (plugin.settings.direction === 'up') {
                pos = {
                    'left': 0,
                    'top': tileHeight
                }
            } else if (plugin.settings.direction === 'down') {
                pos = {
                    'left': 0,
                    'top': -tileHeight
                }
            }
            return pos;
        };

        /**
         * возвращает финальную позицию для блока который должен скрыться {left: xxx, top: yyy}
         */
        var getSlideOutPosition = function () {
            var pos;
            if (plugin.settings.direction === 'left') {
                pos = {
                    'left': -tileWidth,
                    'top': 0
                }
            } else if (plugin.settings.direction === 'right') {
                pos = {
                    'left': tileWidth,
                    'top': 0
                }
            } else if (plugin.settings.direction === 'up') {
                pos = {
                    'left': 0,
                    'top': -tileHeight
                }
            } else if (plugin.settings.direction === 'down') {
                pos = {
                    'left': 0,
                    'top': tileHeight
                }
            }
            return pos;
        };

        plugin.getParams = function() {

            // code goes here

        }

        plugin.init();

    }

    $.fn.tileBlockSlider = function(options) {
        return this.each(function() {
            if (undefined == $(this).data('tileBlockSlider')) {
                var plugin = new $.tileBlockSlider(this, options);
                $(this).data('tileBlockSlider', plugin);
            }
        });
    }

})(jQuery);


$(window).ready(function(){
    var slidedTiles = $('[data-role=tile-slider], .block-slider, .tile-slider');
    slidedTiles.each(function (index, tile) {
        var params = {};
        tile = $(tile);
        params.direction = tile.data('paramDirection');
        params.duration = tile.data('paramDuration');
        params.period = tile.data('paramPeriod');
        tile.tileBlockSlider(params);
    })

});