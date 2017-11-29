export class Navbar {
  constructor () {
    this.initNavbar()
  }

  initNavbar () {
    $('#main-menu-inner .dropdown-toggle').on('click', $.proxy(this.toggleDrowdown, this))
    this.setClassesBasedOnSubNavigation()
  }

  toggleDrowdown (event) {
    event.preventDefault()
    let $this = $(event.currentTarget)
    let $parent = $this.parent()

    $parent.toggleClass('active')

    $this.next('ul').slideToggle(200, () => {
      $parent.toggleClass('open')
    })
  }

  setClassesBasedOnSubNavigation () {
    // we can't use toggle class as we don't know what the current state is
    if ($('#navbar .nav ul.open').length === 0) {
      $('#toggleTabletNavbar, #navbar, #content, .alert').removeClass('subnav')

      return
    }

    $('#toggleTabletNavbar, #navbar, #content, .alert').addClass('subnav')
  }
}
