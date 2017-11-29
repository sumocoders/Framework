import {Locale} from 'Framework/Locale'

const locale = new Locale()

export class Form {
  constructor (form) {
    this.initForm()
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
      let format = datepicker.data('format');

      let startDate = false;
      if (typeof datepicker.data('date-start-date') !== 'undefined' && datepicker.data('date-start-date')) {
        startDate = moment(datepicker.data('date-start-date'), format);
      }

      let endDate = false;
      if (typeof datepicker.data('date-end-date') !== 'undefined' && datepicker.data('date-end-date')) {
        endDate = moment(datepicker.data('date-end-date'), format);
      }

      datepicker.datetimepicker({
        dayViewHeaderFormat: "MMMM YYYY", /* Leverages same syntax as 'format' */
        locale: jsData.request.locale,
        format: format,
        minDate: startDate,
        maxDate: endDate,
        keepOpen: true,
        icons: {
          time: "fa fa-clock-o",
          date: "fa fa-calendar",
          up: "fa fa-arrow-up",
          down: "fa fa-arrow-down",
          previous: "fa fa-arrow-left",
          next: "fa fa-arrow-right"
        },
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
