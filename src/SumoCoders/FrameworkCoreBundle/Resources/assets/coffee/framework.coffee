class DefaultObject
  # Class methods
  @events: (events) ->
    @::events ?= {}
    @::events = $.extend({}, @::events) unless @::hasOwnProperty "events"
    @::events = $.extend(true, {}, @::events, events)

  @onDomReady: (initializers) ->
    @::onDomReady ?= []
    @::onDomReady = @::onDomReady[..] unless @::hasOwnProperty "onDomReady"
    @::onDomReady.push initializer for initializer in initializers

  constructor: ->
    @_setupEventListeners()

  domReady: ->
    @_loadOnDomReadyMethods()

  _loadOnDomReadyMethods: ->
    for callback in @onDomReady
      @[callback]()

  _setupEventListeners: =>
    $document = $(document)
    for selector, actions of @events
      for action, callback of actions
        throw "#{callback} doesn't exist when trying to bind #{action} on #{selector}" unless @[callback]

        if selector == 'document'
          $document.on(action, @[callback])
        else
          $document.on(action, selector, @[callback])

window.DefaultObject = DefaultObject

class Framework extends DefaultObject
  @events
    # show action-list on iphone-size
    '#main-menu-inner .dropdown-toggle' : click : 'toggleDropdown'

    # animate scrolling
    'a.backToTop': click : 'scrollToTop'
    'a[href*="#"]': click : 'scrollTo'

    # link methods
    'a.confirm': click : 'askConfirmation'
    'button.confirm' : click : 'askConfirmationAndSubmit'

    # tabs
    '.nav-tabs a' : click : 'changeTab'

    # loading bar
    'document' :
      form_submitting : 'showLoadingBar'
      ajax_start : 'showLoadingBar'
      ajax_stop : 'hideLoadingBar'

    # search bar
    'a.toggle-searchbar': click : 'toggleSearchBar'

    #clickable table row
    '.table tr': click : 'clickableTablerow'


  @onDomReady [
    '_initAjax'
    '_initForm'
    '_initTabs'
    '_initTooltip'
    '_initPopover'
    '_initSortable'
    '_initDisableSelection'
    '_initDatepicker'
    '_initSlider'
    '_initSelect2'
    'setContentHeight'
  ]

  _initAjax: ->
    # set some defaults for AJAX-request
    $.ajaxSetup
      cache: false
      type: 'POST'
      dataType: 'json'
      timeout: 5000

    # 403 means we aren't authenticated anymore, so reload the page
    $(document).ajaxError((event, XMLHttpRequest, ajaxOptions) ->
      window.location.reload() if XMLHttpRequest.status == 403

      if ajaxOptions?
        textStatus = Locale.err 'GeneralError'

        if XMLHttpRequest.responseText?
          json = $.parseJSON(XMLHttpRequest.responseText)
          if json.message?
            textStatus = json.message
          else
            textStatus = XMLHttpRequest.responseText

        $('#header').after(
          '<div class="alert alert-error" role="alert">' +
          '  <div class="container">' +
          '    <button type="button" class="close" data-dismiss="alert"' +
          '       title="' + Locale.lbl('Close') + '">' + Locale.lbl('Close') +
          '    </button>' +
          '    ' + textStatus +
          '  </div>' +
          '</div>'
        )
      false
    )

    # show spinners
    $(document).ajaxStart(() =>
      $.event.trigger 'ajax_start'
    )
    $(document).ajaxStop(() =>
      $.event.trigger 'ajax_stop'
    )

  _initForm: ->
    $('form').each ->
      className = $(this).data('formClass') || 'Form'
      throw className + ' is not defined' unless window[className]
      formClass = window[className]
      new formClass(this)

  _initTabs: ->
    url = document.location.toString()
    if url.match '#'
      anchor = '#' + url.split('#')[1]

      if $('.nav-tabs a[href='+anchor+']').length > 0
        $('.nav-tabs a[href='+anchor+']').tab 'show'

    $('.tab-content .tab-pane').each(() ->
      if($(this).find('.error').length > 0)
        $('.nav-tabs a[href="#' + $(this).attr('id') + '"]')
          .parent()
          .addClass('error')
    )

  _initSortable: ->
    $sortable = $ '.sortable'

    return if ($sortable.length == 0)
    return if (!$.isFunction($.fn.sortable))

    $sortable.sortable
      handle: ''
      cancel: ''

  _initDisableSelection: ->
    $sortable = $ '.sortable'

    return if ($sortable.length == 0)
    return if (!$.isFunction($.fn.disableSelection))

    $sortable.disableSelection()

  _initDatepicker: ->
    $datepicker = $ '.datepicker'

    return if ($datepicker.length == 0)
    return if (!$.isFunction($.fn.datepicker))

    $datepicker.datepicker(
      dateFormat: "dd-mm-yy"
    )

  _initSlider: ->
    $slider = $ '.slider'

    return if ($slider.length == 0)
    return if (!$.isFunction($.fn.slider))

    $slider.slider({
      min: 0
      max: 50
      values: [ 10, 40 ]
      range: true
    })

  _initSelect2: ->
    $select2 = $ '.select2'

    return if ($select2.length == 0)
    return if (!$.isFunction($.fn.select2))

    $select2.select2()

  _initTooltip: ->
    $('[data-toggle="tooltip"]').tooltip()

  _initPopover: ->
    $('[data-toggle="popover"]').popover(
      {
        html: true
      }
    )

  changeTab: (e) ->
    # if the browser supports history.pushState(), use it to update the URL
    # with the fragment identifier, without triggering a scroll/jump
    if window.history && window.history.pushState
      # an empty state object for now — either we implement a proper
      # popstate handler ourselves, or wait for jQuery UI upstream
      window.history.pushState({}, document.title, this.getAttribute('href'))
    else
      scrolled = $(window).scrollTop()
      window.location.hash = '#'+ this.getAttribute('href').split('#')[1]
      $(window).scrollTop(scrolled)

    $(this).tab 'show'

  toggleSearchBar: ->
    $('.search-box').toggleClass 'open'
    $('input[name=term]').focus()

  showLoadingBar: ->
    $('.header-title').addClass 'progress'
    $('.header-title .header-title-bar').addClass 'progress-bar progress-bar-striped active'
    return

  hideLoadingBar: ->
    $('.header-title .header-title-bar').removeClass 'progress-bar progress-bar-striped active'
    return

