// noinspection JSUnusedLocalSymbols,JSUnusedGlobalSymbols

/** @namespace humhub */
humhub.module('gamecenter', function (module, requireModule, $) {

  const client = requireModule('client')
  const uiStatus = requireModule('ui.status')

  class GameCenter {
    /**
     * @private
     * @type {RegExp}
     */
    moduleRegex = /humhub\.modules\.(.*)/
    /**
     *  @private
     *  @type {string}
     */
    module

    constructor(module) {
      this.module = module.match(this.moduleRegex)[1]
    }

    /**
     * @returns {Promise<{achievements: Array<Achievement>}>}
     */
    loadAchievements() {
      const url = '/gamecenter/achievements/load'
      const payload = {
        module: this.module
      }
      return client.post(url, { data: payload })
    }

    /**
     * @param {Achievement} achievement
     * @returns {Promise<Achievement>}
     */
    updateAchievement(achievement) {
      const url = '/gamecenter/achievements/update'
      const payload = {
        module: this.module,
        achievement: achievement
      }
      return client.post(url, { data: payload })
    }

    /**
     * @returns {Promise<void>}
     */
    startGame() {
      const url = '/gamecenter/report/start'
      const config = {
        data: { module: this.module }
      }
      return client.post(url, config)
    }

    /**
     * @returns {Promise<void>}
     */
    endGame() {
      const url = '/gamecenter/report/end'
      const payload = { module: this.module }

      return client.post(url, { data: payload })
    }

    /**
     *
     * @param {string} option
     * @param {any} value
     * @returns {Promise<void>}
     */
    report(option, value) {
      const url = '/gamecenter/report/report'
      const payload = {
        module: this.module,
        option,
        value
      }

      return client.post(url, { data: payload })
    }

    /**
     * @param {number} score
     * @return {Promise<void>}
     */
    submitScore(score) {
      const url = '/gamecenter/score/create'
      const payload = {
        module: this.module,
        score
      }

      return client.post(url, { data: payload })
    }

    getHighScore() {
      const url = '/gamecenter/score/highscore'
      const payload = {
        module: this.module
      }

      return client.post(url, { data: payload })
    }

    /**
     * @param {string} text
     * @returns {Promise<void>}
     */
    share(text) {
      const url = '/gamecenter/share'
      const payload = {
        module: moduleId.match(this.moduleRegex)[1],
        message: text
      }
      return client.post(url, { data: payload })
                   .then(() => {uiStatus.success(module.text('saved'))})
    }

  }

  module.export = GameCenter

})
/**
 * @typedef {object} Achievement
 * @property {string} achievement
 * @property {string} game
 * @property {string} lastUpdated
 * @property {number} percentCompleted
 */