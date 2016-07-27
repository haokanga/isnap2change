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
'        <div class="snap-alert-operation">' +
'            <div class="snap-alert-btn snap-alert-confirm">OK</div>' +
'        </div>' +
'    </div>' +
'</div>';


        if (!this.$alert) {
            this.$alert = $(alertTpl)
            var $body = $('body')
            $body.append(this.$alert)
            this.$alertContent = this.$alert.find('.snap-alert-content')
            this.$alert.on('click', '.snap-alert-confirm', function () {
                snap.$alert.hide()
                snap.alert.onClose()
            })
        }
        this.alert.onClose = opt.onClose
        this.$alertContent.text(opt.content)
        this.$alert.show()

    },

    confirm: function (opt) {
        opt = $.extend({
            content: 'Missing title!',
            onConfirm: $.noop,
            onCancel: $.noop
        }, opt)
        var confirmTpl =
'<div class="snap-alert">' +
'    <div class="snap-alert-mask"></div>' +
'    <div class="snap-alert-container">' +
'        <div href="#" class="snap-alert-logo"></div>' +
'        <div class="snap-alert-content">content here</div>' +
'        <div class="snap-alert-operation">' +
'            <div class="snap-alert-btn snap-alert-confirm">OK</div>' +
'            <div class="snap-alert-btn snap-alert-cancel">Cancel</div>' +
'        </div>' +
'    </div>' +
'</div>';



        if (!this.$confirm) {
            this.$confirm = $(confirmTpl)
            var $body = $('body')
            $body.append(this.$confirm)
            this.$confirmContent = this.$confirm.find('.snap-alert-content')
            this.$confirm.on('click', '.snap-alert-confirm', function () {
                snap.$confirm.hide()
                snap.confirm.onConfirm()
            })
            this.$confirm.on('click', '.snap-alert-cancel', function () {
              snap.$confirm.hide()
              snap.confirm.onCancel()
            })
        }
        this.confirm.onConfirm = opt.onConfirm
        this.confirm.onCancel = opt.onCancel
        this.$confirmContent.text(opt.content)
        this.$confirm.show()
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
    },
    addListeners: function () {
      var that = this
      this.$main.on('click', '.attachment-nav-item', function (e) {
        var itemIndex = that.$navItems.index(e.currentTarget)
        that.activeItem(itemIndex)
      })

    },
    activeItem: function (index) {
      window.open(this.$navItems.eq(index).data('url'), null, 'width=800,height=600,toolbar=no,location=no')

    },
    resetItem: function () {
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


snap.Pagination = function (opt) {
  this.init(opt)
}
snap.Pagination.prototype = {
    init: function (opt) {
        this.opt = $.extend({
          main: '.pagination',
          onChange: $.noop,
          index: 0
        }, opt)
        this.cacheElements()
        this.addListeners()
        this.activeItem(this.opt.index)
    },
    cacheElements: function () {
      var $main = $(this.opt.main)
      this.$main = $main
      this.$items = $main.find('.pagination-nav-item')
      this.$prev = $main.find('.pagination-prev')
      this.$next = $main.find('.pagination-next')
    },
    addListeners: function () {
      var that = this
      this.$main.on('click', '.pagination-nav-item', function (e) {
        var index = that.$items.index(e.currentTarget)
        that.activeItem(index)
        that.opt.onChange(index)
      })
      this.$prev.on('click', function (e) {
        var index = that.opt.index - 1
        if (index >= 0) {
          that.activeItem(index)
          that.opt.onChange(index)
        }
      })
      this.$next.on('click', function (e) {
        var index = that.opt.index + 1
        if (index < that.$items.length) {
          that.activeItem(index)
          that.opt.onChange(index)
        }
      })
    },
    activeItem: function (index) {
      this.opt.index = index
      this.$items.removeClass('active')
        .eq(index)
        .addClass('active')
    }
}


snap.QuizNav = function (opt) {
  this.init(opt)
}
snap.QuizNav.cls = {
  navItemActive: 'quiz-nav-item-inverted',
  navItemFilled: 'quiz-nav-item-filled',
  quizItemActive: 'quiz-item-active',

}
snap.QuizNav.prototype = {
    tpl: {
      correct: '<span class="quiz-nav-state quiz-nav-state-correct"></span>',
      incorrect: '<span class="quiz-nav-state quiz-nav-state-incorrect"></span>'
    },
    init: function (opt) {
        this.opt = $.extend({
          navList: '.quiz-nav-list',
          quizList: '.quiz-list',
          index: 0
        }, opt)
        this.cacheElements()
        this.addListeners()
        this.activeItem(this.opt.index)
    },
    cacheElements: function () {
      var $nav = $(this.opt.navList)
      this.$nav = $nav
      this.$navItems = $nav.find('.quiz-nav-item')
      var $quizList = $(this.opt.quizList)
      this.$quizList = $quizList
      this.$quizItems = $quizList.find('.quiz-item')
    },
    addListeners: function () {
      var that = this

      var $doc = $(document)
      $doc.on('click', '.quiz-nav-item', function (e) {
        that.activeItem(that.$navItems.index(e.currentTarget))
      })
      $doc.on('click', '.quiz-nav-prev', function () {
        if (that.opt.index > 0) {
          that.activeItem(that.opt.index - 1)
        }
      })
      $doc.on('click', '.quiz-nav-next', function () {
        if (that.opt.index < that.$navItems.length - 1) {
          that.activeItem(that.opt.index + 1)
        }
      })
    },
    activeItem: function (index) {
      this.opt.index = index
      this.$navItems.removeClass(snap.QuizNav.cls.navItemActive)
        .eq(index)
        .addClass(snap.QuizNav.cls.navItemActive)

      this.$quizItems.removeClass(snap.QuizNav.cls.quizItemActive)
        .eq(index)
        .addClass(snap.QuizNav.cls.quizItemActive)
    },
    fillItem: function (index) {
      this.$navItems.eq(index)
        .addClass(snap.QuizNav.cls.navItemFilled)
    },
    unfillItem: function (index) {
      this.$navItems.eq(index)
        .removeClass(snap.QuizNav.cls.navItemFilled)
    },
    feedback: function (index, isCorrect) {

      this.$navItems.eq(index)
        .append(isCorrect ? this.tpl.correct : this.tpl.incorrect)
    }
}
