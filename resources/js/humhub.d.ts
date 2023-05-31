import { GameCenterModule } from './gamecenter'

export namespace humhub {


  /**
   * All log functions accept up to three arguments:
   *
   * @param {string} text The actual message (or text key)
   * @param {any} details Details about the message this could be an js object an error or a client response object
   * @param {boolean} setStatus A flag, which will trigger a global `humhub:modules:log:setStatus` event.
   * This can be used to trigger the status bar for providing user feedback.
   * @returns {void}
   */
  type LogFunction = (text: string, details: any, setStatus: boolean) => void

  interface Logger {
    trace: LogFunction,
    debug: LogFunction,
    info: LogFunction,
    success: LogFunction,
    warn: LogFunction,
    error: LogFunction,
    fatal: LogFunction
  }

  class Module<T> {
    require: Require
    initOnPjaxLoad: boolean
    isModule: boolean
    id: string
    config: Record<string, any>
    log: Logger

    text(key: string): string

    export(exports: Record<string, Function>): void
  }


  /**
   * @param {string} moduleId
   * @param {boolean} lazy
   * @returns {Module}
   */
  type Require = {
    (moduleNS: string, lazy?: boolean): Module<unknown>,
    (moduleNS: 'event', lazy?: boolean): Module<EventModule>,
    (moduleNS: 'client', lazy?: boolean): Module<ClientModule>,
    (moduleNS: 'client.pjax', lazy?: boolean): Module<ClientPjaxModule>,
    (moduleNS: 'ui', lazy?: boolean): Module<UiModule>,
    (moduleNS: 'ui.additions', lazy?: boolean): Module<UiAdditionsModule>,
    (moduleNS: 'ui.status', lazy?: boolean): Module<UiStatusModule>,
    (moduleNS: 'ui.view', lazy?: boolean): Module<UiViewModule>,
    (moduleNS: 'user', lazy?: boolean): Module<UserModule>,
    (moduleNS: 'util', lazy?: boolean): Module<UtilModule>,
    (moduleNS: 'gamecenter', lazy?: boolean): Module<GameCenterModule>
  }

  type EventFunction = {
    (events: string, selector: string, handler: Function): void, (events: string, handler: Function): void
  }
  type EventDataFunction = {
    (event: string, handler: Function): void,
    (event: string, data: object, handler: Function): void,
    (event: string, selector: string, data: object, handler: Function): void
  }

  class Widget {
    componentData: string
    widget: string

    loader(show): void

    reload(options): unknown

    getReloadOptions(): unknown[]

    replace(dom): void

    initWidgetEvents(): void

    fire(event: unknown, args: unknown, triggerDom: unknown): void

    getDefaultOptions(): void

    validate(): boolean

    isVisible(): boolean

    init(): void

    initOptions(options: unknown): void

    statusError(title: string): void

    statusInfo(title: string): void

    show(): void

    hide(): void

    fadeOut(): Promise<unknown>

    fadeIn(): Promise<unknown>

    exists(ns: string): boolean

  }

  class Response {
    /** the url of your call */
    url: string
    /** the result status of the xhr object */
    status: unknown
    /** The server response: Either a json object or html depending on the 'dataType' setting of your call. */
    response: any
    /** In case of an error: "timeout", "error", "abort", "parsererror" or "application" */
    textStatus: 'timeout' | 'error' | 'abort' | 'parsererror' | 'application'
    /** the expected response dataType of your call */
    dataType: string
    /** the response depending on dataType */
    html?: string
    xml?: string
  }


  interface UiModule {
    widget: {
      Widget: Widget
      init(): void
      sortOrder: number
    }
  }

  interface UiViewModule {
    sortOrder: number

    getContentTop(): number

    getHeight(): number

    getState(): JQueryStatic

    getTitle(): string

    getViewContext(): unknown

    getWidth(): number

    init(pjax: boolean): void

    isActiveScroll(): boolean

    isLarge(): boolean

    isMedium(): boolean

    /** @deprecated */
    isNormal(): boolean

    isSmall(): boolean

    preventSwipe(prev): void

    setState(moduleID: string, controllerID: string, action): void

    setViewContext(vctx: unknown): void

    unload(): void
  }

  interface UiAdditionsModule {
    sortOrder: number,