# Menu methods
  _setClassesBasedOnSubNavigation: () =>
    # we can't use toggle class as we don't know what the current state is
    if($('#navbar .nav ul.open').length == 0)
      $('#toggleTabletNavbar, #navbar, #content, .alert').removeClass 'subnav'
    else
      $('#toggleTabletNavbar, #navbar, #content, .alert').addClass 'subnav'

  toggleDropdown: (e) ->
    e.preventDefault()

    $this = $(e.currentTarget)
    $parent = $this.parent()

    $parent.toggleClass 'active'

    $this.next('ul').slideToggle(200, ->
      $parent.toggleClass 'open'
    )

# Animated scroll methods
  scrollTo: (e) ->
    $anchor = $(e.currentTarget)
    href = $anchor.attr 'href'
    url = href.substr(0, href.indexOf '#')
    hash = href.substr(href.indexOf '#')

    # check if we have an url, and if it is on the current page and
    # the element exists
    # disabled for nav-tabs
    if  (url == '' or url.indexOf(document.location.pathname) >= 0) and
    not $anchor.is('[data-no-scroll]') and $(hash).length > 0 and
    not $anchor.parents().is('.nav-tabs')
      $('html, body').stop().animate({
        scrollTop: $(hash).offset().top
      }, 500)
    false

  scrollToTop: (e) ->
    e.preventDefault()

    $('html, body').stop().animate({
      scrollTop: $('#main-wrapper').offset().top
    }, 500)

# Link methods
  askConfirmation: (e) ->
    e.preventDefault()
    $this = $(e.currentTarget)

    $('#confirmModalOk').attr('href', $this.attr 'href')
    $('#confirmModalMessage').html($this.data 'message')
    $('#confirmModal').modal('show')
  false

  askConfirmationAndSubmit: (e) =>
    e.preventDefault()
    $this = $(e.currentTarget)
    $modal = $('#confirmModal')
    $form = $this.parents 'form'

    $('#confirmModalMessage').html($this.data 'message')
    $modal.on('click', '#confirmModalOk', (e) =>
      e.preventDefault()
      $form.submit()
    )
      .modal 'show'
      .on('hide', (e) =>
        $modal.off('click', '#confirmModalOk')
      )
  false

  setContentHeight: =>
    $('#content').css('minHeight', $(window).height())
    timeout = null
    $(window).on('resize', (e) ->
      clearTimeout(timeout)
      timeout = setTimeout( ->
        $('#content').css('minHeight', $(window).height())
      , 200)
    )

  clickableTablerow: (event) ->
    if event.target.nodeName != 'TD'
      return

    actionUrl = $(this).closest('tr').find('.action a').attr 'href'
    window.document.location = actionUrl if actionUrl

window.Framework = Framework
