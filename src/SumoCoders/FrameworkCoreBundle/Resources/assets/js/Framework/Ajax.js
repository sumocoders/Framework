import {Locale} from 'Framework/Locale';
import {Flash} from 'Framework/Flash';
const locale = new Locale();

export class Ajax {

  constructor() {
    this.initAjax();
  }

  initAjax() {
    /*set some defaults for AJAX-request*/
    $.ajaxSetup({
      cache: false,
      type: 'POST',
      dataType: 'json',
      timeout: 5000
    });

    /* 403 means we aren't authenticated anymore, so reload the page*/
    $(document).ajaxError((event, XMLHttpRequest, ajaxOptions) => {
      if(XMLHttpRequest.status == 403) {window.location.reload()}

    if(ajaxOptions != null) {
      let textStatus = locale.err('GeneralError');

      if(XMLHttpRequest.responseText != null) {
        let json = $.parseJSON(XMLHttpRequest.responseText);
        if(json.message != null) {
          textStatus = json.message;
        }
        else {
          textStatus = XMLHttpRequest.responseText;
        }
      }

      new Flash().add(textStatus, 'danger');
    }

    return false;
  });

    /*show spinners*/
    $(document).ajaxStart(() => {
      $.event.trigger('ajax_start');
  });

    $(document).ajaxStop(() => {
      $.event.trigger('ajax_stop');
  });
  }
}
