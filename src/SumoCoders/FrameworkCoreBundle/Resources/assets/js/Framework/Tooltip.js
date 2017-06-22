export class Tooltip {

  constructor(element) {
    this.element = element;
    this.initTooltip();
  }

  initTooltip() {
    this.element.tooltip();
  }
}
