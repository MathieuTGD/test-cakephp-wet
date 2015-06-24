<?php

if (isset($model)) {
    $remove = "";
    $controller = Inflector::tableize($model);

    if (isset($placeholder) && $placeholder != null) {
        $arg_placeholder = ' placeholder="' . $placeholder . '" ';
    } else {
        $arg_placeholder = "";
    }
    if (isset($filter) && $filter != null) {
        $remove = "&nbsp;" . $this->Html->link(__d('wet_kit', "Remove Filter"),
                array("controller" => $controller, "action" => "index"), array("class" => "btn btn-warning"));
    } else {
        $filter = "";
    }

    if (!isset($button_text) || $button_text == '') {
        $button_text = __d('wet_kit', 'Filter List');
    }

    echo '
        <div class="well">
            <form action="' . $this->Html->url("/" . $controller) . '" class="form-inline" role="form" method="post" accept-charset="utf-8" style="margin: 0; padding: 0;">
            <div style="display:none;"><input type="hidden" name="_method" value="POST"></div>
            <div class="form-group"><div class="input-group">
                    <label class="sr-only" for="FilterField">' . __d('wet_kit', 'Filter') . '</label>
                    <input name="data[' . $model . '][filter]" class="form-control" maxlength="150" style="width: 100%" type="text" value="' . $filter . '" ' . $arg_placeholder . ' id="FilterField">
                </div></div>
            <div class="form-group"><div class="input-group">
                    <button class="btn btn-primary" type="submit">' . $button_text . '</button>
                    ' . $remove . '
                </div></div>
            </form>
        </div>
    ';
}
