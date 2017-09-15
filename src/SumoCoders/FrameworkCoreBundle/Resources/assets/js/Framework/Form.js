import {Locale} from 'Framework/Locale'

const locale = new Locale()

let capitalizeWord = function (str) {
  if (typeof str === 'undefined' || !str) {
    return '';
  }

  return str.charAt(0).toUpperCase() + str.slice(1);
}

$.fn.datepicker.dates[jsData.request.locale] = {
  days: [
    capitalizeWord(locale.get('datepicker.full.days.sunday')),
    capitalizeWord(locale.get('datepicker.full.days.monday')),
    capitalizeWord(locale.get('datepicker.full.days.tuesday')),
    capitalizeWord(locale.get('datepicker.full.days.wednesday')),
    capitalizeWord(locale.get('datepicker.full.days.thursday')),
    capitalizeWord(locale.get('datepicker.full.days.friday')),
    capitalizeWord(locale.get('datepicker.full.days.saturday'))
  ],
  daysMin: [
    capitalizeWord(locale.get('datepicker.minimal.days.sunday')),
    capitalizeWord(locale.get('datepicker.minimal.days.monday')),
    capitalizeWord(locale.get('datepicker.minimal.days.tuesday')),
    capitalizeWord(locale.get('datepicker.minimal.days.wednesday')),
    capitalizeWord(locale.get('datepicker.minimal.days.thursday')),
    capitalizeWord(locale.get('datepicker.minimal.days.friday')),
    capitalizeWord(locale.get('datepicker.minimal.days.saturday'))
  ],
  daysShort: [
    capitalizeWord(locale.get('datepicker.short.days.sunday')),
    capitalizeWord(locale.get('datepicker.short.days.monday')),
    capitalizeWord(locale.get('datepicker.short.days.tuesday')),
    capitalizeWord(locale.get('datepicker.short.days.wednesday')),
    capitalizeWord(locale.get('datepicker.short.days.thursday')),
    capitalizeWord(locale.get('datepicker.short.days.friday')),
    capitalizeWord(locale.get('datepicker.short.days.saturday'))
  ],
  months: [
    capitalizeWord(locale.get('datepicker.full.months.january')),
    capitalizeWord(locale.get('datepicker.full.months.february')),
    capitalizeWord(locale.get('datepicker.full.months.march')),
    capitalizeWord(locale.get('datepicker.full.months.april')),
    capitalizeWord(locale.get('datepicker.full.months.may')),
    capitalizeWord(locale.get('datepicker.full.months.june')),
    capitalizeWord(locale.get('datepicker.full.months.july')),
    capitalizeWord(locale.get('datepicker.full.months.august')),
    capitalizeWord(locale.get('datepicker.full.months.september')),
    capitalizeWord(locale.get('datepicker.full.months.october')),
    capitalizeWord(locale.get('datepicker.full.months.november')),
    capitalizeWord(locale.get('datepicker.full.months.december'))
  ],
  monthsShort: [
    capitalizeWord(locale.get('datepicker.short.months.january')),
    capitalizeWord(locale.get('datepicker.short.months.february')),
    capitalizeWord(locale.get('datepicker.short.months.march')),
    capitalizeWord(locale.get('datepicker.short.months.april')),
    capitalizeWord(locale.get('datepicker.short.months.may')),
    capitalizeWord(locale.get('datepicker.short.months.june')),
    capitalizeWord(locale.get('datepicker.short.months.july')),
    capitalizeWord(locale.get('datepicker.short.months.august')),
    capitalizeWord(locale.get('datepicker.short.months.september')),
    capitalizeWord(locale.get('datepicker.short.months.october')),
    capitalizeWord(locale.get('datepicker.short.months.november')),
    capitalizeWord(locale.get('datepicker.short.months.december'))
  ],
  today: capitalizeWord(locale.get('datepicker.buttons.today')),
  clear: capitalizeWord(locale.get('datepicker.buttons.clear')),
  titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
  weekStart: 1
};

export class Form {
  constructor (form) {
    this.form = form
  }

  initForm () {
    this.dateFields()
    $('form').on('submit', $.proxy(this.hijackSubmit, this))
  }

  parseDate (element, key) {
    if (typeof element.data(key) === 'undefined' || element.data(key) === null) {
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
    $('[data-role=datepicker]').each((i, el) => {
      let datepicker = $(el);

      datepicker.datepicker({
        titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
        currentText: locale.get('datepicker.buttons.today'),
        closeText: locale.get('datepicker.buttons.close'),
        nextText: locale.get('datepicker.buttons.next'),
        prevText: locale.get('datepicker.buttons.previous'),
        firstDay: 1,
        hideIfNoPrevNext: true,
        showAnim: 'slideDown',
        zIndex: 9999,
        language: jsData.request.locale,
        format: datepicker.data('format')
      });
    });

    $('.js-input-focus').on('click', function() {
      $($(this).data('toggle')).focus();
    });
  }

  hijackSubmit () {
    $(this).trigger('form_submitting')
  }
}
