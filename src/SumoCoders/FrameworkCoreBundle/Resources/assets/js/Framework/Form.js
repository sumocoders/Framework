import {Locale} from 'Framework/Locale'

const locale = new Locale()
const dateFieldOptions = {
  currentText: locale.get('datepicker.buttons.today'),
  closeText: locale.get('datepicker.buttons.close'),
  nextText: locale.get('datepicker.buttons.next'),
  prevText: locale.get('datepicker.buttons.previous'),
  firstDay: 1,
  hideIfNoPrevNext: true,
  showAnim: 'slideDown',
  zIndex: 9999,
  dateFormat: 'dd/mm/yy',
  dayNames: [
    locale.get('datepicker.full.days.sunday'),
    locale.get('datepicker.full.days.monday'),
    locale.get('datepicker.full.days.tuesday'),
    locale.get('datepicker.full.days.Wednesday'),
    locale.get('datepicker.full.days.thursday'),
    locale.get('datepicker.full.days.friday'),
    locale.get('datepicker.full.days.saturday')
  ],
  dayNamesMin: [
    locale.get('datepicker.minimal.days.sunday'),
    locale.get('datepicker.minimal.days.monday'),
    locale.get('datepicker.minimal.days.tuesday'),
    locale.get('datepicker.minimal.days.wednesday'),
    locale.get('datepicker.minimal.days.thursday'),
    locale.get('datepicker.minimal.days.friday'),
    locale.get('datepicker.minimal.days.saturday')
  ],
  dayNamesShort: [
    locale.get('datepicker.short.days.sunday'),
    locale.get('datepicker.short.days.monday'),
    locale.get('datepicker.short.days.tuesday'),
    locale.get('datepicker.short.days.wednesday'),
    locale.get('datepicker.short.days.thursday'),
    locale.get('datepicker.short.days.friday'),
    locale.get('datepicker.short.days.saturday')
  ],
  monthNames: [
    locale.get('datepicker.full.months.january'),
    locale.get('datepicker.full.months.february'),
    locale.get('datepicker.full.months.march'),
    locale.get('datepicker.full.months.april'),
    locale.get('datepicker.full.months.may'),
    locale.get('datepicker.full.months.june'),
    locale.get('datepicker.full.months.july'),
    locale.get('datepicker.full.months.august'),
    locale.get('datepicker.full.months.september'),
    locale.get('datepicker.full.months.october'),
    locale.get('datepicker.full.months.november'),
    locale.get('datepicker.full.months.december')
  ],
  monthNamesShort: [
    locale.get('datepicker.short.months.january'),
    locale.get('datepicker.short.months.february'),
    locale.get('datepicker.short.months.march'),
    locale.get('datepicker.short.months.april'),
    locale.get('datepicker.short.months.may'),
    locale.get('datepicker.short.months.june'),
    locale.get('datepicker.short.months.july'),
    locale.get('datepicker.short.months.august'),
    locale.get('datepicker.short.months.september'),
    locale.get('datepicker.short.months.october'),
    locale.get('datepicker.short.months.november'),
    locale.get('datepicker.short.months.december')
  ]
}

export class Form {
  constructor (form) {
    this.form = form
  }

  initForm () {
    this.dateFields()
    $('form').on('submit', $.proxy(this.hijackSubmit, this))
  }

  parseDate (element, key) {
    if (element.data(key) !== undefined || element.data(key) !== null) {
      return ''
    }

    let data = element.data(key).split('-')
    return new Date(
        parseInt(data[0], 10),
        parseInt(data[1], 10) - 1,
        parseInt(data[2], 10)
    )
  }

  dateFields () {
    if (!$.isFunction($.fn.datepicker)) {
      return
    }

    $.datepicker.setDefaults(dateFieldOptions)

    $('[data-date-type] input[type="text"]', this.form).each((i, el) => {
      let $dateWrapper = $(el).parents('[data-provider="datepicker"]')
      let initialDate = this.parseDate($dateWrapper, 'date')

      $(el).datepicker({
        altField: '#' + $dateWrapper.data('linkField'),
        altFormat: $dateWrapper.data('linkFormat').replace('yyyy', 'yy'),
        defaultDate: initialDate
      })

      switch ($dateWrapper.data('dateType')) {
        case 'start':
          let startDate = this.parseDate($dateWrapper, 'minimumDate')
          $(el).datepicker('option', 'minDate', startDate)

          if ((initialDate !== '') && (initialDate < startDate)) {
            initialDate = startDate
          }
          break

        case 'until':
          let endDate = this.parseDate($dateWrapper, 'maximumDate')
          $(el).datepicker('option', 'maxDate', endDate)

          if ((initialDate !== '') && (startDate > initialDate)) {
            initialDate = startDate
          }
          break

        case 'range':
          startDate = this.parseDate($dateWrapper, 'minimumDate')
          $(el).datepicker('option', 'minDate', startDate)

          if ((initialDate !== '') && (initialDate < startDate)) {
            initialDate = startDate
          }

          endDate = this.parseDate($dateWrapper, 'maximumDate')
          $(el).datepicker('option', 'maxDate', endDate)

          if ((initialDate !== '') && (startDate > initialDate)) {
            initialDate = startDate
          }
          break
      }

      // show initial date if provided
      if (initialDate !== '') {
        return $(el).val($.datepicker.formatDate(this._dateFieldOptions.dateFormat, initialDate))
      }
    })

    $('[data-date-type] a', this.form).on('click', () => {
      let el = $(this).parents('[data-provider="datepicker"]').find('input:first')
      if (!$(el).datepicker('widget').is(':visible')) {
        return $(el).datepicker('show')
      }
    })
  }

  hijackSubmit () {
    $(this).trigger('form_submitting')
  }
}
