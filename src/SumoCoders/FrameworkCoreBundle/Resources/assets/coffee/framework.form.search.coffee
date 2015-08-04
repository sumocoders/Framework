class SearchForm extends Form
  constructor: (form) ->
    @form = form
    @_initSearch()

  _initSearch: ->
    $searchField = $('input[name=term]', @form)
    route = $(@form).attr('action') + '.json'

    $searchField.autocomplete(
      position:
        using: (position, elements) ->
          newPosition =
            left: position.left
            top: position.top
            bottom: 'auto'
            margin: 0
          elements.element.element.css(newPosition)
      source: (request, response) ->
        $.ajax
          type: 'GET'
          url: route
          data: { term: request.term }
          success: (data) ->
            items = []
            for value in data.data.results
              items.push(
                {
                  value: value
                }
              )
            response(items)
      select: (e, ui) ->
        e.preventDefault()
        if ui.item.value.route?
          document.location = ui.item.value.route
        else if ui.item.value.value?
          return ui.item.value.value
        else
          return ui.item.label
      focus: (e, ui) ->
        e.preventDefault()
        $(e.target).val(ui.item.value.title)
    )

    $searchField.each (idx,element) =>
      $(element).data('ui-autocomplete')._renderItem = @renderItem

    return

  renderItem: (ul, item) ->
    $('<li>')
      .append(
        $('<a>').append(
          item.value.title +
          '<small class="muted"> (' + item.value.bundle + ')</small>'
        )
      )
      .appendTo(ul)


window.SearchForm = SearchForm
