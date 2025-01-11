$.hik.jtable.prototype._fillDropDownListWithOptions = function ($select,
options, value) {
var fieldName = $select.attr("name");
var field = this.options.fields[fieldName];
if (field.type == "multi") {
$select.empty().attr("multiple", "multiple");
for (var i = 0; i < options.length; i++) {
var selected = false;
for (var j = 0; j < value.length; j ++) {
if (value[j] == options[i].Value) {
selected = true;
break;
}
}
$select.append(' (selected ? ' selected="selected"' : '') + '>' + options[i].DisplayText

    ''); } } else { $select.empty(); for (var i = 0; i < options.length; i++) { $select.append('' + options[i].DisplayText + ''); } } };
