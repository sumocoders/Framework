export class Popover {

  constructor(element) {
    this.element = element;
    this.initPopover();
  }

  initPopover() {
    this.element.popover(
        {
          html: true
        }
    )
  }
}
