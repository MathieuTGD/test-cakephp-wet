<?php
use Cake\Core\Configure;

if (Configure::read("wetkit.ui.search") === true):
    $searchBox = $this->fetch('wetkit-search');
    if ($this->elementExists('search')) {
        echo $this->element('search');
    } else if (trim($searchBox) != '') {
        echo $searchBox;
    } else {
        ?>


        <?php if (Configure::read('wetkit.wet.theme') == 'theme-base') { ?>
            <section id="wb-srch" class="col-md-4 visible-md visible-lg">
                <h2><?php echo __d('wet_kit', 'Search') ?></h2>

                <form action="https://google.ca/search" method="get" role="search" class="form-inline">
                    <div class="form-group">
                        <label for="wb-srch-q"><?php echo __d('wet_kit', 'Search website') ?></label>
                        <input id="wb-srch-q" class="form-control" name="q" type="search" value="" size="27"
                               maxlength="150">
                        <input type="hidden" name="q" value="site:wet-boew.github.io OR site:github.com/wet-boew/">
                    </div>
                    <button type="submit" id="wb-srch-sub" class="btn btn-default"><?php echo __d('wet_kit',
                            'Search') ?></button>
                </form>
            </section>
        <?php } ?>

        <?php if (Configure::read('wetkit.wet.theme') == 'theme-gc-intranet') { ?>
            <section id="wb-srch" class="visible-md visible-lg">
                <h2><?php echo __d('wet_kit', 'Search') ?></h2>

                <form action="http://search-recherche.ent.dfo-mpo.ca/index-eng.php" method="post" role="search"
                      class="form-inline">
                    <div class="form-group">
                        <label for="wb-srch-q"><?php echo __d('wet_kit', 'Search website') ?></label>
                        <input id="wb-srch-q" class="form-control" name="texthighlight" type="search" value="" size="27"
                               maxlength="150"/>
                    </div>
                    <button type="submit" id="wb-srch-sub" class="btn btn-default"><?php echo __d('wet_kit',
                            'Search') ?></button>
                </form>
            </section>
        <?php } ?>

        <?php if (Configure::read('wetkit.wet.theme') == 'theme-gcwu-fegc') { ?>
            <section id="wb-srch" class="visible-md visible-lg">
                <h2><?php echo __d('wet_kit', 'Search') ?></h2>

                <form action="http://recherche-search.gc.ca/rGs/s_r" method="get" role="search" class="form-inline">
                    <div class="form-group">
                        <label for="wb-srch-q"><?php echo __d('wet_kit', 'Search website') ?></label>
                        <input id="wb-srch-q" class="form-control" name="q" type="search" value="" size="27"
                               maxlength="150">
                        <input type="hidden" name="st" value="s"/>
                        <input type="hidden" name="s5bm3ts21rch" value="x"/>
                        <input type="hidden" name="num" value="10"/>
                        <input type="hidden" name="st1rt" value="0"/>
                        <input type="hidden" name="langs" value="<?php echo $lang ?>"/>
                        <input type="hidden" name="cdn" value="dfo"/>
                    </div>
                    <button type="submit" id="wb-srch-sub" class="btn btn-default"><?php echo __d('wet_kit',
                            'Search') ?></button>
                </form>
            </section>
        <?php } ?>



    <?php
    }

endif;
?>