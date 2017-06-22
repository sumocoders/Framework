export class Scrolling {

  constructor() {
    $('a.backToTop').on('click', $.proxy(this.scrollToTop, this));
    $('a[href*=#]').on('click', $.proxy(this.scrollTo, this));
  }

  scrollToTop(event) {
    event.preventDefault();
    $('html, body').stop().animate({
      scrollTop: $('#main-wrapper').offset().top
    }, 500);
  }

  scrollTo(event) {
    let $anchor = $(event.currentTarget),
        href = $anchor.attr('href'),
        url = href.substr(0, href.indexOf('#')),
        hash = href.substr(href.indexOf('#'));

    /* check if we have an url, and if it is on the current page and
     the element exists
     disabled for nav-tabs */
    if((url == '' || url.indexOf(document.location.pathname) >= 0)
        && !$anchor.is('[data-no-scroll]') && $(hash).length > 0) {
      $('html, body').stop().animate({
        scrollTop: $(hash).offset().top
      }, 500);
    }
    false;
  }
}