    apply(element: HTMLElement | JQueryStatic, id: string, selector: string): void,

    applyTo(element: HTMLElement | JQueryStatic, options): void,

    extend(id, handler, options): void,

    highlight(node: HTMLElement): void,

    init(): void,

    observe(node, options): JQueryStatic,

    register(id: number, selector: Function, handler, options: object): void,

    switchButtons(outButton, inButton, cfg): void,

    unload(): void
  }

  interface UiStatusModule {

    info(msg: string, closeAfter?: number)

    success(msg: string, closeAfter?: number)

    warn(msg: string, error: unknown, closeAfter?: number)

    error(msg: string, error: unknown, closeAfter?: number)

    setContent(content, error)

    toggle(error?: unknown)

    show(callback?: () => void)

    hide(callback?: () => void)
  }

  interface UtilModule {
    object: {
      chain(thisObj): unknown,
      debounce(func, wait, immediate): unknown,
      defaultValue(obj: unknown, defaultValue: unknown): unknown,
      extendable(options): unknown,
      inherits(Sub, Parent, options): unknown,
      isArray(obj: unknown): boolean,
      isBoolean(obj: unknown): boolean,
      isDefined(obj: object): unknown,
      isEmpty(obj: unknown): boolean,
      isFunction(obj: unknown): boolean,
      isJQuery(obj: unknown): boolean,
      isNumber(n: unknown): boolean,
      isObject(obj: unknown): boolean,
      isString(obj: unknown): boolean,
      resolve(obj: unknown, ns: string, init: unknown): unknown,
      sort(arr, field): unknown,
      swap(json): unknown
    },
    string: {
      capitalize(text: string): string,
      capitalizeFirstLetter(s: string): string,
      cutPrefix(text: string, prefix: string): string,
      cutSuffix(val: string, suffix: string): string,
      decode(value: string): unknown,
      encode(value: string): unknown,
      endsWith(val, suffix): boolean,
      escaleHtml(text: string, simple): string,
      htmlDecode(value: string): unknown,
      htmlEncode(value: string): unknown,
      lowerCaseFirstLetter(s: string): unknown,
      startsWith(val: string, prefix: string): boolean,
      template(tmpl: string, config: object): unknown
    },
    array: { move(array: Array<unknown>, oldIndex: number, newIndex: number): Array<unknown> },
    url: { getUrlParameter(search: string): string | undefined }
  }

  interface UserModule {
  }

  interface EventModule {
    events: typeof jQuery
    off: EventFunction
    on: EventDataFunction
    one: EventDataFunction

    sub(target: unknown): unknown

    trigger(eventType: unknown, extraParameters: unknown): unknown

    triggerCondition(target: unknown, event: unknown, extraParameters: unknown): unknown
  }

  interface ClientPjaxModule {
    sortOrder: number

    init(): void,

    post(event: unknown): void,

    redirect(url: string): void,

    reload(): void,

    isActive(): boolean,

    installLoader(): void,
  }

  class ClientModule {
    config: {
      baseUrl: string, reloadableScripts: string[], text: object
    }
    id: string
    initOnPjaxLoad: boolean
    isModule: boolean
    log: Logger
    pjax: {
      require: Require, initOnPjaxLoad: boolean, isModule: boolean, id: string
      config: object
    }
    require: Require
    sortOrder: number

    public actionPost(evt: unknown): unknown

    public ajax(url: string, cfg: object, originalEvent?: unknown): Promise<Response>

    public back(): unknown

    public export(exports: object): unknown

    public get(url: string, cfg, originalEvent): Promise<Response>

    public html(url: string, cfg, originalEvent): Promise<Response>

    public init(isPjax: boolean): unknown

    public json(url: string, cfg: object, originalEvent: Event): unknown

    public offBeforeLoad(): unknown

    public onBeforeLoad(form, msg, msgModal): unknown

    public post(url: string, cfg: object, originalEvent?): Promise<unknown>

    public redirect(url): unknown

    public reload(preventPjax): unknown

    public submit($form, cfg, originalEvent): unknown

    public text(key: string): string

    public unloadForm($form, msg): unknown
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
  type module = (id, moduleFunction: (module: Module<unknown>, require: Require, $: typeof jQuery) => void) => void
}
