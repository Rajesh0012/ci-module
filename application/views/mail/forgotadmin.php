<html lang="en">
    <head>
        <title>BetCompare</title>
        <base href="http://<?= $_SERVER['HTTP_HOST'] ?>/">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="<?php echo base_url() ?>public/admin/images/favicon-16x16.png">

    <table cellpadding="0" cellspacing="0" border="0" style="margin:0 auto; min-width:320px; max-width:600px; border:2px solid #0040ff;" width="100%">
        <tr style="background: #0c4f98 none repeat scroll 0 0; padding:5px;">
            <td align="center"><a href="javascript:void(0);"><img src="<?php echo base_url() ?>public/admin/images/logo.svg" alt="BetCompare"></a></td>
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
        <tr>
		<td style="color: #383636;
                font-family: arial;
                font-size: 16px;
                line-height: 26px;
                padding: 35px">Click on  link to reset password:
			<a href="<?php echo SITE_URL ?>/admin/auth/resetpassword?check=<?php echo $hash_value; ?>">click this link</a>	 	
		</td>
		</tr>
                
          <tr>
            <td style="color: #383636;
                font-family: arial;
                font-size: 16px;
                line-height: 26px;
                padding: 35px"> Note:
                       This link will expire after 24 hour.
            </td>
        </tr>      

        <tr>
            <td style="color: #383636;
                font-family: arial;
                font-size: 16px;
                line-height: 26px;
                padding: 35px">
                <strong>Warm Regards,<br> 
                        The Checkiodds Team
                </strong>
            </td>
        </tr>  


    </table>
