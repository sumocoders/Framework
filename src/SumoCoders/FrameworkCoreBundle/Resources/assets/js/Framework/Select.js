import {PluginNotFound} from 'Exception/PluginNotFound';

export class Select
{
  constructor(element)
  {
    if (!$.isFunction($.fn.select2)) {
      throw new PluginNotFound('Select2');
    }

    this.element = element;
    this.initSelect();
  }

  initSelect()
  {
    this.element.select2();
  }
}
