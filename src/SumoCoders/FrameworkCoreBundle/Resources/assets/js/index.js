import {Ajax} from './Framework/Ajax';
import {Form} from './Framework/Form';
import {Link} from './Framework/Link';
import {LoadingBar} from './Framework/LoadingBar';
import {Navbar} from './Framework/Navbar';
import {Popover} from './Framework/Popover';
import {Scrolling} from './Framework/Scrolling';
import {SetHeight} from './Framework/SetHeight';
import {Searchbar} from './Framework/Searchbar';
import {Select} from './Framework/Select';
import {Slider} from './Framework/Slider';
import {Sortable} from './Framework/Sortable';
import {Table} from './Framework/Table';
import {Tabs} from './Framework/Tabs';
import {Tooltip} from './Framework/Tooltip';

new Ajax();
new Form();
new Link();
new LoadingBar();
new Navbar();
new Scrolling();
new SetHeight();
new Searchbar();
new Table();
new Tabs();

export class Index {

  constructor() {
    this.initializeSliders();
    this.initializeSortables();
    this.initializePopovers();
    this.initializeTooltips();
    this.initializeSelects();
  }

  initializeSliders() {
    $('.slider').each((index, element) => {
      element.slider = new Slider($(element));
  });
  }

  initializeSortables() {
    $('.sortable').each((index, element) => {
      element.sortable = new Sortable($(element));
  });
  }

  initializePopovers() {
    $('[data-toggle="popover"]').each((index, element) => {
      element.popover = new Popover($(element));
  });
  }

  initializeTooltips() {
    $('[data-toggle="tooltip"]').each((index, element) => {
      element.tooltip = new Tooltip($(element));
  });
  }

  initializeSelects() {
    $('.select2').each((index, element) => {
      element.select2 = new Select($(element));
  });
  }
}

window.index = new Index();
