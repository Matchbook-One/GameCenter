humhub.module('gamecenter', function (module, require, $) {
  module.export({
    // uncomment the following line in order to call the init() function also for each pjax call
    // initOnPjaxLoad: true,
    init: () => {
      console.log('gamecenter module activated')
    },
    hello: () => {
      alert(`${module.text('hello')} - ${module.config.username}`)
    }
  })
})
