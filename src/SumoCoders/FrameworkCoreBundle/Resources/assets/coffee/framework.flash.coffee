class Flash

  add: (message, type, time) ->
    alertId = Date.now();

    $('.main-header').append(
        '<div class="alert alert-fixed alert-' + type + ' alert-dismissible notification" role="status" data-alert-id="' + alertId + '">' +
            '  <div class="container">' +
            '    <a class="close" data-dismiss="alert" title="' + Locale.lbl('Close') + '">' +
            '       <i class="fa fa-close"></i>' +
            '       <span class="hide">' + Locale.lbl('Close') + '</span>' +
            '    </a>' +
            '    ' + message +
            '  </div>' +
        '</div>'
    );

    if time?
      callback = => @remove(alertId)
      setTimeout callback, time

    return alertId

  remove: (alertId) ->
    $('[data-alert-id=' + alertId + ']').remove()

window.Flash = new Flash()
