export class Tabs {

  constructor() {
    $('.nav-tabs a').on('click', $.proxy(this.changeTab, this));
  }

  changeTab(event) {
    let $current = $(event.currentTarget);
    /* if the browser supports history.pushState(), use it to update the URL
     with the fragment identifier, without triggering a scroll/jump */
    if(window.history && window.history.pushState) {
      /* an empty state object for now — either we implement a proper
       popstate handler ourselves, or wait for jQuery UI upstream */
      window.history.pushState({}, document.title, $current.href);
    }
    else {
      let scrolled = $(window).scrollTop();
      window.location.hash = '#' + $current.href.split('#')[1];
      $(window).scrollTop(scrolled);
    }

    $(this).tab('show');
  }
}
