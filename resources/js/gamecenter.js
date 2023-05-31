// noinspection JSUnusedLocalSymbols,JSUnusedGlobalSymbols

/** @namespace humhub */
humhub.module('gamecenter', (module, getModule, $) => {

  /** @type {humhub.Module<humhub.ClientModule>} */
  const client = getModule('client')

  /** @type {humhub.Module<humhub.UiStatusModule>} */
  const uiStatus = getModule('ui.status')


  class GameCenter {
    /**
     *  @private
     *  @type {string}
     */
    module

    /**
     * @param {string} module
     */
    constructor(module) {
      this.module = module.match(moduleRegex)[1]
    }

    /**
     * @returns {Promise<{achievements: Array<Achievement>}>}
     */
    loadAchievements() {
      const url = '/gamecenter/achievement/load'
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
      const url = '/gamecenter/achievement/update'
      achievement.percentCompleted = clamp(achievement.percentCompleted, 0, 100)

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
      const url = '/gamecenter/api/highscore'
      const payload = {
        module: this.module
      }

      return client.post(url, { data: payload })
    }

    /**
     * @param {string} message
     * @returns {Promise<void>}
     */
    async share(message) {
      const url = '/gamecenter/share'
      const payload = {
        message: message
      }
      await client.post(url, { data: payload })
                  .then(() => {
                    uiStatus.success(module.text('saved'))
                  })
    }

    loadLeaderboards() {
      const url = '/gamecenter/api/leaderboard'
      const payload = {
        module: this.module
      }

      return client.post(url, { data: payload })
    }
  }

  /**
   *
   * @param {number} value
   * @param {number} min
   * @param {number} max
   * @return {number}
   */
  function clamp(value, min, max) {
    const bigger = Math.max(min, max)
    const smaller = Math.min(min, max)
    return Math.max(smaller, Math.min(value), bigger)
  }

  /** @var {GameCenter} instance */
  let instance

  /**
   *
   * @param {string} moduleId
   * @returns {GameCenter}
   */

  module.export({
                  shared: (moduleId) => {
                    if (!instance) {
                      const moduleRegex = /humhub\.modules\.(.*)/
                      instance = new GameCenter(moduleId.match(moduleRegex)[1])
                    }

                    return instance
                  }
                })
})
/**
 * @typedef {object} Achievement
 * @property {string} achievement
 * @property {string} game
 * @property {string} lastUpdated
 * @property {number} percentCompleted
 */
