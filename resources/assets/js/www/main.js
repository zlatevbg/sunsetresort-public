//=require plugins/*.js
//=require vendor/plugins/combine/*.js
//=require vendor/tweezer.js
//=require vendor/cookieconsent.js
//=require vendor/plugins/slider-pro/jquery.sliderPro.core.js
// require vendor/plugins/slider-pro/jquery.sliderPro.thumbnails.js
//=require vendor/plugins/slider-pro/jquery.sliderPro.conditionalImages.js
// require vendor/plugins/slider-pro/jquery.sliderPro.retina.js
//=require vendor/plugins/slider-pro/jquery.sliderPro.lazyLoading.js
//=require vendor/plugins/slider-pro/jquery.sliderPro.layers.js
// require vendor/plugins/slider-pro/jquery.sliderPro.fade.js
//=require vendor/plugins/slider-pro/jquery.sliderPro.touchSwipe.js
// require vendor/plugins/slider-pro/jquery.sliderPro.caption.js
// require vendor/plugins/slider-pro/jquery.sliderPro.deepLinking.js
//=require vendor/plugins/slider-pro/jquery.sliderPro.autoplay.js
// require vendor/plugins/slider-pro/jquery.sliderPro.keyboard.js
// require vendor/plugins/slider-pro/jquery.sliderPro.fullScreen.js
//=require vendor/plugins/slider-pro/jquery.sliderPro.buttons.js
//=require vendor/plugins/slider-pro/jquery.sliderPro.arrows.js
// require vendor/plugins/slider-pro/jquery.sliderPro.thumbnailTouchSwipe.js
// require vendor/plugins/slider-pro/jquery.sliderPro.thumbnailArrows.js
// require vendor/plugins/slider-pro/jquery.sliderPro.video.js

