export class Table {

  constructor() {
    $('.table tr').on('click', $.proxy(this.clickableTablerow, this));
  }

  clickableTablerow(event) {
    if(event.target.nodeName != 'TD') {
      return;
    }

    let actionUrl = $(this).closest('tr').find('.action a').attr('href');
    if(actionUrl) { window.document.location = actionUrl }
  }
}
