import {PluginNotFound} from 'Exception/PluginNotFound'

export class Sortable {
  constructor (element) {
    if (!$.isFunction($.fn.sortable)) {
      throw new PluginNotFound('Sortable')
    }

    this.element = element
    this.initSortable()
    this.initDisableSelection()
  }

  initSortable () {
    this.element.sortable(
      {
        handle: '',
        cancel: ''
      }
    )
  }

  initDisableSelection () {
    this.element.disableSelection()
  }
}
