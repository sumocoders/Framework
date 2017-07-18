export class Scrolling
{
  constructor()
  {
    $('a[href*=#]').on('click', $.proxy(this.scrollTo, this));
  }

  scrollTo(event)
  {
    let $anchor = $(event.currentTarget);
    let href = $anchor.attr('href');
    let url = href.substr(0, href.indexOf('#'));
    let hash = href.substr(href.indexOf('#'));

    /* check if we have an url, and if it is on the current page and the element exists disabled for nav-tabs */
    if ((url === '' || url.indexOf(document.location.pathname) >= 0)
        && !$anchor.is('[data-no-scroll]')
        && $(hash).length > 0
    ) {
      let $htmlBody = $('html, body');
      $htmlBody.stop();
      $htmlBody.animate(
        {
          scrollTop: $(hash).offset().top
        },
        500
      );
    }
  }
}
