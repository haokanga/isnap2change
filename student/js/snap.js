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

    }
}
