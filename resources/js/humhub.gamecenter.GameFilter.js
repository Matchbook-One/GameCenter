humhub.module('gamecenter.GameFilter', function (module, require, $) {

  const Widget = require('ui.widget.Widget')
  const util = require('util')

  class GameFilter extends Widget {
    constructor() {
      super()
      this.$permissions = $('.permission-grid-editor')
      this.initDropdown()

      const activeModule = util.url.getUrlParameter('module')

      if (activeModule && this.modules.indexOf(activeModule) > -1) {
        $.val(activeModule).trigger('change')
      }
    }

    initDropdown() {
      this.modules = []
      this.$permissions.find('[data-module-id]').each(function () {
        const $this = $(this)
        const id = $this.attr('data-module-id')
        if (this.modules.indexOf(id) < 0) {
          $.append('<option value="' + id + '">' + $this.text() + '</option>')
          this.modules.push(id)
        }
      })
    }


    filterModule(moduleId) {
      const showAll = moduleId === 'all'

      $('.permission-group-tabs').find('a').each(function () {
        const $this = $(this)
        let original = $this.data('originalUrl')

        if (!original) {
          original = $this.attr('href')
          $this.data('originalUrl', original)
        }

        $this.attr('href', !showAll ? original + '&module=' + moduleId : original)
      })

      if (showAll) {
        this.$permissions.find('tr').show()
        return
      }

      this.$permissions
          .find('[data-module-id]')
          .each(() => {
            const id = $(this).attr('data-module-id')

            const $row = $(this).closest('tr')
            if (id !== moduleId) {
              $row.hide()
            } else {
              $row.show()
            }

          })
    }

    change(evt) {
      this.filterModule($.val())
    }

  }

  module.export = GameFilter
})
