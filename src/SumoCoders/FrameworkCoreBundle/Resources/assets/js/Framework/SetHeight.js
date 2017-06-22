export class SetHeight {

  constructor() {
    this.setContentHeight();
  }

  setContentHeight() {
    $('#content').css('minHeight', $(window).height());
    let timeout = null;
    return $(window).on('resize', function(e) {
      clearTimeout(timeout);
      return timeout = setTimeout( () =>
          $('#content').css('minHeight', $(window).height())
          , 200);
    });
  }
}