var unikat = function() {
  'use strict';

  var variables = {
    lock_time: 0,
    jsInfoHook: '.js-info',
  };

  var dfrom;
  var dto;
  var missingDates = {};

  var htmlLoading;

  var errorWrapperHtmlStart = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close"><span aria-hidden="true">&times;</span></button>';
  var errorMessageHtmlStart =  '<ul>';
  var errorListHtmlStart =      '<li>';
  var errorListHtmlEnd =        '</li>';
  var errorMessageHtmlEnd =    '</ul>';
  var errorWrapperHtmlEnd =   '</div>';

  var successWrapperHtmlStart = '<div class="alert alert-success alert-dismissible"><button type="button" class="close"><span aria-hidden="true">&times;</span></button><span class="glyphicon glyphicon-ok"></span>';
  var successWrapperHtmlEnd =   '</div>';

  var alertMessagesHtmlStart = '<div class="alert-messages">';
  var alertMessagesAbsoluteHtmlStart = '<div class="alert-messages absolute">';
  var alertMessagesHtmlEnd =   '</div>';

  var glyphiconWarning = '<span class="glyphicon glyphicon-warning-sign"></span>';
  var glyphiconRemove = '<span class="glyphicon glyphicon-remove form-control-feedback"></span>';
  var glyphiconRemoveSpan = 'span.glyphicon-remove';

  var formGroupClass = '.form-group';
  var hasErrorClass = 'has-error has-feedback';
  var hasErrorClasses = hasErrorClass + ' has-addon-feedback';
  var ajaxLockClass = '.ajax-lock';
  var ajaxLockedClass = '.ajax-locked';
  var alertMessagesClass = '.alert-messages';
  var alertSuccessClass = '.alert-success';
  var alertErrorClass = '.alert-danger';
  var alertClass = '.alert';
  var inputGroupAddonClass = 'input-group-addon';
  var buttonCloseClass = 'button.close';

  function run() {
    htmlLoading = '<div tabindex="-1" class="ajax-locked"><div><div><img src="' + variables.loadingImageSrc + '" alt="' + variables.loadingImageAlt + '" title="' + variables.loadingImageTitle + '">' + variables.loadingText + '</div></div></div>';
    errorMessageHtmlStart = variables.ajaxErrorMessage + errorMessageHtmlStart;

    $('#mobile-nav').click(function() {
      $(this).toggleClass('active');
      $('body').toggleClass('nav-open');
    });

    if (variables.utcNowTimestamp <= variables.utcTimestamp) {
        var $countdown = $('#countdown');

        var $countdownDays = $('#countdownDays', $countdown);
        var $countdownHours = $('#countdownHours', $countdown);
        var $countdownMinutes = $('#countdownMinutes', $countdown);
        var $countdownSeconds = $('#countdownSeconds', $countdown);

        var $countdownDaysNumber = $('span', $countdownDays).first();
        var $countdownHoursNumber = $('span', $countdownHours).first();
        var $countdownMinutesNumber = $('span', $countdownMinutes).first();
        var $countdownSecondsNumber = $('span', $countdownSeconds).first();

        var $countdownDaysText = $('span', $countdownDays).last();
        var $countdownHoursText = $('span', $countdownHours).last();
        var $countdownMinutesText = $('span', $countdownMinutes).last();
        var $countdownSecondsText = $('span', $countdownSeconds).last();

        $countdown.countdown(variables.utcTimestamp * 1000)
        .on('update.countdown', function(event) {
            $countdownDaysNumber.text(event.strftime('%D'));
            $countdownHoursNumber.text(event.strftime('%H'));
            $countdownMinutesNumber.text(event.strftime('%M'));
            $countdownSecondsNumber.text(event.strftime('%S'));

            $countdownDaysText.text(event.strftime('%!D:' + variables.countdownDays + ';'));
            $countdownHoursText.text(event.strftime('%!H:' + variables.countdownHours + ';'));
            $countdownMinutesText.text(event.strftime('%!M:' + variables.countdownMinutes + ';'));
            $countdownSecondsText.text(event.strftime('%!S:' + variables.countdownSeconds + ';'));
        })
        .on('finish.countdown', function(event) {
            window.location.reload(true);
        });
    }

    $(document).on('mouseenter', '.tooltip', function() {
        $(this).qtip({
            position: {
                // container: $('.magnific-popup'),
                viewport: $(window),
                my: 'bottom center',
                // at: 'top center',
                // target: [$(this).offset().left + 20, $(this).offset().top + 5],
                adjust: {
                  x: -15,
                  y: -25,
                }
            },
            content: {
                title: $(this).children('.tooltip-content').attr('title'),
                text: $(this).children('.tooltip-content'),
            },
            overwrite: false,
            show: {
                ready: true,
            },
            hide: {
                fixed: true,
                delay: 300,
            },
            style: {
                tip: {
                    width: 12,
                    height: 12,
                },
            },
        });
    });

    var magnificPopupConfig = {
      delegate: 'a.popup',
      type: 'image',
      tClose: variables.magnificPopup.close,
      tLoading: variables.magnificPopup.loading,
      gallery: {
        enabled: true,
        preload: [0, 2],
        tPrev: variables.magnificPopup.prev,
        tNext: variables.magnificPopup.next,
      },
      preloader: true,
      mainClass: 'mfp-zoom-in',
      zoom: {
        enabled: true,
      },
    };

    $('.popup-gallery').magnificPopup(magnificPopupConfig);

    $('.popup-galleries').each(function() {
      $(this).magnificPopup(magnificPopupConfig);
    });

    if (typeof unikat.sliders == 'object') {
      slidersSetup(unikat.sliders);
    }

    if (typeof $.multiselect == 'object') {
      multiselectSetup(unikat.multiselect);
    }

    $(document).on('click', '.js-map', function(e) {
      e.preventDefault();

      var src = $(this).attr('href') + '/map';

      $.magnificPopup.open({
        type: 'ajax',
        key: 'popup-form',
        focus: ':input:visible',
        closeOnBgClick: true,
        closeBtnInside: false,
        alignTop: false,
        tClose: variables.magnificPopup.close,
        tLoading: variables.magnificPopup.loading,
        ajax: {
          tError: variables.magnificPopup.ajaxError
        },
        preloader: true,
        removalDelay: 500,
        mainClass: 'mfp-zoom-in map-wrapper',
        items: {
          src: src,
        },
      });
    });

    $(document).on('click', variables.jsInfoHook, function(e) {
      e.preventDefault();

      var src = $(this).attr('href');

      $.magnificPopup.open({
        type: 'ajax',
        key: 'popup-form',
        focus: ':input:visible',
        closeOnBgClick: false,
        alignTop: true,
        tClose: variables.magnificPopup.close,
        tLoading: variables.magnificPopup.loading,
        ajax: {
          tError: variables.magnificPopup.ajaxError
        },
        preloader: true,
        removalDelay: 500,
        mainClass: 'mfp-zoom-in',
        items: {
          src: src,
        },
      });
    });

    $(document).on('click', function(e) {
      if (!$(e.target).closest('.dropdown-toggle').length && !$(e.target).closest('.dropdown-guests').length) {
        $('.dropdown-menu').each(function() {
          if (!$(this).hasClass('menu-static')) {
            $(this).removeClass('active');
            $(this).prev().removeClass('active');
          }
        });

        $('.slidedown-menu').each(function() {
          if (!$(this).hasClass('menu-static')) {
            $(this).slideUp();
          }
        });
      }
    });

    $(document).on('click', '.dropdown-toggle', function(e) {
      e.preventDefault();

      $('.dropdown-toggle.active').removeClass('active');
      $(this).toggleClass('active');

      var parent = $(this).parent();
      var next = $(this).next();

      $('.dropdown-menu.active').not(next[0]).removeClass('active');

      if (next.width() > ($(window).width() - parent.offset().left)) {
        next.addClass('dropdown-menu-right');
      }

      next.toggleClass('active');
    });

    $(document).on('click', '.slidedown-toggle', function(e) {
      e.preventDefault();
      e.stopPropagation();

      if (!$(e.target).closest('.dropdown-menu').length) {
        $('.dropdown-menu.active').removeClass('active');
      }

      $('.slidedown-menu').not($(this).next()).each(function() {
        if (!$(this).hasClass('menu-static')) {
          $(this).slideUp();
        }
      });

      $(this).toggleClass('active');
      $(this).next().slideToggle();
    });

    $(document).on('click', alertMessagesClass + ' ' + buttonCloseClass, function() {
      $(this).closest(alertClass).remove();
    });

    if (typeof this.callback == 'function') {
      this.callback();
    }

    $(document).on('submit', 'form:not(.none)', function(e) {
      if ($(this).hasClass('defaultForm')) {
        return true;
      }

      e.preventDefault();

      var data = $(this).serialize();

      ajaxify({
        that: $(this),
        method: 'post',
        queue: $(this).data('ajaxQueue'),
        action: $(this).attr('action'),
        data: data,
      });

      return false;
    });

    $(document).on('click', '.bookPrev, .step-visited, #book-button', function(e) {
      var that = $(this).closest('form');
      if (!that.length) {
        that = $(this);
      }

      if (that.hasClass('defaultForm')) {
        if ($(this).data('url')) {
          window.location.href = $(this).data('url');
        }
        return true;
      }

      e.preventDefault();

      ajaxify({
        that: that,
        method: 'get',
        queue: that.data('ajaxQueue'),
        action: $(this).attr('href') ? $(this).attr('href') : $(this).data('url'),
      });

      return false;
    });

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      },
    });
  }

  function addZ(n) {
    return n < 10 ? '0' + n : '' + n;
  }

  function formatBookDate(date) {
    return date.getFullYear() + '' + addZ(date.getMonth() + 1) + '' + addZ(date.getDate());
  }

  function dfromSelected(date) {
    var refreshDto = false;
    $('#input-dfrom-text').text(date);
    var d = new Date($("#input-dfrom").datepicker("getDate"));
    var maxDate = new Date(d.getFullYear(), (d.getMonth() + 3), d.getDate())
    var keys = [];

    dfrom = formatBookDate(d);

    for (var k in missingDates) {
      if (missingDates.hasOwnProperty(k)) {
        keys.push(k);
      }
    }

    keys.sort();

    for (var i = 0, len = keys.length; i < len; i++) {
      if (dfrom < keys[i]) {
        maxDate = new Date(keys[i].substring(0, 4), keys[i].substring(4, 6) - 1, keys[i].substring(6, 8));

        if (dto > keys[i]) {
          dto = dfrom;
          $('#input-dto').datepicker('setDate', d);
          refreshDto = true;
        }

        break;
      }
    }

    if (dfrom > dto) {
      dto = formatBookDate(d);
      refreshDto = true;
    }

    if (refreshDto) {
      $('#input-dto').datepicker('refresh');
      $('#input-dto-text').text(date);
    }

    $('#input-dto').datepicker('option', 'minDate', d);
    $('#input-dto').datepicker('option', 'maxDate', maxDate);
    $('#input-dto').removeAttr('disabled');
  }

  function dtoSelected(date) {
    var d = new Date($("#input-dto").datepicker("getDate"));
    dto = formatBookDate(d);
    $('#booking-step1').removeAttr('disabled');
    $('#input-dto-text').text(date);
    if (!dfrom) {
      $('#input-dfrom').datepicker('setDate', d);
      dfromSelected(date);
    }
    $('#input-dfrom').datepicker('refresh');
  }

  function bookDates(from, to, firstDate) {
    $.datepicker.regional[unikat.variables.language] = unikat.variables.datepicker[unikat.variables.language];
    $.datepicker.setDefaults($.datepicker.regional[unikat.variables.language]);

    if (from) {
      dfrom = formatBookDate(new Date(from));
    }

    if (to) {
      dto = formatBookDate(new Date(to));
    }

    var dates = JSON.parse(unikat.variables.dates);
    var lastAvailableDate = dates[Object.keys(dates)[Object.keys(dates).length - 1]].dto;
    var maxDate = new Date(lastAvailableDate.substring(0, 4), lastAvailableDate.substring(4, 6) - 1, lastAvailableDate.substring(6, 8))

    $('#input-dfrom').datepicker({
      minDate: firstDate ? firstDate : +1,
      maxDate: maxDate, // '+1y',
      altField: '#input-dfrom-hidden',
      onSelect: function(date) {
        dfromSelected(date);
      },
      beforeShowDay: function(date) {
        var current = formatBookDate(date);
        missingDates[current] = null;

        var available = false;
        for (var i = 0; i < dates.length; i++) {
          if (current >= dates[i].dfrom && current <= dates[i].dto) {
            available = true;
            delete missingDates[current];
            break;
          }
        }

        if (!available) {
          return [false, ''];
        } else if (current == dfrom) {
          return [true, 'first-day'];
        } else if (current == dto) {
          return [true, 'last-day'];
        } else if (current > dfrom && current < dto) {
          return [true, 'booked-day'];
        } else {
          return [true, ''];
        }
      },
    });

    $('#input-dto').datepicker({
      minDate: firstDate ? firstDate : +1,
      maxDate: maxDate, // '+1y',
      altField: '#input-dto-hidden',
      onSelect: function(date) {
        dtoSelected(date);
      },
      beforeShowDay: function(date) {
        var current = formatBookDate(date);

        var available = false;
        for (var i = 0; i < dates.length; i++) {
          if (current >= dates[i].dfrom && current <= dates[i].dto) {
            available = true;
            break;
          }
        }

        if (!available) {
          return [false, ''];
        } else if (current == dfrom) {
          return [true, 'first-day'];
        } else if (current == dto) {
          return [true, 'last-day'];
        } else if (current > dfrom && current < dto) {
          return [true, 'booked-day'];
        } else {
          return [true, ''];
        }
      },
    });

    if (from) {
      var d = new Date(from);
      $('#input-dfrom').datepicker('setDate', d);
      dfromSelected(addZ(d.getDate()) + '.' + addZ(d.getMonth() + 1) + '.' + d.getFullYear());
    }

    if (to) {
      var d = new Date(to);
      $('#input-dto').datepicker('setDate', d);
      dtoSelected(addZ(d.getDate()) + '.' + addZ(d.getMonth() + 1) + '.' + d.getFullYear())
    }

    $('#input-dfrom').on('input', function() {
      if (this.value) {
        $('#input-dto').removeAttr('disabled');
      } else {
        $('#input-dto').attr('disabled', 'disabled');
      }
    });

    $('#input-dto').on('input', function() {
      if (this.value) {
        $('#booking-step1').removeAttr('disabled');
      } else {
        $('#booking-step1').attr('disabled', 'disabled');
      }
    });
  }

  function slidersSetup(sliders) {
    var config = {
      width: '100%',
      autoHeight: true,
      arrows: true,
      buttons: true,
      waitForLayers: true,
      autoplay: false,
      autoScaleLayers: false,
      init: function() {
        $(this.instance).css('opacity', 1);
      },
    };

    $.each(sliders, function(key) {
      $('#' + key).sliderPro($.extend(true, {}, config, sliders[key]));
    });
  }

  function multiselectSetup(multiselect) {
    $.extend($.multiselect.multiselect.prototype.options, {
      checkAllText: variables.multiselect.checkAll,
      uncheckAllText: variables.multiselect.uncheckAll,
      noneSelectedText: variables.multiselect.noneSelected,
      noneSelectedSingleText: variables.multiselect.noneSelectedSingle,
      selectedText: variables.multiselect.selected,
      filterLabel: variables.multiselect.filterLabel,
      filterPlaceholder: variables.multiselect.filterPlaceholder,
    });

    $.each(multiselect, function(key, value) {
      $('#' + key).multiselect(value);
    });
  }

  function ajaxify(params) {
    ajax_abort(params.that);
    if (!params.skipLock) {
      ajax_lock(params.that);
    }

    if (params.method == 'get') {
      var jqxhr = $.getq(params.queue, params.action, params.data);
    } else {
      var jqxhr = $.postq(params.queue, params.action, params.data);
    }

    jqxhr.done(function(data, status, xhr) {
      var that = params.that;

      if (data.redirect) {
        window.location.href = data.redirect;
      } else if (data.refresh) {
        window.location.reload(true);
      } else if (data.submit) {
        for (var key in data.fields) {
          document.getElementById(key).value = data.fields[key];
        }
        document.getElementById(data.submit).submit();
      } else if (data.modal) {
        ajax_unlock(params.that);
        $.magnificPopup.open({
          type: 'inline',
          alignTop: false,
          closeOnBgClick: true,
          closeBtnInside: true,
          mainClass: 'mfp-zoom-in',
          key: 'popup-form',
          focus: ':input:visible',
          tClose: variables.magnificPopup.close,
          tLoading: variables.magnificPopup.loading,
          ajax: {
            tError: variables.magnificPopup.ajaxError,
          },
          preloader: true,
          removalDelay: 500,
          items: {
            src: data.modal,
          },
        });
      } else {
        if (!params.skipLock) {
          ajax_unlock(params.that);
        }
        ajax_reset(params.that, data);
        if (data.success) {
          if (data.closePopup) {
            params.that = that;
            $.magnificPopup.close();
            ajax_unlock(params.that);
          }

          if (data.success !== true) {
            ajax_success_text(params.that, data.success);
          }
        }

        if (data.errors) {
          if (!params.skipErrors) {
            ajax_error(params.that, data);
          }
        }
      }
    });

    jqxhr.fail(function(xhr, textStatus, errorThrown) {
      if (textStatus != 'abort' && !params.skipErrors) {
        ajax_unlock(params.that);
                if (xhr.status == 422) { // laravel response for validation errors
                  ajax_error_validation(params.that, xhr.responseJSON);
                } else {
                  ajax_error_text(params.that, textStatus + ': ' + errorThrown);
                }
              }
            });
  };

  function ajax_reset_form(form, excluded, included) {
    var inputs = form.find('input:not(:button, :submit, :reset, :radio, :checkbox, [type=hidden]), select, textarea');
    var options = form.find('input:radio, input:checkbox');

    if (included) {
      inputs.each(function() {
        if ($(this).is(included)) {
          $(this).val('');
        }
      });
      options.each(function() {
        if ($(this).is(included)) {
          $(this).removeAttr('checked').removeAttr('selected');
        }
      });
    } else if (excluded) {
      inputs.not(excluded).val('');
      options.not(excluded).removeAttr('checked').removeAttr('selected');
    } else {
      inputs.val('');
      options.removeAttr('checked').removeAttr('selected');
    }
  };

  function ajax_lock(that) {
    $('[type=submit]', that).prop('disabled', true);

    that.lock_timer = window.setTimeout(function() {
      $(htmlLoading).prependTo(that.closest(ajaxLockClass)).focus();

      $(ajaxLockedClass, that).on('keydown', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var code = e.keyCode ? e.keyCode : e.which;
                if (code == 27) { // ESC
                  ajax_unlock(that);
                }
                return false;
              });
    }, variables.lock_time);
    return true;
  };

  function ajax_unlock(that) {
    window.clearTimeout(that.lock_timer);
    ajax_clear_messages(that);
    $('[type=submit]', that).prop('disabled', false);
    $(ajaxLockedClass, that).remove();
    return true;
  };

  function ajax_abort(that) {
    if (/^sync-/.test(that.data('ajaxQueue')) && $.ajaxq.isRunning(that.data('ajaxQueue'))) {
      $.ajaxq.abort(that.data('ajaxQueue'));
    }
  };

  function scrollIntoView(target, margin) {
    margin = margin || 20;
    var start = window.scrollY || window.pageYOffset;
    var stop;

    switch (typeof target) {
      case 'number':
        stop = start + target;
        target = null;
        break;
      case 'object':
        break;
      case 'string':
        target = document.querySelector(target);
        break;
    }

    if (target) {
      if (isElementInViewport(target)) {
        return;
      }

      stop = target.getBoundingClientRect().top + start;
    }

    var end = stop - margin;

    if ('scrollBehavior' in document.documentElement.style || typeof Tweezer !== 'function') {
      window.scrollTo(0, end); // target.scrollIntoView({block: 'start', behavior: 'smooth'})
    } else {
      new Tweezer({
        start: start,
        end: end,
      }).on('tick', function(v) {
        window.scrollTo(0, v);
      }).begin();
    }

    if (target) {
      target.setAttribute('tabindex', '-1');
      target.focus();
    }
  }

  function ajax_message(that, msg, method) {
    var method = method || 'insertBefore';
    var $messages;

    var container = that.data('alertContainer');
    if (container) {
      method = 'prependTo';
      that = $('.' + container);
      alertMessagesHtmlStart = alertMessagesAbsoluteHtmlStart;
    }

    $messages = that.prev(alertMessagesClass);
    if ($messages.length > 0) {
      $messages.append(msg)
    } else {
      $messages = that.next(alertMessagesClass);
      if ($messages.length > 0) {
        $messages.append(msg)
      } else {
        $(alertMessagesClass).remove();
        $messages = $(alertMessagesHtmlStart + msg + alertMessagesHtmlEnd);
        $messages[method](that);
      }
    }

    if (!isElementInViewport($messages[0])) {
      // $.scrollTo($messages[0]);
      scrollIntoView($messages[0]);
    }
  };

  function ajax_clear_messages(that, group) {
    if (group) {
      that.prev(alertMessagesClass).find(group).remove();
      that.next(alertMessagesClass).find(group).remove();
    } else {
      that.prev(alertMessagesClass).empty();
      that.next(alertMessagesClass).empty();
    }
  };

  function ajax_clear(that) {
    $(glyphiconRemoveSpan, that).remove();
    $(formGroupClass, that).removeClass(hasErrorClasses);
  };

  function ajax_error_validation(that, data) {
    ajax_clear(that);

    var msg = '';

    if (typeof data == 'object') {
      msg += errorWrapperHtmlStart + errorMessageHtmlStart;
      for (var key in data) {
        if ($('#input-' + key).next().hasClass(inputGroupAddonClass)) {
          $('#input-' + key).after(glyphiconRemove).closest(formGroupClass).addClass(hasErrorClasses);
        } else {
          if ($('#input-' + key).closest('form').hasClass('form-inline')) {
            $('#input-' + key).closest(formGroupClass).addClass(hasErrorClass);
          } else {
            $('#input-' + key).closest(formGroupClass).addClass(hasErrorClass).append(glyphiconRemove);
          }
        }

        for (var i = 0; i < data[key].length; i++) {
          msg += errorListHtmlStart + glyphiconWarning + data[key][i] + errorListHtmlEnd;
        }
      }
      msg += errorMessageHtmlEnd + errorWrapperHtmlEnd;
    }

    ajax_message(that, msg);
  };

  function ajax_reset(that, data) {
    var excluded = [];
    var included = [];
    var i;

    if (data.resetOnly) {
      for (i = 0; i < data.resetOnly.length; i++) {
        included.push('#input-' + data.resetOnly[i]);
      }
      if (included.length) {
        ajax_reset_form(that, null, included.join());
      }
    } else if (data.resetExcept) {
      for (i = 0; i < data.resetExcept.length; i++) {
        excluded.push('#input-' + data.resetExcept[i]);
      }
      if (excluded.length) {
        ajax_reset_form(that, excluded.join());
      }
    } else if (data.reset) {
      ajax_reset_form(that);
    }

    if (data.resetMultiselect) {
      $.each(data.resetMultiselect, function(key, value) {
        var $multiselect = $('#' + key);

        if ($.inArray('empty', value) !== -1) {
          $multiselect.empty();
        }

        if ($.inArray('disable', value) !== -1) {
          $multiselect.multiselect('disable');
        }

        if ($.inArray('refresh', value) !== -1) {
          $multiselect.multiselect('refresh');
        }
      });
    }

    if (data.resetRecaptcha) {
      grecaptcha.reset();
    }
  }

  function ajax_error(that, data) {
    ajax_clear(that);

    var msg = '';
    if (typeof data == 'object') {
      if (data.errors) {
        msg += errorWrapperHtmlStart + errorMessageHtmlStart;
        for (var i = 0; i < data.errors.length; i++) {
          msg += errorListHtmlStart + glyphiconWarning + data.errors[i] + errorListHtmlEnd;
        }
        msg += errorMessageHtmlEnd + errorWrapperHtmlEnd;
      }

      if (data.ids) {
        for (var i = 0; i < data.ids.length; i++) {
          if ($('#input-' + data.ids[i]).next().hasClass(inputGroupAddonClass)) {
            $('#input-' + data.ids[i]).after(glyphiconRemove).closest(formGroupClass).addClass(hasErrorClasses);
          } else {
            if ($('#input-' + data.ids[i]).closest('form').hasClass('form-inline')) {
              $('#input-' + data.ids[i]).closest(formGroupClass).addClass(hasErrorClass);
            } else {
              $('#input-' + data.ids[i]).closest(formGroupClass).addClass(hasErrorClass).append(glyphiconRemove);
            }
          }
        }
      }
    }

    ajax_message(that, msg);
  };

  function ajax_error_text(that, msg) {
    ajax_clear(that);
    msg = errorWrapperHtmlStart + glyphiconWarning + msg + errorWrapperHtmlEnd;
    ajax_message(that, msg);
  };

  function ajax_success_text(that, msg) {
    ajax_clear(that);
    msg = successWrapperHtmlStart + msg + successWrapperHtmlEnd;
    ajax_message(that, msg);
  };

  return {run: run, variables: variables, bookDates: bookDates, slidersSetup: slidersSetup, multiselectSetup: multiselectSetup, scrollIntoView: scrollIntoView}
}();
