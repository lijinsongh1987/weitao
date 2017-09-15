/*
 * Translated default messages for bootstrap-select.
 * Locale: EN (English)
 * Region: US (United States)
 */
(function ($) {
  $.fn.selectpicker.defaults = {
    noneSelectedText: '---请选择---',
    noneResultsText: 'No results match {0}',
    countSelectedText: function (numSelected, numTotal) {
      return (numSelected == 1) ? "{0} item selected" : "{0} items selected";
    },
    maxOptionsText: function (numAll, numGroup) {
      return [
        (numAll == 1) ? 'Limit reached ({n} item max)' : 'Limit reached ({n} items max)',
        (numGroup == 1) ? 'Group limit reached ({n} item max)' : 'Group limit reached ({n} items max)'
      ];
    },
    selectAllText: '全选 ',
    deselectAllText: '清空',
    multipleSeparator: ', '
  };
})(jQuery);
