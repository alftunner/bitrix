import Dialog from './dialog/dialog';
import Item from './item/item';
import Tab from './dialog/tabs/tab';
import TagSelector from './tag-selector/tag-selector';
import BaseFooter from './dialog/footer/base-footer';
import DefaultFooter from './dialog/footer/default-footer';
import BaseStub from './dialog/tabs/base-stub';
import DefaultStub from './dialog/tabs/default-stub';

import type { DialogOptions } from './dialog/dialog-options';
import type { TabOptions } from './dialog/tabs/tab-options';
import type { ItemOptions } from './item/item-options';
import type { TagSelectorOptions } from './tag-selector/tag-selector-options';

import './css/dialog.css';
import './css/tab.css';
import './css/item.css';
import './css/tag-selector.css';

/**
 * @namespace BX.UI.EntitySelector
 */
export {
	Dialog,
	Item,
	Tab,
	TagSelector,
	BaseFooter,
	DefaultFooter,
	BaseStub,
	DefaultStub
}

export type {
	DialogOptions,
	TabOptions,
	ItemOptions,
	TagSelectorOptions
}