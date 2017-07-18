import {PluginNotFound} from 'Exception/PluginNotFound';

export class Slider
{
  constructor(element, min = 0, max = 50, values = [10, 40], range = true)
  {
    if (!$.isFunction($.fn.slider)) {
      throw new PluginNotFound('Slider');
    }

    this.element = element;
    this.min = min;
    this.max = max;
    this.values = values;
    this.range = range;
    this.initSlider();
  }

  initSlider()
  {
    this.element.slider(
      {
        min: this.min,
        max: this.max,
        values: this.values,
        range: this.range,
      }
    )
  }
}
