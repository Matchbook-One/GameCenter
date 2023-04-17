/* global humhub */
humhub.module('gamecenter', function (module, require, $) {


  class GameCenter {
    /**
     * @param t
     */
    static report(t) {
      console.log(t)
    }

    static saveScore(score, user, game) {
      console.log(`saveScore(${score}, ${user}, ${game})`)
    }

    static getScores(user, game) {
      console.log(`getScores(${user}, ${game})`)
    }
  }

  module.export = GameCenter
})
