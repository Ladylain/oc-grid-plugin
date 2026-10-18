Grid Cards displays the backend lists of October CMS as a grid of cards instead of a table. It suits the records that are recognised at a glance: products with a photo, team members, articles, events, documents, packs or offers.

![Products as cards](https://raw.githubusercontent.com/Ladylain/oc-grid-plugin/master/docs/screenshots/01-products-cards.jpg)

## A drop-in replacement for the ListController

Replace `ListController` with `GridController` in your controller and rename `$listConfig` to `$gridConfig`. Your configuration file, your column definitions and your controller overrides keep working:

- toolbar, search and filters
- sorting, with a sort selector in place of the column headers
- pagination and records per page
- checkboxes, select all and bulk actions such as Delete
- list setup (visible columns and records per page)
- `listExtendQuery`, `listInjectRowClass`, `listOverrideColumnValue` and the other overrides
- reordering of sortable records by drag and drop

Nested records (`showTree`) keep the usual tree table of October CMS.

## Cards designed from your columns

No template to write: each column is placed in the card with a single option.

- **media**: a full width image at the top of the card
- **badge**: a pill over the image, for example a status
- **header**: the title of the card, followed by subtitles
- **body**: fields with their label above the value
- **footer**: small fields on the bottom line

Several fields share a line with the `cardSpan` option (`half`, `third`, `quarter`… or 1 to 12 columns). The Bootstrap `col-*` classes already used in `headCssClass` are supported.

## Every column type

Text, number, switch, summary, image, file, selectable, color picker, linkage, partial, date, date and time, time, time since and time tense columns are displayed with their usual rendering, adapted to the card.

## Two layouts

- **default**: the image above the title
- **overlay**: the title over the image, with a gradient

The width of the cards, the number of cards per line and the proportions of the image are set in the configuration file.

## Built for October CMS 4

- light and dark modes, using the colors of the backend theme
- responsive grid, from one card per line on mobile
- the list scripts of October CMS are used unchanged
- English and French translations
