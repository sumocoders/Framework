class Flash

  add: (message, type) ->
    alertId = Math.floor(Date.now() / 1000);

    $('body').prepend(
      '<div class="alert alert-' + type + ' alert-dismissible notification" role="alert" data-alert-id="' + alertId + '">' +
        '  <div class="container">' +
        '    <button type="button" class="close" data-dismiss="alert"' +
        '       title="' + Locale.lbl('Close') + '">' + Locale.lbl('Close') +
        '    </button>' +
        '    ' + message +
        '  </div>' +
        '</div>'
    );

    return alertId

  remove: (alertId) ->
    $('[data-alert-id=' + alertId + ']').remove()

window.Flash = new Flash()
