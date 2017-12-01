export class Searchbar {
  constructor () {
    this.initEventListeners()
  }

  initEventListeners () {
    $('a.toggle-searchbar').on('click', $.proxy(this.toggleSearchbar, this))
  }

  toggleSearchbar () {
    $('.search-box').toggleClass('open')
    $('input[name=term]').focus()
  }
}
