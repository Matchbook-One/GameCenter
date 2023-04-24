/** @namespace humhub */
// eslint-disable-next-line no-unused-vars
humhub.module('gamecenter', function (module, requireModule, $) {
  const client = requireModule('client')
  const moduleRegex = /humhub\.modules\.(.*)/

  /**
   * @param {string} module
   * @param {number} playerID
   * @param {number} score
   * @return {Promise<unknown>}
   */
  const submitScore = (module, playerID, score) => {
    const url = '/gamecenter/score/create'
    const payload = {
      module: module.match(moduleRegex)[1],
      playerID,
      score
    }

    return client.post(url, { data: payload })
  }

  /**
   *
   * @param {string} moduleId the module id
   * @param {number} player the id of the user playing
   */
  const startGame = (moduleId, player) => {
    const url = '/gamecenter/report/start'
  }

  module.export({ submitScore, startGame })

})
