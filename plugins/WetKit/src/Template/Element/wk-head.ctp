<?php use Cake\Core\Configure;

if ($this->elementExists('head')) {
    echo $this->element('head');
} else {
?>



<!DOCTYPE html><!--[if lt IE 9]><html class="no-js lt-ie9" lang="<?= Configure::read("wetkit.lang") ?>" dir="ltr"><![endif]--><!--[if gt IE 8]><!-->
<html class="no-js" lang="<?= Configure::read("wetkit.lang") ?>" dir="ltr">
<!--<![endif]-->
<head>
    <?php //echo $this->Html->charset(); ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo Configure::read('wetkit.title'); ?></title>

    <link href="<?= Configure::read('wetkit.wet.path') ?>/assets/favicon.ico" rel="shortcut icon"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta name="description"
          content="<?= Configure::read("wetkit.description") ?>"/>
    <meta name="dcterms.title" content="<?= Configure::read("wetkit.title") ?>"/>
    <meta name="dcterms.creator" content="<?= Configure::read("wetkit.creator") ?>"/>
    <meta name="dcterms.issued" title="W3CDTF"
          content="Date published (<?= Configure::read("wetkit.release-date") ?>) / Date de publication (<?= Configure::read("wetkit.release-date") ?>)"/>
    <meta name="dcterms.modified" title="W3CDTF"
          content="Date modified (<?= Configure::read("wetkit.modified") ?>) / Date de modification (<?= Configure::read("wetkit.modified") ?>)"/>
    <meta name="dcterms.subject" title="scheme" content="<?= Configure::read("wetkit.meta-subject") ?>"/>
    <meta name="dcterms.language" title="ISO639-2" content="<?= Configure::read("wetkit.ISO639-2") ?>"/>
    <?= $this->fetch('wetkit-head-meta') ?>

    <!--[if gte IE 9 | !IE ]><!-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/<?= Configure::read('wetkit.jquery-version') ?>/jquery.min.js"></script>
    <link rel="stylesheet" href="<?= Configure::read('wetkit.wet.path') ?>/css/wet-boew.min.css"/>

    <!--<![endif]-->
    <link rel="stylesheet" href="<?= Configure::read('wetkit.wet.path') ?>/css/theme.min.css"/>
    <!--[if lt IE 9]>
    <link rel="stylesheet" href="<?= Configure::read('wetkit.wet.path') ?>/css/ie8-wet-boew.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/<?= Configure::read('wetkit.jquery-ie-version') ?>/jquery.min.js"></script>
    <script src="<?= Configure::read('wetkit.wet.path') ?>/js/ie8-wet-boew.min.js"></script><![endif]-->
    <noscript>
        <link rel="stylesheet" href="<?= Configure::read('wetkit.wet.path') ?>/css/noscript.min.css"/>
    </noscript>

    <!-- TinyMCE -->
    <script src="/share/tinymce/<?= Configure::read('wetkit.tinymce-version') ?>/js/tinymce/tinymce.min.js"></script>

    <script type="text/javascript">
        tinymce.init({
            selector: "textarea.mceEditor",
            menubar: false,
            plugins: ["advlist autolink autosave link lists paste table code importcss"],
            importcss_file_filter: "<?= Configure::read('wetkit.wet.basepath') ?>/<?= Configure::read('wetkit.wet.version') ?>/theme-gc-intranet/css/wet-boew.min.css",
            importcss_append: true,
            content_css: "<?= Configure::read('wetkit.wet.basepath') ?>/<?= Configure::read('wetkit.wet.version') ?>/theme-gc-intranet/css/wet-boew.min.css",
            toolbar_items_size: 'small',
            toolbar1: "formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent blockquote | undo redo | link unlink | table | removeformat | subscript superscript | code",
        });
    </script>

    <?= $this->Html->css("wetkit"); ?>
    <?= $this->fetch('css'); ?>
    <?= $this->fetch('script'); ?>
    <?= $this->fetch('wetkit-head'); ?>
</head>

<?php
}