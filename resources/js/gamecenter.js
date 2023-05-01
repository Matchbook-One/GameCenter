// noinspection JSUnusedLocalSymbols,JSUnusedGlobalSymbols

/** @namespace humhub */
humhub.module('gamecenter', function (module, requireModule, $) {

  const client = requireModule('client')

  class GameCenter {
    /**
     * @private
     * @type {RegExp}
     */
    moduleRegex = /humhub\.modules\.(.*)/

    /**
     * @param {string} module
     * @param {number} score
     * @return {Promise<unknown>}
     */
    submitScore(module, score) {
      const url = '/gamecenter/score/create'
      const payload = {
        module: module.match(this.moduleRegex)[1],
        score
      }

      return client.post(url, { data: payload })
    }

    /**
     * @param {string} moduleId the module id
     * @returns {Promise<unknown>}
     */
    startGame(moduleId) {
      const url = '/gamecenter/report/start'
      const config = {
        data: { module: moduleId.match(this.moduleRegex)[1], 'hallo': 'velo' }
      }
      return client.post(url, config)
    }

    /**
     * @param {string} moduleId the module id
     * @returns {Promise<unknown>}
     */
    endGame(moduleId) {
      const url = '/gamecenter/report/end'
      const payload = { module: moduleId.match(this.moduleRegex)[1] }

      return client.post(url, { data: payload })
    }

    /**
     *
     * @param {string} moduleId the module id
     * @param {string} option
     * @param {any} value
     * @returns {Promise<unknown>}
     */
    report(moduleId, option, value) {
      const url = '/gamecenter/report/report'
      const payload = {
        module: moduleId.match(this.moduleRegex)[1],
        option,
        value
      }

      return client.post(url, { data: payload })
    }

    /**
     * @param {string} moduleId
     * @param {string} text
     * @returns {Promise<unknown>}
     */
    share(moduleId, text) {
      const url = '/gamecenter/share'
      const payload = {
        module: moduleId.match(this.moduleRegex)[1],
        message: text
      }
      return client.post(url, { data: payload })
    }
  }

  module.export = GameCenter

})
