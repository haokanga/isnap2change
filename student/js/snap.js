var snap = {
    /**
     * 自定义alert
     * @param opt {object} 参数
     * @parma opt.content {string} 弹窗显示的文案
     * @parma opt.onClose {function} 弹窗关闭时执行的回调
     **/
    alert: function (opt) {
        opt = $.extend({
            content: 'Missing title!',
            onClose: $.noop
        }, opt)
        var alertTpl =
            '<div class="snap-alert">' +
            '    <div class="snap-alert-mask"></div>' +
            '    <div class="snap-alert-container">' +
            '        <a href="#" class="snap-alert-logo"></a>' +
            '        <div class="snap-alert-content">content here</div>' +
            '        <div class="snap-alert-confirm">OK</div>' +
            '    </div>' +
            '</div>';


        if (!this.$alert) {
            this.$alert = $(alertTpl)
            var $body = $('body')
            $body.append(this.$alert)
            this.$alertContent = this.$alert.find('.snap-alert-content')
            $body.on('click', '.snap-alert-confirm', function () {
                snap.$alert.hide()
                snap.alert.onClose()
            })
        }
        this.alert.onClose = opt.onClose
        this.$alertContent.text(opt.content)
        this.$alert.show()

    },

    enableBackTop: function () {
        var $win = $(window)
        var snap = this

        function normalizeShow() {
            if ($win.scrollTop() > 50) {
                snap.$backTop.show()
            } else {
                snap.$backTop.hide()
            }
        }

        if (!this.$backTop) {
            var tpl =
                '<div class="back-top">' +
                '    <div class="icon-top"></div>' +
                '    <div class="back-top-label">Back to Top</div>' +
                '</div>'
            this.$backTop = $(tpl)
            var $body = $(document.body)
            $body.append(this.$backTop)
            $body.on('click', '.back-top', function () {
                $body.animate({ scrollTop: 0 }, 'fast');
            })
            $win.on('scroll', this.throttle(normalizeShow, 50))
            normalizeShow()
        }
    },
    //  http://underscorejs.org/#throttle
    throttle: function(func, wait, options) {
        var context, args, result;
        var timeout = null;
        var previous = 0;

        var _ = {}
          _.now = Date.now || function() {
            return new Date().getTime();
          };
        if (!options) options = {};
        var later = function() {
          previous = options.leading === false ? 0 : _.now();
          timeout = null;
          result = func.apply(context, args);
          if (!timeout) context = args = null;
        };
        return function() {
          var now = _.now();
          if (!previous && options.leading === false) previous = now;
          var remaining = wait - (now - previous);
          context = this;
          args = arguments;
          if (remaining <= 0 || remaining > wait) {
            if (timeout) {
              clearTimeout(timeout);
              timeout = null;
            }
            previous = now;
            result = func.apply(context, args);
            if (!timeout) context = args = null;
          } else if (!timeout && options.trailing !== false) {
            timeout = setTimeout(later, remaining);
          }
          return result;
        };
    },
    initAttachmentCtrl: function () {
      AttachmentCtrl.init()
    }
}


var AttachmentCtrl = {
    init: function () {
        this.cacheElements()
        this.addListeners()
    },
    cacheElements: function () {
      var $main = $('.attachment')
      this.$main = $main
      this.$navItems = $main.find('.attachment-nav-item')
      this.$contentContainer = $main.find('.attachment-content-container')
      this.$contents = $main.find('.attachment-content')
      this.$close = $main.find('.attachment-close')
    },
    addListeners: function () {
      var that = this
      this.$main.on('click', '.attachment-nav-item', function (e) {
        var itemIndex = that.$navItems.index(e.currentTarget)
        that.activeItem(itemIndex)
      })

      this.$main.on('click', '.attachment-close', function (e) {
        that.resetItem()
      })
    },
    activeItem: function (index) {
      this.$navItems.addClass('attachment-nav-item-hide')
      this.$navItems.eq(index)
        .removeClass('attachment-nav-item-hide')
        .addClass('attachment-nav-item-active')

      this.$contentContainer.addClass('attachment-content-container-show')
      this.$contents.removeClass('attachment-content-show')
        .eq(index)
        .addClass('attachment-content-show')
      this.$close.addClass('attachment-close-show')
    },
    resetItem: function () {
      this.$navItems.removeClass('attachment-nav-item-hide attachment-nav-item-active')
      this.$contentContainer.removeClass('attachment-content-container-show')
      this.$contents.removeClass('attachment-content-show')
      this.$close.removeClass('attachment-close-show')
    }
}


snap.Form = function (opt) {
  this.init(opt)
}
snap.Form.prototype = {
    init: function (opt) {
        this.opt = $.extend({
          form: '.question-form',
          onSubmit: $.noop
        }, opt)
        this.cacheElements()
        this.addListeners()
    },
    cacheElements: function () {
      var $form = $(this.opt.form)
      this.$form = $form
      this.$items = $form.find('.question-item')
      this.$fields = $form.find('.question-field')
    },
    addListeners: function () {
      var that = this
      this.$form.on('submit', function (e) {
        e.preventDefault()
        that.opt.onSubmit(that.getData())
      })
    },
    setErrors: function (errors) {
      var that = this
      this.$form.find('.question-error').removeClass('question-error-show')
      errors.forEach(function (err) {
        var $error = that.$items.eq(err.index).find('.question-error')
        if (err.text) {
          $error.text(err.text)
        }
        $error.addClass('question-error-show')
      })
    },
    getData: function () {
      var dataArray = this.$form.serializeArray()
      var data = {}
      dataArray.forEach(function (field) {
        if (field.name) {
          data[field.name] = field.value
        }
      })
      return data
    }
}
