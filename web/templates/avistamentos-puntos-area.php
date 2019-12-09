<?php defined('ANS') or die(); ?>

<?php if ($avistamentos) { ?>
<div id="listado-avistamentos">
    <div class="ly-11">
        <ul class="listaxe ly-e1">
            <?php
            foreach ($avistamentos as $num => $avistamento) {
                if ($num % 6 != 0) {
                    echo $Html->each('</ul><ul class="listaxe ly-e2">', 3, $num - 1);
                }

                echo $Html->each('</ul></div><div class="ly-11"><ul class="listaxe ly-e1">', 6, $num - 1);

                $Templates->render('aux-avistamento.php', array(
                    'avistamento' => $avistamento,
                    'mapa' => true
                ));
            }
            ?>
        </ul>  
    </div>
</div>
<?php } ?>