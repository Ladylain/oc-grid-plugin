# Changelog

Grid Cards supports October CMS 4.4 and later.

## 1.0.1

### Fixed

- The images of the `media` section were resized to 68 × 68 pixels by the list widget and looked blurry. They are now resized to 960 pixels wide and cropped to the proportions of the card (`cardMediaRatio`). The `width` and `height` options of the column still take precedence.

## 1.0.0

First version of Grid Cards.

- `GridController` behavior, a drop-in replacement for the `ListController` that displays the records as cards.
- Card sections (`media`, `badge`, `header`, `body`, `footer`) and field widths on 12 columns (`cardSpan`, or the `col-*` classes of `headCssClass`).
- Grid options: `cardMinWidth`, `cardColumns`, `cardLayout` (`default` or `overlay`), `cardMediaRatio`, `cardLabels`, `showSortSelector`.
- Sort selector and a labelled select all checkbox above the cards.
- Reordering of sortable records by drag and drop. Nested records keep the tree table of October CMS.
- All the list column types, with the file names displayed next to the file icons.
- Light and dark modes, responsive grid, English and French translations.
