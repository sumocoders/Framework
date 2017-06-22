import {Data} from 'Framework/Data';

const dataData = new Data();

export class Locale {

  constructor() {
    this.isInitialized = false;
    this.data = null;
  }

  initialize() {
    $.ajax('/' + dataData.get('request.locale') + '/locale.json', {
      type: 'GET',
      dataType: 'json',
      async: false,
      success: (data) => {
      this.data = data;
    this.isInitialized = true;
  },
    error: (jqXHR, textStatus, errorThrown) => {
      throw Error('Regenerate your locale-files.');
    }
  })
  }

  exists(key) {
    return (this.get(key) != null );
  }

  get(key) {
    if(!this.isInitialized) this.initialize();
    if(this.data[key] != null) return this.data[key];
  }

  act(key) {
    return this.get(key);
  }

  err(key) {
    return this.get(key);
  }

  lbl(key) {
    return this.get(key);
  }

  msg(key) {
    return this.get(key);
  }
}
