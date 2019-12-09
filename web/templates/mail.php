<?php defined('ANS') or die(); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title><?php echo $Mail->Subject; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>

    <body style="background: #85b200; font-family: Arial, Helvetica, sans-serif; font-size: 14px; color: #555555; margin: 0;">
        <table width="100%">
            <tr>
                <td>
                    <table style="width: 650px; margin: 0 auto 0 auto; background: #f2f2f2; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 40px;">
                                <h1 style="font-size: 34px; line-height: 34px; margin-bottom: 20px; font-weight: normal;"><?php echo $Mail->Subject; ?></h1>
                                <?php echo $Mail->Body; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
