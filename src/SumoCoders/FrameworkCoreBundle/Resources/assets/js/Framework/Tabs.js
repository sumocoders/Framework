export class Tabs {
  constructor () {
    this.initEventListeners()
    this.loadTab()
  }

  initEventListeners () {
    $('.nav-tabs a').on('click', $.proxy(this.changeTab, this))
  }

  changeTab (event) {
    let $current = $(event.currentTarget)
    /* if the browser supports history.pushState(), use it to update the URL
     with the fragment identifier, without triggering a scroll/jump */
    if (window.history && window.history.pushState) {
      /* an empty state object for now — either we implement a proper
       popstate handler ourselves, or wait for jQuery UI upstream */
      window.history.pushState({}, document.title, $current.attr('href'))
    } else {
      let scrolled = $(window).scrollTop()
      window.location.hash = '#' + $current.attr('href').split('#')[1]
      $(window).scrollTop(scrolled)
    }
    $current.tab('show')
  }

  loadTab () {
    let anchor = document.location.hash
    if (anchor !== '') {
      if ($('.nav-tabs a[href=' + anchor + ']').length > 0) {
        $('.nav-tabs a[href=' + anchor + ']').tab('show')
      }
    }
  }
}
