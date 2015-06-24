<?php

$created = "";
$sep = "";
if ($data['created'] && $data['created_by']) {
    $created = __d(
        'wet_kit',
        "Created on {0} by {1}", $data['created'],
        $data['created_by']
    );
    $sep = "<br>";
}  elseif ($data['created']) {
    $created = __d(
        'wet_kit',
        "Created on {0} ",
        $data['created']
    );
    $sep = "<br>";
}

$modified = "";
if ($data['modified'] && $data['modified_by']) {
    $modified = __d('wet_kit',
        "Modified on {0} by {1}",
        $data['modified'],
        $data['modified_by']
    );
}  elseif ($data['modified']) {
    $modified = __d(
        'wet_kit',
        "Modified on {0}",
        $data['modified']
    );
}

echo '<div class="' . $data['class'] . '" style="' . $data['style'] . '" >
            ' . $created . $sep . $modified .
"</div>";
