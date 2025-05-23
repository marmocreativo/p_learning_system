<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"><head> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
<meta name="viewport" content="width=device-width"> 
<meta content="telephone=no" name="format-detection"> 
<meta content="date=no" name="format-detection"> 
<meta content="address=no" name="format-detection"> 
<meta content="email=no" name="format-detection"> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-CjHUnq1+kecTE52F7O2ZQj1LlZ/4tp9VVvW8UFPhZLbh9dU0Xc9x66zVPBrU50YhP0ErJ5fNpx6/qvWPC3e8dQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- FLEX Framework v3.6 by Digital Pi. --> 
<!-- EMAIL SETTINGS -->         
<!-- MODULE SETTINGS -->            
<!-- BANNER SETTINGS -->               
<!-- COL SETTINGS -->      
<!-- DIVIDER & BORDER SETTINGS -->         
<!-- IMAGE SETTINGS -->                    
<!-- TYPOGRAPHY SETTINGS H1-6 + P - Duplicate as needed -->                                                                                  
<!-- CTA SETTINGS -->                       
<!-- TOGGLES - Example in body of email -->                               
<title>{{$data['titulo']}}</title>
<style type="text/css">
    * {-webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;}
    html, body {margin: 0; padding: 0;}
    body {margin: 0 auto!important; padding: 0; font-family: Arial, sans-serif; -webkit-text-size-adjust: 100%!important; -ms-text-size-adjust: 100%!important; -webkit-font-smoothing: antialiased!important;}
    .mktoText a, .mktoSnippet a, a:link, a:visited {color: #017698; text-decoration: underline;}
    a[x-apple-data-detectors] {color: inherit!important; text-decoration: none!important; font-size: inherit!important; font-family: inherit!important; font-weight: inherit!important; line-height: inherit!important;}
    img {border: 0!important; outline: none!important; max-width:100%;}
    table {border-spacing: 0; mso-table-lspace: 0px; mso-table-rspace: 0px;}
    th {margin: 0; padding: 0;font-weight: normal;}
    div,td,a,span {mso-line-height-rule: exactly;}
    ul {margin: 0; padding: 0; padding-left: 20px; margin-left: 20px;}
    li {margin: 0; padding-top: 0px; margin-top: 0px;}
    [owa] .col, .col {display: table-cell!important;}

    .link-word-break a {word-break: break-all;}

    .link-normal a, .link-normal a:visited, .link-normal a:link {color: #017698; text-decoration: underline;}
    .link-light a, .link-light a:visited, .link-light a:link {color: #FFFFFF; text-decoration: underline;}

    .flex-button-a {
      margin:10px 10px; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold !important; background-color:#4e5091; border:0px solid #4e5091; border-radius:10px; border-collapse:collapse; text-align:center;
    }
	
	 .flex-button-a a, .flex-button-a a:visited, .flex-button-a a:link {
      padding:5px 10px 8px 10px; display:block; text-align:center; color:#FFFFFF !important; text-decoration:none !important;
    }
	.flex-button-a:hover {
     background: #474747 !important; border-color: #474747 !important;
    }
	

    .flex-button-b {
      margin:10px 10px; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold !important; background-color:#43B02A; border:0px solid #43B02A; border-radius:10px; border-collapse:collapse; text-align:center;
    }
	
	 .flex-button-b a, .flex-button-b a:visited, .flex-button-b a:link {
      padding:5px 10px 8px 10px; display:block; text-align:center; color:#FFFFFF !important; text-decoration:none !important;
    }
	.flex-button-b:hover {
     background: #474747 !important; border-color: #474747 !important;
    }
	
	.flex-button-c {
      margin:12px 10px; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold !important; background-color:#0085AD; border:0px solid #0085AD; border-radius:10px; border-collapse:collapse; text-align:center;
    }
	
	 .flex-button-c a, .flex-button-c a:visited, .flex-button-c a:link {
      padding:5px 10px 8px 10px; display:block; text-align:center; color:#FFFFFF !important; text-decoration:none !important;
    }
	.flex-button-c:hover {
     background: #474747 !important; border-color: #474747 !important;
    }
	
	.flex-button-d {
      margin:12px 10px; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold !important; background-color:#004976; border:0px solid #004976; border-radius:10px; border-collapse:collapse; text-align:center;
    }
	
	 .flex-button-d a, .flex-button-d a:visited, .flex-button-d a:link {
      padding:5px 10px 8px 10px; display:block; text-align:center; color:#FFFFFF !important; text-decoration:none !important;
    }
	.flex-button-d:hover {
     background: #474747 !important; border-color: #474747 !important;
    }
	
	.flex-button-e {
      margin:12px 10px; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold !important; background-color:#253746; border:0px solid #253746; border-radius:10px; border-collapse:collapse; text-align:center;
    }
	
	 .flex-button-e a, .flex-button-e a:visited, .flex-button-e a:link {
      padding:5px 10px 8px 10px; display:block; text-align:center; color:#FFFFFF !important; text-decoration:none !important;
    }
	.flex-button-e:hover {
     background: #474747 !important; border-color: #474747 !important;
    }

    @media only screen and (max-width:599px) {
      .mob-center {margin: 0px auto!important; float: none!important;}
      .mob-full {min-width: 100%!important; width: 100%!important; height: auto!important;}
      .img-full {width:100%!important; max-width:100%!important; height:auto!important;}
      .img-scale {width:100%!important; height:auto!important;}
      .col {display: block!important;}
      .mob-text-center {text-align: center!important;}
      .mob-text-default {}
      .mob-align-center {margin: 0 auto!important; float: none!important;}
      .mob-align-default {}
      .mob-hide {display:none!important; visibility: hidden!important;}
    }

    @media yahoo {
      * {overflow: visible!important;}
      .y-overflow-hidden {overflow: hidden!important;}
    }
	/* Hover styles for buttons */
		.button-td,
		.button-a {
			transition: all 100ms ease-in;
			width: 174px;
		}
		.button-td:hover,
		.button-a:hover {
			background: #474747 !important;
			border-color: #474747 !important;
		}
  </style> 

<style type="text/css"></style></head> 
<body class="body" style="background-color:#E8E8E6;">

<div style="background-color:#E8E8E6; table-layout: fixed; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; width:100%; vertical-align:top;"> 
<table class="mob-full mktoContainer" style="width:600px; overflow:visible; margin:0 auto; vertical-align:middle;" valign="middle" cellspacing="0" cellpadding="0" border="0" align="center">
<tbody><tr class="mktoModule"> 
<td style="background-color:#FFFFFF;"> 
<table style="min-width:100%;" width="100%" cellspacing="0" cellpadding="0" border="0"> 
<tbody> 
<tr> 
<td style="padding-left:20px; padding-right:20px; padding-top:20px; padding-bottom:20px;"> 
<table style="min-width:100%;" width="100%" cellspacing="0" cellpadding="0" border="0"> 
<tbody> 
<tr> 
<td align="left"> 
<table class="mob-align-default" cellspacing="0" cellpadding="0" border="0"> 
<tbody> 
<tr> 
<td> 
<div class="mktoSnippet"> 
<a href="https://www.panduit.com/latam/es/home.html" target="_blank" style="text-decoration: none;"><img src="https://panduitlatam.com/recursos/2023/panduitweek/logo-panduit.png" alt="Panduit" style="display: block; max-width: 100%; height: auto;" width="90" border="0"></a> 
</div> </td> 
</tr> 
</tbody> 
</table> </td> 
</tr> 
</tbody> 
</table> </td> 
</tr> 
</tbody> 
</table> </td> 
</tr>
<tr class="mktoModule"> 
<td style="background-color:#FFFFFF;"> 
<table style="min-width:100%;" width="100%" cellspacing="0" cellpadding="0" border="0"> 
<tbody> 
<tr> 
<td style="padding-left:0px; padding-right:0px; padding-top:0px; padding-bottom:20px;"> 
<table style="min-width:100%;" width="100%" cellspacing="0" cellpadding="0" border="0"> 
<tbody> 
<tr> 
<td align="center"> 
<div class="mktoImg" mktolockimgsize="true" mktolockimgstyle="true"> <a target="new"><img class="img-full mob-align-default img-full mob-align-default" src="https://p-learning.panduitlatam.com/assets/images/micrositio/1600x-300-Email-Banner-PLe.jpg" style="display:block; width:100% !important; max-width:600px;" width="600" border="0"></a> 
</div> </td> 
</tr> 
</tbody> 
</table> </td> 
</tr> 
</tbody> 
</table> </td> 
</tr>
	
<tr class="mktoModule"> 
<td bgcolor="#ffffff"> 
<table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" width="100%"> 
<tbody> 
<tr> 
<td style="padding: 0px 0 0 0;  text-align: left; font-family: sans-serif; font-size: 13px; line-height: 20px;"> <h2 class="mktoText" style="margin: 0; color:#333333;"> 
<div style="text-align: center;"><p style="color:#09517d;font-size:18px;line-height:30px;margin-bottom: 0px;"><strong>El premio que seleccionaste ya está en camino</strong>
</p> </div></h2> </td> 
</tr> 
</tbody> 
</table> </td> 
</tr>
	
	
<tr class="mktoModule"> 
<td bgcolor="#ffffff"> 
<table role="presentation" aria-hidden="true" width="100%" cellspacing="0" cellpadding="0" border="0"> 
<tbody> 
<tr> 
<td style="padding: 0px 50px; text-align: center; font-family: sans-serif; font-size: 15px; line-height: 22px; color: #333333;"> 
<div class="mktoText">
  <p>

    Tomaremos la dirección que nos diste y lo haremos llegar lo más pronto posible. Recuerda que nuestros envíos toman de 9 a 11 días hábiles, debido a los procesos de empaquetado y mensajería. Sin embargo, por la temporada podrían tardar un poco más.
    </p>
  
</div> </td> 
</tr> 
</tbody> 
</table> </td> 
</tr>

<tr class="mktoModule"> 
  <td bgcolor="#ffffff"> 
  <table role="presentation" aria-hidden="true" width="100%" cellspacing="0" cellpadding="0" border="0"> 
  <tbody> 
  <tr> 
  <td style="padding: 0px 180px; text-align: left; font-family: sans-serif; font-size: 14px; line-height: 22px; color: #333333;"> 
  <div class="mktoText">

      <table role="presentation" aria-hidden="true" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top: 0px;">
        <thead>
          <tr>
            <th><strong>Producto</strong></th>
            <th><strong>Créditos canjeados</strong></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($data['productos'] as $producto)
          <tr style="background-color: #d1d2d4;">
              <td style="padding: 0px 10px; font-family: sans-serif; font-size: 14px; color: #000;">
                {{$producto->nombre}} ({{$producto->variacion}})</td>
              <td style="padding: 0px 10px; font-family: sans-serif; font-size: 14px; color: #333333;">{{$producto->creditos_totales}}</td>
            </tr>
          @endforeach
        
      </tbody>
      </table>
  
     
    
  </div> </td> 
  </tr> 
  </tbody> 
  </table> </td> 
  </tr>

<tr class="mktoModule"> 
  <td bgcolor="#ffffff"> 
  <table role="presentation" aria-hidden="true" width="100%" cellspacing="0" cellpadding="0" border="0"> 
  <tbody> 
  <tr> 
  <td style="padding: 0px 40px; text-align: center; font-family: sans-serif; font-size: 14px; line-height: 22px; color: #333333;"> 
  <div class="mktoText">
      <p>
        Si recibiste este correo por error o necesitas comunicarte con nosotros, contáctanos.
      </p>
  
      <p><strong>Atte. Equipo PLearning</strong></p>
    
  </div> </td> 
  </tr> 
  </tbody> 
  </table> </td> 
  </tr>
	
<tr class="mktoModule"> 
  <td bgcolor="#ffffff"> 
  <table role="presentation" aria-hidden="true" width="100%" cellspacing="0" cellpadding="0" border="0"> 
  <tbody> 
  <tr> 
  <td style="padding: 0px 50px; text-align: center; font-family: sans-serif; font-size: 12px; line-height: 22px; color: #333333; padding-bottom: 20px;"> 
  <div class="mktoText">
    <p>Copyright Panduit. Todos los derechos reservados.</p>
    
  </div> </td> 
  </tr> 
  </tbody> 
  </table> </td> 
  </tr>

</tbody></table> 
</div> 
<!--[if gte mso 9]></td></tr></table><!--<![endif]-->  
<a href="https://go.panduit.com/MzQ5LUVRSS0zNjYAAAGJVyYV68OnjRbOVgSU4UA0xv7_d9NBGHgOALOVvmQJZ9Q6JKTMUBbRJEP-EcscVrO1AHm_Uxk="></a>
<img style="display: none;" alt="" width="1" height="1">
<link rel="stylesheet" type="text/css">
</body></html>