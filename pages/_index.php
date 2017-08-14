<?php
if (!empty($result_statistic)) {
    ?>
    <div class="container container_stats">
    <div class="row">
        <div class="col-md-12 body_position text-left">
            <span style="text-shadow: 3px 2px 3px black;">Statistics...</span>
        </div>
    </div>
    <hr style="margin: 0 auto;">

    <div class="row text-center text_bg_head">
        <div class="col-md-1 col-xs-1">#</div>
        <div class="col-md-3 col-xs-3">Original URL</div>
        <div class="col-md-3 col-xs-3">Short URL</div>
        <div class="col-md-1 col-xs-2">User IP</div>
        <div class="col-md-2 col-xs-3">Link Count</div>
        <div class="col-md-2 hidden-xs">Date Registration</div>
    </div>

    <?php
    foreach ($result_statistic as $key_count => $result_statistic_value){

    if ($key_count >= count($result_statistic) - 1){
    ?>
    <div class="row text-center result_row text_bg_stats text_bg_end"><?php
        }else{
        ?>
        <div class="row text-center result_row text_bg_stats"><?php
            }

            ?>
            <div class="col-md-1 col-xs-1"><?= $result_statistic_value[0]; ?></div>
            <div class="col-md-3 col-xs-3 text-left clip_dotted" alt="<?= $result_statistic_value[1]; ?>"
                 title="<?= $result_statistic_value[1]; ?>">
                <?= $result_statistic_value[1]; ?>
            </div>
            <div class="col-md-3 col-xs-3 text-left clip_dotted"><?= $result_statistic_value[2]; ?></div>
            <div class="col-md-1 col-xs-2"><?= $result_statistic_value[3]; ?></div>
            <div class="col-md-2 col-xs-3"><?= $result_statistic_value[4]; ?></div>
            <div class="col-md-2 hidden-xs"><?= $result_statistic_value[5]; ?></div>
        </div>
        <?php
        }
        ?>

    </div>

    <?php
}
?>