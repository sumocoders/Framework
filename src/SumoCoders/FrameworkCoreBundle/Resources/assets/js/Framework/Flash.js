import {Locale} from 'Framework/Locale'
const locale = new Locale()

export class Flash {
  add (message, type, time) {
    let alertId = Date.now()

    $('.main-header').append(
        `<div class="alert alert-${type} alert-dismissible notification" role="status" data-alert-id="${alertId}">
        <a class="close" data-dismiss="alert" title="${locale.lbl('core.interface.close')}">
          <i class="fa fa-close"></i>
          <span class="hide">${locale.lbl('core.interface.close')}</span>
        </a> ${message}
      </div>`
    )

    if (time !== undefined && time !== null) {
      let callback = () => this.remove(alertId)
      setTimeout(callback, time)
    }

    return alertId
  }

  remove (alertId) {
    $('[data-alert-id=' + alertId + ']').remove()
  }
}
