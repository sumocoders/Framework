export class LoadingBar {
  constructor () {
    this.initEventListeners()
  }

  initEventListeners () {
    $(document).on(' form_submitting', $.proxy(this.showLoadingBar, this))
    $(document).on('ajax_start', $.proxy(this.showLoadingBar, this))
    $(document).on('ajax_stop', $.proxy(this.hideLoadingBar, this))
  }

  showLoadingBar () {
    $('.header-title').addClass('progress')
    $('.header-title .header-title-bar').addClass('progress-bar progress-bar-striped active')
  }

  hideLoadingBar () {
    $('.header-title .header-title-bar').removeClass('progress-bar progress-bar-striped active')
  }
}
