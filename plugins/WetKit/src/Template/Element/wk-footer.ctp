<?php
if ($this->elementExists('footer')) {
    echo $this->element('footer');
} else {
    echo $this->fetch("wetkit-footer");
    echo $this->element('scripts');
    echo $this->element('wetkit-debug');
}
