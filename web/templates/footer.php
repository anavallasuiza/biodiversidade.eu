<?php defined('ANS') or die(); ?>

<?php /*<footer class="paypal">
    <div class="wrapper">
        <!-- Formulario -->
        <div class="paypal-subscribe">
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <section>
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="GJ79FE6F63Z8J">
                    <input type="hidden" name="on0" value="Subscrición"><h1><?php echo __('Subscrición') ?></h1><select name="os0">
                        <option value="Doar mensualmente"><?php echo __('Doar mensualmente : €5,00 EUR - mensualmente') ?></option>
                    </select>
                </section>
                <section>
                    <input type="hidden" name="on1" value="Doar mensualmente"><h1><?php echo __('Doar mensualmente') ?></h1><input type="text" name="os1" maxlength="200">
                    <input type="hidden" name="currency_code" value="EUR">
                    <input type="image" src="https://www.paypalobjects.com/pt_PT/PT/i/btn/btn_subscribe_SM.gif" border="0" name="submit" alt="PayPal - A forma mais fácil e segura de efetuar pagamentos online!">
                    <img alt="" border="0" src="https://www.paypalobjects.com/es_ES/i/scr/pixel.gif" width="1" height="1">
                </section>
            </form>
        </div>

        <div class="paypal-donate">
            <h1><?php echo __('Doar') ?></h1>
            <!-- Botón -->
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="EVCLX5QQ7XU96">
            <input type="image" src="https://www.paypalobjects.com/pt_PT/PT/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - A forma mais fácil e segura de efetuar pagamentos online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/es_ES/i/scr/pixel.gif" width="1" height="1">
            </form>
        </div>
    </div>
</footer>*/ ?>

<footer class="main">
	<div class="wrapper">
		<ul class="main-menu">
			<li><a href="mailto:info@biodiversidade.eu"><?php echo __('info@biodiversidade.eu'); ?></a></li>
			<li><a href="<?php echo path('info', 'colofon'); ?>"><?php echo __('Colofón'); ?></a></li>
			<li><a href="<?php echo path('info', 'nota-legal'); ?>"><?php echo __('Nota legal'); ?></a></li>
			<li><a href="<?php echo path('info', 'licenza'); ?>"><?php echo __('Licenza dos contidos'); ?></a></li>
			<li><a href="<?php echo path('info', 'proxecto'); ?>"><?php echo __('O proxecto'); ?></a></li>
            <li><a href="<?php echo path('info', 'entra-na-comunidade'); ?>"><?php echo __('FAQ'); ?></a></li>
			<li><a href="<?php echo path('achegas'); ?>"><?php echo __('Achegas'); ?></a></li>
		</ul>
        <a class="boton-feedback fixed" href="<?php echo path('feedback'); ?>">
            <?php __e('Feedback'); ?>
        </a>
	</div>
</footer>

<footer class="wrapper">
	<ul>
		<li>
			<a href="<?php echo __('ligazon pe logo 1'); ?>">
				<?php
                	echo $Html->img(array(
                	'src' => $Html->imgSrc('templates|img/logo-1.png'),
                	));
                ?>
            </a>
        </li>
        <li>
			<a href="<?php echo __('ligazon pe logo 2'); ?>">
				<?php
                	echo $Html->img(array(
                	'src' => $Html->imgSrc('templates|img/logo-2.png'),
                	));
                ?>
            </a>
        </li>
        <li>
			<a href="<?php echo __('ligazon pe logo 3'); ?>">
				<?php
                	echo $Html->img(array(
                	'src' => $Html->imgSrc('templates|img/logo-3.png'),
                	));
                ?>
            </a>
        </li>
        <li>
			<a href="<?php echo __('ligazon pe logo 4'); ?>">
				<?php
                	echo $Html->img(array(
                	'src' => $Html->imgSrc('templates|img/logo-4.png'),
                	));
                ?>
            </a>
        </li>
        <li>
			<a href="<?php echo __('ligazon pe logo 5'); ?>">
				<?php
                	echo $Html->img(array(
                	'src' => $Html->imgSrc('templates|img/logo-5.png'),
                	));
                ?>
            </a>
        </li>
        <li>
			<a href="<?php echo __('ligazon pe logo 6'); ?>">
				<?php
                	echo $Html->img(array(
                	'src' => $Html->imgSrc('templates|img/logo-6.png'),
                	));
                ?>
            </a>
        </li>
        <li>
			<a href="<?php echo __('ligazon pe logo 7'); ?>">
				<?php
                	echo $Html->img(array(
                	'src' => $Html->imgSrc('templates|img/logo-7.png'),
                	));
                ?>
            </a>
        </li>
	</ul>
</footer>