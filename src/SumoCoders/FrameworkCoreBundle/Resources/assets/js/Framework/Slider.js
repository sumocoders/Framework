import {PluginNotFound} from 'Exception/PluginNotFound';

export class Slider
{
  constructor(element)
  {
    if (!$.isFunction($.fn.slider)) {
      throw new PluginNotFound('Slider');
    }

    this.element = element;
    this.initSlider();
  }

  initSlider()
  {
    this.element.slider(
      {
        min: 0,
        max: 50,
        values: [10, 40],
        range: true,
      }
    )
  }
}
