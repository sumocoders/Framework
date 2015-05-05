class Form
  form: null,

  constructor: (form) ->
    @form = form
    @_dateFields()
    @_fixPlaceholders()
    @_hijackSubmit()

# date fields
  _dateFieldOptions:
    currentText:        Locale.lbl 'core.interface.today'
    closeText:        Locale.lbl 'core.interface.close'
    nextText:         Locale.lbl 'core.interface.next'
    prevText:         Locale.lbl 'core.interface.previous'
    firstDay:         1
    hideIfNoPrevNext: true
    showAnim:         'slideDown'
    zIndex:           9999
    dateFormat:       'dd/mm/yy'
<<<<<<< Updated upstream
=======
#    dayNames: [
#      Locale.msg 'DatePickerFullSunday'
#      Locale.msg 'DatePickerFullMonday'
#      Locale.msg 'DatePickerFullTuesday'
#      Locale.msg 'DatePickerFullWednesday'
#      Locale.msg 'DatePickerFullThursday'
#      Locale.msg 'DatePickerFullFriday'
#      Locale.msg 'DatePickerFullSaturday'
#    ]
#    dayNamesMin: [
#      Locale.msg 'DatePickerMinimalSunday'
#      Locale.msg 'DatePickerMinimalMonday'
#      Locale.msg 'DatePickerMinimalTuesday'
#      Locale.msg 'DatePickerMinimalWednesday'
#      Locale.msg 'DatePickerMinimalThursday'
#      Locale.msg 'DatePickerMinimalFriday'
#      Locale.msg 'DatePickerMinimalSaturday'
#    ]
#    dayNamesShort: [
#      Locale.msg 'DatePickerShortSunday'
#      Locale.msg 'DatePickerShortMonday'
#      Locale.msg 'DatePickerShortTuesday'
#      Locale.msg 'DatePickerShortWednesday'
#      Locale.msg 'DatePickerShortThursday'
#      Locale.msg 'DatePickerShortFriday'
#      Locale.msg 'DatePickerShortSaturday'
#    ]
#    monthNames: [
#      Locale.msg 'DatePickerFullJanuary'
#      Locale.msg 'DatePickerFullFebruary'
#      Locale.msg 'DatePickerFullMarch'
#      Locale.msg 'DatePickerFullApril'
#      Locale.msg 'DatePickerFullMay'
#      Locale.msg 'DatePickerFullJune'
#      Locale.msg 'DatePickerFullJuly'
#      Locale.msg 'DatePickerFullAugust'
#      Locale.msg 'DatePickerFullSeptember'
#      Locale.msg 'DatePickerFullOctober'
#      Locale.msg 'DatePickerFullNovember'
#      Locale.msg 'DatePickerFullDecember'
#    ]
#    monthNamesShort: [
#      Locale.msg 'DatePickerShortJanuary'
#      Locale.msg 'DatePickerShortFebruary'
#      Locale.msg 'DatePickerShortMarch'
#      Locale.msg 'DatePickerShortApril'
#      Locale.msg 'DatePickerShortMay'
#      Locale.msg 'DatePickerShortJune'
#      Locale.msg 'DatePickerShortJuly'
#      Locale.msg 'DatePickerShortAugust'
#      Locale.msg 'DatePickerShortSeptember'
#      Locale.msg 'DatePickerShortOctober'
#      Locale.msg 'DatePickerShortNovember'
#      Locale.msg 'DatePickerShortDecember'
#    ]
>>>>>>> Stashed changes

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
