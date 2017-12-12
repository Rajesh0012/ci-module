<html lang="en">
    <head>
        <title>CheckiOdds</title>
        <base href="http://<?= $_SERVER['HTTP_HOST'] ?>/">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="/public/images/favicon-16x16.png">
    </head>
    <table cellpadding="0" cellspacing="0" border="0" style="margin:0 auto; min-width:320px; max-width:600px; border:2px solid #0040ff;" width="100%">
        <tr style="background-color:#ffffff; padding:5px;">
            <td align="center"><a href="javascript:void(0);"><img src="<?php echo BASE_URL . '/admin/' ?>images/logo.svg" alt="BetCompare"></a></td>
        </tr>

        <tr><td>&nbsp; </td> </tr>
        <tr><td>&nbsp; </td> </tr>
        <tr><td>&nbsp; </td> </tr>

        <tr>
            <td style="   color: #383636;
                font-family: arial;
                font-size: 20px;
                padding: 0 35px;">Hi <?php echo $full_name; ?>, </td>
        </tr>
        <!--<tr>
            <td style="   color: #383636;
                font-family: arial;
                font-size: 20px;
                padding: 0 35px;">Your otp for verification is-  <?php echo $otp; ?>, </td>
        </tr>!-->
        <tr>
            <td style="color: #383636;
                font-family: arial;
                font-size: 16px;
                line-height: 26px;
                padding: 35px">Click on  link to verify account:
                <a href="<?php echo SITE_URL ?>/Otp_verify?check=<?php echo $hash_value; ?>">click this link</a>	 	
            </td>
        </tr>

        <tr>
            <td style="color: #383636;
                font-family: arial;
                font-size: 16px;
                line-height: 26px;
                padding: 35px"> Note:
                This link will expire after Click.
            </td>
        </tr>      

        <tr>
            <td style="color: #383636;
                font-family: arial;
                font-size: 16px;
                line-height: 26px;
                padding: 35px">
                <strong>Warm Regards,<br> 
                    The CheckiOdds Team
                </strong>
            </td>
        </tr>



    </table>
</html>
