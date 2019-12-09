<?php defined('ANS') or die(); ?>

<section class="content">
    <div class="content wrapper ly-f1">
        <section class="subcontent ly-e1">
            <article class="texto texto-permalink paypal">
                <header>
                    <h1><?php echo $texto['titulo']; ?></h1>
                </header>

                <?php echo $texto['texto']; ?>

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
            </article>
        </section>
    </div>
</section>
