import * as jQuery from 'jQuery'


declare namespace humhub {

  /**
   * All log functions accept up to three arguments:
   *
   * @param {string} text The actual message (or text key)
   * @param {any} details Details about the message this could be an js object an error or a client response object
   * @param {boolean} setStatus A flag, which will trigger a global `humhub:modules:log:setStatus` event. This can be used to trigger the status bar for providing user feedback.
   * @returns {void}
   */
  type LogFunction = (text: string, details: any, setStatus: boolean) => void

  type EventFunction = {
    (events: string, selector: string, handler: Function): void,
    (events: string, handler: Function): void
  }
  type EventDataFunction = {
    (event: string, handler: Function): void,
    (event: string, data: object, handler: Function): void,
    (event: string, selector: string, data: object, handler: Function): void
  }

  interface GameCenterModule extends Module {
    config: {}
    export: (exports: object) => void
    id: 'humhub.modules.gamecenter'
    initOnPjaxLoad: false
    isModule: true
    log: Logger
    require: Require
    submitScore: () => Promise<unknown>
    text: (key: string) => string
  }

  interface EventModule extends Module {
    events: typeof jQuery
    off: EventFunction
    on: EventDataFunction
    one: EventDataFunction
    sub: (target: unknown) => unknown
    trigger: (eventType: unknown, extraParameters: unknown) => unknown
    triggerCondition: (target: unknown, event: unknown, extraParameters: unknown) => unknown
  }

  class Response {
    /** the url of your call */
    url: string
    /** the result status of the xhr object */
    status: unknown
    /** the server response, either a json object or html depending of the 'dataType' setting of your call. */
    response: any
    /** In case of error: "timeout", "error", "abort", "parsererror", "application" */
    textStatus: 'timeout' | 'error' | 'abort' | 'parsererror' | 'application'
    /** the expected response dataType of your call */
    dataType: string
    /** the response depending on dataType */
    html?: string
    xml?: string
  }

  interface ClientModule extends Module {
    actionPost: (evt: unknown) => unknown
    ajax: (url: string, cfg: object, originalEvent?: unknown) => Promise<Response>
    back: () => unknown
    config: {
      baseUrl: string,
      reloadableScripts: string[],
      text: object
    }
    export: (exports: object) => unknown
    get: (url: string, cfg, originalEvent) => Promise<Response>
    html: (url: string, cfg, originalEvent) => Promise<Response>
    id: string
    init: (isPjax: boolean) => unknown
    initOnPjaxLoad: boolean
    isModule: boolean
    json: (url: string, cfg: object, originalEvent: Event) => unknown
    log: Logger
    offBeforeLoad: () => unknown
    onBeforeLoad: (form, msg, msgModal) => unknown
    pjax: {
      require: Require,
      initOnPjaxLoad: boolean,
      isModule: boolean,
      id: string
      config: object
    }
    post: (url: string, cfg: object, originalEvent?) => Promise<unknown>
    redirect: (url) => unknown
    reload: (preventPjax) => unknown
    require: Require
    sortOrder: number
    submit: ($form, cfg, originalEvent) => unknown
    text: (key: string) => string
    unloadForm: ($form, msg) => unknown
  }

  interface Module {
    require: Require
    initOnPjaxLoad: boolean
    isModule: boolean
    id: string
    config: Record<string, any>
    text: (key: string) => string
    export: (exports: Record<string, Function>) => void,
    log: Logger
  }

  interface Logger {
    trace: LogFunction,
    debug: LogFunction,
    info: LogFunction,
    success: LogFunction,
    warn: LogFunction,
    error: LogFunction,
    fatal: LogFunction
  }

  /**
   * @param {string} moduleId
   * @param {boolean} lazy
   * @returns {Module}
   */
  type Require = {
    (moduleNS: string, lazy?: boolean): Module,
    (moduleNS: 'client', lazy?: boolean): ClientModule
    (moduleNS: 'event', lazy?: boolean): EventModule
    (moduleNS: 'gamecenter', lazy?: boolean): GameCenterModule
  }

  /**
   * Adds a module to the humhub.modules namespace.
   *
   * The module id can be provided either as
   *
   * - full namespace humhub.modules.ui.modal
   * - or modules.ui.modal
   * - or short ui.modal
   *
   * Usage:
   *
   * ```
   * humhub.module('ui.modal', function(module, require, $) {...});
   * ```
   *
   * This would create an empty ui namespace (if not already created before) register the given module `ui.modal`.
   *
   * The module can export functions and properties by using:
   *
   * ```
   * module.myFunction = function() {...}
   *
   * or
   *
   * module.export({
   *  myFunction: function() {...}
   * });
   * ```
   *
   * The export function can be called as often as needed (but should be called once at the end of the module).
   * Its also possible to export single classes e.g.:
   *
   * ```
   * humhub.module('my.LoaderWidget', function(module, require, $) {
   *    var LoaderWidget = function() {...}
   *
   *    module.export = LoaderWidget;
   * });
   * ```
   *
   * A module can provide an `init` function, which by default is only called after the first initialization
   * e.g. after a full page load when the document is ready or when loaded by means of ajax.
   * In case a modules `init` function need to be called also after each `pjax` request, the modules `initOnPjaxLoad` has to be
   * set to `true`:
   *
   * ```
   * module.initOnPjaxLoad = true;
   * ```
   *
   * ## Dependencies
   *
   * The core modules are initialized in a specific order to provide the required dependencies for each module.
   * The order is given by the order of module calls and in case of core modules configured in the API's AssetBundle.
   *
   * A module can be received by using the required function within a module function.
   * You can either depend on a module at initialization time or within your functions or use the lazy flag of the require function.
   *
   * Usage:
   *
   * ```
   * var modal = require('ui.modal');
   *
   * or lazy require
   *
   * var modal = require('ui.modal', true);
   * ````
   * @function module:humhub.module
   * @access public
   * @param {string} id the namespaced id
   * @param {(instance: Module, require: Require, $: JQuery) => void} moduleFunction
   * @returns {void}
   *
   * ## Module Lifecycle
   *
   * A module runs through the following lifecycle (by the example of our example module):
   *
   * ### Full Page load
   * Calling humhub.module - the module is registered but not initialized
   *
   * ### Document Ready
   * humhub:beforeInitModule
   * humhub:modules:example:beforeInit
   *
   * ### Calling the modules init function
   * humhub:modules:example:afterInit
   * humhub:afterInitModule
   * humhub:ready
   *
   * ### Pjax call
   * humhub:modules:client:pjax:beforeSend
   *
   * ### Calling the modules unload function
   * humhub:modules:client:pjax:success
   *
   * Reinitialize all modules with `initOnPjaxLoad=true` by calling init with `isPjax = true`
   *
   * @see https://docs.humhub.org/docs/develop/javascript-index#module-lifecycle
   */
  type module = (id, moduleFunction: (module: Module, require: Require, $: typeof jQuery) => void) => void
}
