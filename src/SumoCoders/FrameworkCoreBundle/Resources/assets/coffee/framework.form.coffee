class Form
  form: null,

  constructor: (form) ->
    @form = form
    @_dateFields()
    @_fixPlaceholders()
    @_hijackSubmit()

# date fields
  _dateFieldOptions:
    currentText:      Locale.get 'core.datepicker.buttons.today'
    closeText:        Locale.get 'core.datepicker.buttons.close'
    nextText:         Locale.get 'core.datepicker.buttons.next'
    prevText:         Locale.get 'core.datepicker.buttons.previous'
    firstDay:         1
    hideIfNoPrevNext: true
    showAnim:         'slideDown'
    zIndex:           9999
    dateFormat:       'dd/mm/yy'
    dayNames: [
      Locale.get 'core.datepicker.full.days.sunday'
      Locale.get 'core.datepicker.full.days.monday'
      Locale.get 'core.datepicker.full.days.tuesday'
      Locale.get 'core.datepicker.full.days.Wednesday'
      Locale.get 'core.datepicker.full.days.thursday'
      Locale.get 'core.datepicker.full.days.friday'
      Locale.get 'core.datepicker.full.days.saturday'
    ]
    dayNamesMin: [
      Locale.get 'core.datepicker.minimal.days.sunday'
      Locale.get 'core.datepicker.minimal.days.monday'
      Locale.get 'core.datepicker.minimal.days.tuesday'
      Locale.get 'core.datepicker.minimal.days.wednesday'
      Locale.get 'core.datepicker.minimal.days.thursday'
      Locale.get 'core.datepicker.minimal.days.friday'
      Locale.get 'core.datepicker.minimal.days.saturday'
    ]
    dayNamesShort: [
      Locale.get 'core.datepicker.short.days.sunday'
      Locale.get 'core.datepicker.short.days.monday'
      Locale.get 'core.datepicker.short.days.tuesday'
      Locale.get 'core.datepicker.short.days.wednesday'
      Locale.get 'core.datepicker.short.days.thursday'
      Locale.get 'core.datepicker.short.days.friday'
      Locale.get 'core.datepicker.short.days.saturday'
    ]
    monthNames: [
      Locale.get 'core.datepicker.full.months.january'
      Locale.get 'core.datepicker.full.months.february'
      Locale.get 'core.datepicker.full.months.march'
      Locale.get 'core.datepicker.full.months.april'
      Locale.get 'core.datepicker.full.months.may'
      Locale.get 'core.datepicker.full.months.june'
      Locale.get 'core.datepicker.full.months.july'
      Locale.get 'core.datepicker.full.months.august'
      Locale.get 'core.datepicker.full.months.september'
      Locale.get 'core.datepicker.full.months.october'
      Locale.get 'core.datepicker.full.months.november'
      Locale.get 'core.datepicker.full.months.december'
    ]
    monthNamesShort: [
      Locale.get 'core.datepicker.short.months.january'
      Locale.get 'core.datepicker.short.months.february'
      Locale.get 'core.datepicker.short.months.march'
      Locale.get 'core.datepicker.short.months.april'
      Locale.get 'core.datepicker.short.months.may'
      Locale.get 'core.datepicker.short.months.june'
      Locale.get 'core.datepicker.short.months.july'
      Locale.get 'core.datepicker.short.months.august'
      Locale.get 'core.datepicker.short.months.september'
      Locale.get 'core.datepicker.short.months.october'
      Locale.get 'core.datepicker.short.months.november'
      Locale.get 'core.datepicker.short.months.december'
    ]

  _parseDate: (element, key) ->
    return '' if not element.data(key)?
    data = element.data(key).split '-'
    new Date(
      parseInt(data[0], 10),
      parseInt(data[1], 10) - 1,
      parseInt(data[2], 10)
    )

  _dateFields: ->
    $.datepicker.setDefaults @_dateFieldOptions

    $('[data-date-type] input[type="text"]', @form).each((i, el) =>
      $dateWrapper = $(el).parents('[data-provider="datepicker"]')
      initialDate = @_parseDate($dateWrapper, 'date')

      $(el).datepicker(
        altField:    '#' + $dateWrapper.data('linkField')
        altFormat:   $dateWrapper.data('linkFormat').replace('yyyy', 'yy')
        defaultDate: initialDate
      )

      switch $dateWrapper.data('dateType')
        when 'start'
          startDate = @_parseDate($dateWrapper, 'minimumDate')
          $(el).datepicker('option', 'minDate', startDate)

          if initialDate != '' && initialDate < startDate
            initialDate = startDate

        when 'until'
          endDate = @_parseDate($dateWrapper, 'maximumDate')
          $(el).datepicker('option', 'maxDate', endDate)

          if initialDate != '' && startDate > initialDate
            initialDate = startDate

        when 'range'
          startDate = @_parseDate($dateWrapper, 'minimumDate')
          $(el).datepicker('option', 'minDate', startDate)

          if initialDate != '' && initialDate < startDate
            initialDate = startDate

          endDate = @_parseDate($dateWrapper, 'maximumDate')
          $(el).datepicker('option', 'maxDate', endDate)

          if initialDate != '' && startDate > initialDate
            initialDate = startDate

      # show initial date if provided
      $(el).val($.datepicker.formatDate(@_dateFieldOptions.dateFormat, initialDate)) unless initialDate == ''
    )

    $('[data-date-type] a', @form).on 'click', () ->
      el = $(this).parents('[data-provider="datepicker"]').find('input:first')
      $(el).datepicker('show') unless $(el).datepicker('widget').is(':visible')

    return

  # fixes
  _fixPlaceholders: ->
    # detect if placeholder-attributes is supported
    jQuery.support.placeholder =
      ('placeholder' in document.createElement('input'))

    if !jQuery.support.placeholder
      $input = $(@form).find('input[placeholder]')

      $input.on('focus', ->
        $this = $(this)

        if $this.val() == $this.attr 'placeholder'
          $this.val('')
          .removeClass('placeholder')
      )

      $input.on('blur', ->
        $this = $(this)

        if($this.val() == '' || $this.val() == $this.attr('placeholder'))
          $this.val($this.attr('placeholder'))
          .addClass('placeholder')
      )

      $input.blur

      $input.parents('form').submit ->
        $(this).find('input[placeholder]').each ->
          if $(this).val() == $(this).attr('placeholder')
            $(this).val('')

  _hijackSubmit: ->
    $('form').on('submit', (e) ->
      $(this).trigger('form_submitting')
    )

window.Form = Form
