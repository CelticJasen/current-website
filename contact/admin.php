<?php
require_once( dirname(__FILE__).'/form.lib.php' );

define( 'PHPFMG_USER', "currentinc@townsqr.com" ); // must be a email address. for sending password to you.
define( 'PHPFMG_PW', "20111011-15d5" );

?>
<?php
/**
 * Copyright (C) : http://www.formmail-maker.com
*/

# main
# ------------------------------------------------------
error_reporting( E_ERROR ) ;
phpfmg_admin_main();
# ------------------------------------------------------




function phpfmg_admin_main(){
    $mod  = isset($_REQUEST['mod'])  ? $_REQUEST['mod']  : '';
    $func = isset($_REQUEST['func']) ? $_REQUEST['func'] : '';
    $function = "phpfmg_{$mod}_{$func}";
    if( !function_exists($function) ){
        phpfmg_admin_default();
        exit;
    };

    // no login required modules
    $public_modules   = false !== strpos('|captcha|', "|{$mod}|");
    $public_functions = false !== strpos('|phpfmg_mail_request_password||phpfmg_filman_download||phpfmg_image_processing||phpfmg_dd_lookup|', "|{$function}|") ;   
    if( $public_modules || $public_functions ) { 
        $function();
        exit;
    };
    
    return phpfmg_user_isLogin() ? $function() : phpfmg_admin_default();
}

function phpfmg_admin_default(){
    if( phpfmg_user_login() ){
        phpfmg_admin_panel();
    };
}



function phpfmg_admin_panel()
{    
    phpfmg_admin_header();
    phpfmg_writable_check();
?>    
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td valign=top style="padding-left:280px;">

<style type="text/css">
    .fmg_title{
        font-size: 16px;
        font-weight: bold;
        padding: 10px;
    }
    
    .fmg_sep{
        width:32px;
    }
    
    .fmg_text{
        line-height: 150%;
        vertical-align: top;
        padding-left:28px;
    }

</style>

<script type="text/javascript">
    function deleteAll(n){
        if( confirm("Are you sure you want to delete?" ) ){
            location.href = "admin.php?mod=log&func=delete&file=" + n ;
        };
        return false ;
    }
</script>


<div class="fmg_title">
    1. Email Traffics
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=1">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=1">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_EMAILS_LOGFILE) ){
            echo '<a href="#" onclick="return deleteAll(1);">delete all</a>';
        };
    ?>
</div>


<div class="fmg_title">
    2. Form Data
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=2">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=2">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_SAVE_FILE) ){
            echo '<a href="#" onclick="return deleteAll(2);">delete all</a>';
        };
    ?>
</div>

<div class="fmg_title">
    3. Form Generator
</div>
<div class="fmg_text">
    <a href="http://www.formmail-maker.com/generator.php" onclick="document.frmFormMail.submit(); return false;" title="<?php echo htmlspecialchars(PHPFMG_SUBJECT);?>">Edit Form</a> &nbsp;&nbsp;
    <a href="http://www.formmail-maker.com/generator.php" >New Form</a>
</div>
    <form name="frmFormMail" action='http://www.formmail-maker.com/generator.php' method='post' enctype='multipart/form-data'>
    <input type="hidden" name="uuid" value="<?php echo PHPFMG_ID; ?>">
    <input type="hidden" name="external_ini" value="<?php echo function_exists('phpfmg_formini') ?  phpfmg_formini() : ""; ?>">
    </form>

		</td>
	</tr>
</table>

<?php
    phpfmg_admin_footer();
}



function phpfmg_admin_header( $title = '' ){
    header( "Content-Type: text/html; charset=" . PHPFMG_CHARSET );
?>
<html>
<head>
    <title><?php echo '' == $title ? '' : $title . ' | ' ; ?>PHP FormMail Admin Panel </title>
    <meta name="keywords" content="PHP FormMail Generator, PHP HTML form, send html email with attachment, PHP web form,  Free Form, Form Builder, Form Creator, phpFormMailGen, Customized Web Forms, phpFormMailGenerator,formmail.php, formmail.pl, formMail Generator, ASP Formmail, ASP form, PHP Form, Generator, phpFormGen, phpFormGenerator, anti-spam, web hosting">
    <meta name="description" content="PHP formMail Generator - A tool to ceate ready-to-use web forms in a flash. Validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. ">
    <meta name="generator" content="PHP Mail Form Generator, phpfmg.sourceforge.net">

    <style type='text/css'>
    body, td, label, div, span{
        font-family : Verdana, Arial, Helvetica, sans-serif;
        font-size : 12px;
    }
    </style>
</head>
<body  marginheight="0" marginwidth="0" leftmargin="0" topmargin="0">

<table cellspacing=0 cellpadding=0 border=0 width="100%">
    <td nowrap align=center style="background-color:#024e7b;padding:10px;font-size:18px;color:#ffffff;font-weight:bold;width:250px;" >
        Form Admin Panel
    </td>
    <td style="padding-left:30px;background-color:#86BC1B;width:100%;font-weight:bold;" >
        &nbsp;
<?php
    if( phpfmg_user_isLogin() ){
        echo '<a href="admin.php" style="color:#ffffff;">Main Menu</a> &nbsp;&nbsp;' ;
        echo '<a href="admin.php?mod=user&func=logout" style="color:#ffffff;">Logout</a>' ;
    }; 
?>
    </td>
</table>

<div style="padding-top:28px;">

<?php
    
}


function phpfmg_admin_footer(){
?>

</div>

<div style="color:#cccccc;text-decoration:none;padding:18px;font-weight:bold;">
	:: <a href="http://phpfmg.sourceforge.net" target="_blank" title="Free Mailform Maker: Create read-to-use Web Forms in a flash. Including validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. " style="color:#cccccc;font-weight:bold;text-decoration:none;">PHP FormMail Generator</a> ::
</div>

</body>
</html>
<?php
}


function phpfmg_image_processing(){
    $img = new phpfmgImage();
    $img->out_processing_gif();
}


# phpfmg module : captcha
# ------------------------------------------------------
function phpfmg_captcha_get(){
    $img = new phpfmgImage();
    $img->out();
    $_SESSION[PHPFMG_ID.'fmgCaptchCode'] = $img->text ;
}



function phpfmg_captcha_generate_images(){
    for( $i = 0; $i < 50; $i ++ ){
        $file = "$i.png";
        $img = new phpfmgImage();
        $img->out($file);
        $data = base64_encode( file_get_contents($file) );
        echo "'{$img->text}' => '{$data}',\n" ;
        unlink( $file );
    };
}


function phpfmg_dd_lookup(){
    $paraOk = ( isset($_REQUEST['n']) && isset($_REQUEST['lookup']) && isset($_REQUEST['field_name']) );
    if( !$paraOk )
        return;
        
    $base64 = phpfmg_dependent_dropdown_data();
    $data = @unserialize( base64_decode($base64) );
    if( !is_array($data) ){
        return ;
    };
    
    
    foreach( $data as $field ){
        if( $field['name'] == $_REQUEST['field_name'] ){
            $nColumn = intval($_REQUEST['n']);
            $lookup  = $_REQUEST['lookup']; // $lookup is an array
            $dd      = new DependantDropdown(); 
            echo $dd->lookupFieldColumn( $field, $nColumn, $lookup );
            return;
        };
    };
    
    return;
}


function phpfmg_filman_download(){
    if( !isset($_REQUEST['filelink']) )
        return ;
        
    $info =  @unserialize(base64_decode($_REQUEST['filelink']));
    if( !isset($info['recordID']) ){
        return ;
    };
    
    $file = PHPFMG_SAVE_ATTACHMENTS_DIR . $info['recordID'] . '-' . $info['filename'];
    phpfmg_util_download( $file, $info['filename'] );
}


class phpfmgDataManager
{
    var $dataFile = '';
    var $columns = '';
    var $records = '';
    
    function phpfmgDataManager(){
        $this->dataFile = PHPFMG_SAVE_FILE; 
    }
    
    function parseFile(){
        $fp = @fopen($this->dataFile, 'rb');
        if( !$fp ) return false;
        
        $i = 0 ;
        $phpExitLine = 1; // first line is php code
        $colsLine = 2 ; // second line is column headers
        $this->columns = array();
        $this->records = array();
        $sep = chr(0x09);
        while( !feof($fp) ) { 
            $line = fgets($fp);
            $line = trim($line);
            if( empty($line) ) continue;
            $line = $this->line2display($line);
            $i ++ ;
            switch( $i ){
                case $phpExitLine:
                    continue;
                    break;
                case $colsLine :
                    $this->columns = explode($sep,$line);
                    break;
                default:
                    $this->records[] = explode( $sep, phpfmg_data2record( $line, false ) );
            };
        }; 
        fclose ($fp);
    }
    
    function displayRecords(){
        $this->parseFile();
        echo "<table border=1 style='width=95%;border-collapse: collapse;border-color:#cccccc;' >";
        echo "<tr><td>&nbsp;</td><td><b>" . join( "</b></td><td>&nbsp;<b>", $this->columns ) . "</b></td></tr>\n";
        $i = 1;
        foreach( $this->records as $r ){
            echo "<tr><td align=right>{$i}&nbsp;</td><td>" . join( "</td><td>&nbsp;", $r ) . "</td></tr>\n";
            $i++;
        };
        echo "</table>\n";
    }
    
    function line2display( $line ){
        $line = str_replace( array('"' . chr(0x09) . '"', '""'),  array(chr(0x09),'"'),  $line );
        $line = substr( $line, 1, -1 ); // chop first " and last "
        return $line;
    }
    
}
# end of class



# ------------------------------------------------------
class phpfmgImage
{
    var $im = null;
    var $width = 73 ;
    var $height = 33 ;
    var $text = '' ; 
    var $line_distance = 8;
    var $text_len = 4 ;

    function phpfmgImage( $text = '', $len = 4 ){
        $this->text_len = $len ;
        $this->text = '' == $text ? $this->uniqid( $this->text_len ) : $text ;
        $this->text = strtoupper( substr( $this->text, 0, $this->text_len ) );
    }
    
    function create(){
        $this->im = imagecreate( $this->width, $this->height );
        $bgcolor   = imagecolorallocate($this->im, 255, 255, 255);
        $textcolor = imagecolorallocate($this->im, 0, 0, 0);
        $this->drawLines();
        imagestring($this->im, 5, 20, 9, $this->text, $textcolor);
    }
    
    function drawLines(){
        $linecolor = imagecolorallocate($this->im, 210, 210, 210);
    
        //vertical lines
        for($x = 0; $x < $this->width; $x += $this->line_distance) {
          imageline($this->im, $x, 0, $x, $this->height, $linecolor);
        };
    
        //horizontal lines
        for($y = 0; $y < $this->height; $y += $this->line_distance) {
          imageline($this->im, 0, $y, $this->width, $y, $linecolor);
        };
    }
    
    function out( $filename = '' ){
        if( function_exists('imageline') ){
            $this->create();
            if( '' == $filename ) header("Content-type: image/png");
            ( '' == $filename ) ? imagepng( $this->im ) : imagepng( $this->im, $filename );
            imagedestroy( $this->im ); 
        }else{
            $this->out_predefined_image(); 
        };
    }

    function uniqid( $len = 0 ){
        $md5 = md5( uniqid(rand()) );
        return $len > 0 ? substr($md5,0,$len) : $md5 ;
    }
    
    function out_predefined_image(){
        header("Content-type: image/png");
        $data = $this->getImage(); 
        echo base64_decode($data);
    }
    
    // Use predefined captcha random images if web server doens't have GD graphics library installed  
    function getImage(){
        $images = array(
			'2DD6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7WANEQ1hDGaY6IImJTBFpZW10CAhAEgtoFWl0bQh0EEDWDRVDcd+0aStTV0WmZiG7LwCsDsU8RgeIXhFktzRgiok0YLolNBTTzQMVflSEWNwHADGyzQkOv71cAAAAAElFTkSuQmCC',
			'01E3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7GB0YAlhDHUIdkMRYAxgDWIEyAUhiIlNYgWJAGkksoJUBLBaA5L6opUAUumppFpL70NShiImg2IEpxhrAgOEWRgfWUHQ3D1T4URFicR8AnkzJW3oxVZoAAAAASUVORK5CYII=',
			'DC05' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QgMYQxmmMIYGIIkFTGFtdAhldEBWF9Aq0uDo6IghxtoQ6OqA5L6opdNWLV0VGRWF5D6IuoAGEQy9mGIgO0Qw3MIQgOw+iJsZpjoMgvCjIsTiPgDgZ82y0yzzhAAAAABJRU5ErkJggg==',
			'E046' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QkMYAhgaHaY6IIkFNDCGMLQ6BASgiLG2Mkx1dBBAERNpdAh0dEB2X2jUtJWZmZmpWUjuA6lzbXREMw8oFhroIIJuR6MjmhjQLY2obsHm5oEKPypCLO4DAMzJzYgNaZl+AAAAAElFTkSuQmCC',
			'15E5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7GB1EQ1lDHUMDkMRYHUQaWIEyyOpEsYgxOoiEAMVcHZDctzJr6tKloSujopDcB9TV6AqkRVD0YhMTAYoBSRQx1lbWBoYAZPeJhjCGsIY6THUYBOFHRYjFfQDKg8gfVLUv5QAAAABJRU5ErkJggg==',
			'49FA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpI37pjCGsIYGtKKIhbC2sjYwTHVAEmMMEWl0bWAICEASY50CEmN0EEFy37RpS5emhq7MmobkvoApjIFI6sAwNJQBpDc0BMUtLI3o6himgNyCLgZ0M7rYQIUf9SAW9wEAuW/K4yUPOfQAAAAASUVORK5CYII=',
			'72F7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkMZQ1hDA0NDkEVbWVtZgbQIiphIoyu62BQGsFgAsvuiVi1dGrpqZRaS+xgdGKYAzWtFthfID2AFmYAkJgJUCRJHFgsAqmQFmYAiJhrqiiY2UOFHRYjFfQCu6sq/t9BqxQAAAABJRU5ErkJggg==',
			'A77E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7GB1EQ11DA0MDkMRYAxgaHRoCHZDViUzBFAtoZWhlaHSEiYGdFLV01bRVS1eGZiG5D6gugGEKI4re0FAgP4ARzTxWoHvQxUQaQKJYxFDcPFDhR0WIxX0ARvzKjqXxNncAAAAASUVORK5CYII=',
			'D74D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QgNEQx0aHUMdkMQCpjA0OrQ6OgQgi7UCxaY6OoigirUyBMLFwE6KWrpq2srMzKxpSO4DqgtgbUTXy+jAGhqIJsbawICubooIWAzZLaEBYDEUNw9U+FERYnEfAFcZzcWXEGUYAAAAAElFTkSuQmCC',
			'5088' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QkMYAhhCGaY6IIkFNDCGMDo6BASgiLG2sjYEOoggiQUGiDQ6ItSBnRQ2bdrKrNBVU7OQ3deKog4u5opmXkArph0iUzDdwhqA6eaBCj8qQizuAwDmM8vbKxYzcwAAAABJRU5ErkJggg==',
			'916A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WAMYAhhCGVqRxUSmMAYwOjpMdUASC2hlDWBtcAgIQBFjAIoxOogguW/a1FVRS6euzJqG5D5WV6A6R0eYOggE6w0MDUESE4CIoagTmcIAdAuqXqBLQhlCGVHNG6DwoyLE4j4AIXHIugkdQiEAAAAASUVORK5CYII=',
			'40BC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpI37pjAEsIYyTA1AFgthDGFtdAgQQRIDirSyNgQ6sCCJsU4RaXRtdHRAdt+0adNWpoauzEJ2XwCqOjAMDQWKAc1DdQumHQxTMN2C1c0DFX7Ug1jcBwD0B8tU2USU8wAAAABJRU5ErkJggg==',
			'3954' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7RAMYQ1hDHRoCkMQCprC2sjYwNCKLMbSKNLoCSRSxKUCxqQxTApDctzJq6dLUzKyoKGT3TWEMdGgIdEA1j6ERKBYagiLGArQjAMMtjI6o7gO5mSGUAUVsoMKPihCL+wBFxs3co7lSMAAAAABJRU5ErkJggg==',
			'31B4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7RAMYAlhDGRoCkMQCpjAGsDY6NCKLMbSyBrA2BLSiiE1hAKmbEoDkvpVRq6KWhq6KikJ2H1idowOqeUCxhsDQEAyxADS3gO1AERMFuhjdzQMVflSEWNwHABLVzCNAcsV4AAAAAElFTkSuQmCC',
			'5015' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nM3QsQ2AIBCF4XcFGzDQNfZnAhaM4BRYsAGyA04pdqdSasJd94eEL4fjNREj7S8+7yDI5EU1ieTgiHFrJtGjzWI3zjSx8i2l1LXUELQvXe8Qrf650ySZ1Cysm83NkiHaZwRCnnce4H4fbsd3Aktkys0bNFVUAAAAAElFTkSuQmCC',
			'261C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WAMYQximMEwNQBITmcLayhDCECCCJBbQKtLIGMLowIKsuxWoYgqjA4r7pk0LWzVtZRaK+wJEW5HUgSGjg0ijA5oYawNEDNkOkQagW6aguiU0FOiSUAcUNw9U+FERYnEfACA+yeBeXW/8AAAAAElFTkSuQmCC',
			'3569' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7RANEQxlCGaY6IIkFTBFpYHR0CAhAVtkq0sDa4Ogggiw2RSSEtYERJgZ20sqoqUuXTl0VFYbsvikMja6ODlNR9LYCxRoCGlDFREBiKHYETGFtRXeLaABjCLqbByr8qAixuA8AqJrL4sieGDgAAAAASUVORK5CYII=',
			'0842' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7GB0YQxgaHaY6IImxBrC2MrQ6BAQgiYlMEQGqcnQQQRILaAWqC3RoEEFyX9TSlWErM7NWRSG5D6SOtdGh0QFFr0ija2hAKwO6HY0OUxjQ3dLoEIDpZsfQkEEQflSEWNwHABXLzOYqdO4QAAAAAElFTkSuQmCC',
			'19DE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDGUMDkMRYHVhbWRsdHZDViTqINLo2BDqg6kURAztpZdbSpamrIkOzkNwHtCMQUy8DFvNYsIhhcUsIppsHKvyoCLG4DwBSKchUp46wJQAAAABJRU5ErkJggg==',
			'BDCF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7QgNEQxhCHUNDkMQCpoi0MjoEOiCrC2gVaXRtEEQVmwISY4SJgZ0UGjVtZeqqlaFZSO5DU4dkHjYxDDsw3AJ1M4rYQIUfFSEW9wEACHzL7WfAtN8AAAAASUVORK5CYII=',
			'E409' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QkMYWhmmMEx1QBILaGCYyhDKEBCAKhbK6OjoIIIixujK2hAIEwM7KTRq6dKlq6KiwpDcF9Ag0sraEDAVVa9oqCtIBtWOVkZHBwd0MXS3YHPzQIUfFSEW9wEAmoLMoF1P51cAAAAASUVORK5CYII=',
			'042B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nM2QsRGAQAgEj+A7ePvBwBwDEjvQKkzoQEv4QKv0zWA01FEu2xmOHbBfZsaf8oofMQxKyo4lwUpty+JYXqBp7jk7JkYdKhPnN5RS9m3UyfmJZYNR6BNrlBcKffWGQSKrLnUz7p7OSfvg/NX/HsyN3wE9AsnQ8Z+p6QAAAABJRU5ErkJggg==',
			'BE9A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QgNEQxlCGVqRxQKmiDQwOjpMdUAWaxVpYG0ICAhAU8faEOggguS+0KipYSszI7OmIbkPpI4hBK4Obh5DQ2BoCJoYYwOaOrBbHFHEIG5mRBEbqPCjIsTiPgC6HsxzGw8ZzAAAAABJRU5ErkJggg==',
			'D8AB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QgMYQximMIY6IIkFTGFtZQhldAhAFmsVaXR0dHQQQRFjbWVtCISpAzspaunKsKWrIkOzkNyHpg5unmtoIJp5QLEGNLEpmHpBbgaKobh5oMKPihCL+wAKxM3p+6+sbQAAAABJRU5ErkJggg==',
			'BA86' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QgMYAhhCGaY6IIkFTGEMYXR0CAhAFmtlbWVtCHQQQFEn0ujo6OiA7L7QqGkrs0JXpmYhuQ+qDs080VBXoHkiKGIijRhiYL2obgkNEGl0QHPzQIUfFSEW9wEAt8/Nw++rMJ4AAAAASUVORK5CYII=',
			'53E4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QkNYQ1hDHRoCkMQCGkRaWRsYGlHFGBpdGxhakcUCAxhA6qYEILkvbNqqsKWhq6KikN3XClLH6ICsFygGNI8xNATZDrAYA4pbRKaA3YIixhqA6eaBCj8qQizuAwDOAs1iR9DD5wAAAABJRU5ErkJggg==',
			'3CC7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7RAMYQxlCHUNDkMQCprA2OjoENIggq2wVaXBtEEAVmyLSwApSj+S+lVHTVi0FUlnI7oOoa2VAMw8oNgVdDGhHAAOGWwIdsLgZRWygwo+KEIv7AF4RzBH+aUBHAAAAAElFTkSuQmCC',
			'B702' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nM2QsQ2AMAwEnSIbhH3sDVzgJtM4RTYIbECTKTEVsaAEKf7u9fo/GfrjFGbSL3zCi2CDDQePGxQUYB69CoWIMPlcjcqaBj7JfT96Nt18lmPLFbdRA8ar1XlRAxmN27B2Y/HM5rUg6wT/+1AvfCdWDs22EGRL9QAAAABJRU5ErkJggg==',
			'09F8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDA6Y6IImxBrC2sjYwBAQgiYlMEWl0BaoWQRILaAWJwdWBnRS1dOnS1NBVU7OQ3BfQyhjoimZeQCsDhnkiU1gwxLC5BezmBgYUNw9U+FERYnEfAE4Ty231DehpAAAAAElFTkSuQmCC',
			'0FAF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7GB1EQx2mMIaGIImxBog0MIQyOiCrE5ki0sDo6IgiFtAq0sDaEAgTAzspaunUsKWrIkOzkNyHpg4hFhqIYQe6OpBb0MUYHTDFBir8qAixuA8AG/LJ0UUttS4AAAAASUVORK5CYII=',
			'0BAD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7GB1EQximMIY6IImxBoi0MoQyOgQgiYlMEWl0dHR0EEESC2gVaWVtCISJgZ0UtXRq2NJVkVnTkNyHpg4m1ugaiioGssMVTR3ILSC9yG4BuRkohuLmgQo/KkIs7gMAUTDLwo0JS2UAAAAASUVORK5CYII=',
			'A1B9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7GB0YAlhDGaY6IImxBjAGsDY6BAQgiYlMYQ1gbQh0EEESC2gF6m10hImBnRS1FIhCV0WFIbkPos5hKrLe0FCgWENAA4Z5DQFY7EB1S0Arayi6mwcq/KgIsbgPAEVZywNCukZCAAAAAElFTkSuQmCC',
			'C14D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WEMYAhgaHUMdkMREWhkDGFodHQKQxAIaWQMYpjo6iCCLNQD1BsLFwE6KAqKVmZlZ05DcB1LH2oiplzU0EFWsEewWFDGRVogYsltYQ1hD0d08UOFHRYjFfQC2gco6Ow09UwAAAABJRU5ErkJggg==',
			'01BF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7GB0YAlhDGUNDkMRYAxgDWBsdHZDViUxhDWBtCEQRC2hlQFYHdlLUUiAKXRmaheQ+NHUIMTTzRKZgirEGYOpldGANBboZRWygwo+KEIv7AHsPx4ghx+43AAAAAElFTkSuQmCC',
			'1410' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7GB0YWhmmADGSGKsDw1SGEIapDkhiog4MoYwhDAEBKHoZXRmmMDqIILlvZdbSpaumrcyahuQ+oIpWJHVQMdFQBwwxsFvQ7ACLobolhKGVMdQBxc0DFX5UhFjcBwAWuchL+3bLJgAAAABJRU5ErkJggg==',
			'348E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7RAMYWhlCGUMDkMQCpjBMZXR0dEBRCVTF2hCIKjaF0RVJHdhJK6OWLl0VujI0C9l9U0RaMc0TDXVFN6+VoRXdDqBbMPRic/NAhR8VIRb3AQA3CskTjP4CvgAAAABJRU5ErkJggg==',
			'5BEC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkNEQ1hDHaYGIIkFNIi0sjYwBIigijW6NjA6sCCJBQaA1DE6ILsvbNrUsKWhK7NQ3NeKog4mBjYPWSygFdMOkSmYbmENwHTzQIUfFSEW9wEA5+7LCklwJPoAAAAASUVORK5CYII=',
			'59CF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QkMYQxhCHUNDkMQCGlhbGR0CHRhQxEQaXRsEUcQCA0BijDAxsJPCpi1dmrpqZWgWsvtaGQOR1EHFGBrRxQJaWTDsEJmC6RbWALCbUc0boPCjIsTiPgDDs8n1vGxWDAAAAABJRU5ErkJggg==',
			'4DBE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpI37poiGsIYyhgYgi4WItLI2Ojogq2MMEWl0bQhEEWOdAhRDqAM7adq0aStTQ1eGZiG5LwBVHRiGhmKaxzAFqxiGW7C6eaDCj3oQi/sAMJnLg1O43KIAAAAASUVORK5CYII=',
			'31AF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7RAMYAhimMIaGIIkFTGEMYAhldEBR2coawOjoiCo2hSGAtSEQJgZ20sqoVVFLV0WGZiG7D1Ud1DygWCgWMTR1AVj0igJ1Ypg3QOFHRYjFfQCZYMfS8CCkIgAAAABJRU5ErkJggg==',
			'B30D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QgNYQximMIY6IIkFTBFpZQhldAhAFmtlaHR0dHQQQVHH0MraEAgTAzspNGpV2NJVkVnTkNyHpg5unisWMUw7MN2Czc0DFX5UhFjcBwCB88x49NyrCwAAAABJRU5ErkJggg==',
			'C221' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WEMYQxhCGVqRxURaWVsZHR2mIosFNIo0ujYEhKKINTA0OjQEwPSCnRS1atXSVSuzliK7D6huCtCGVjS9AWBRFDsYHYCi6G5pAIqiiLGGiIa6hgaEBgyC8KMixOI+AOkIy+YdFFvrAAAAAElFTkSuQmCC',
			'5BCA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7QkNEQxhCHVqRxQIaRFoZHQKmOqCKNbo2CAQEIIkFBoi0sjYwOogguS9s2tSwpatWZk1Ddl8rijqYGNA8xtAQZDvAYoIo6kSmgNwSiCLGGgBysyOqeQMUflSEWNwHADPYy+eyGE64AAAAAElFTkSuQmCC',
			'0AD0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7GB0YAlhDGVqRxVgDGENYGx2mOiCJiUxhbWVtCAgIQBILaBVpdG0IdBBBcl/U0mkrU1dFZk1Dch+aOqiYaCi6mMgUkDpUO1gDgGJobmF0AIqhuXmgwo+KEIv7ACc6zUoSU8blAAAAAElFTkSuQmCC',
			'5C1E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QkMYQxmmMIYGIIkFNLA2OoQwOjCgiIk0OKKJBQaINAD1wsTATgqbNm3VqmkrQ7OQ3deKog6nWABQzAFNTGQK0C1oYqwBjKGMoY4obh6o8KMixOI+AOd/ykvsGl06AAAAAElFTkSuQmCC',
			'5223' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7QkMYQxhCGUIdkMQCGlhbGR0dHQJQxEQaXUEkklhgAEOjA1AsAMl9YdNWLV21MmtpFrL7WhmmAHEDsnlAfgBQFMW8gFZGB6AoipjIFNYGRgdGFLewBoiGuoYGoLh5oMKPihCL+wCy68xZwonV0wAAAABJRU5ErkJggg==',
			'44E5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpI37pjC0soY6hgYgi4UwTGVtYHRAVscYwhCKLsY6hdEVKObqgOS+adOWLl0aujIqCsl9AVNEWlmBtAiS3tBQ0VBXNDGwW4B2YIoxBASgi4U6THUYDOFHPYjFfQBR88o29OSGUQAAAABJRU5ErkJggg==',
			'68D8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDGaY6IImJTGFtZW10CAhAEgtoEWl0bQh0EEEWawCqawiAqQM7KTJqZdjSVVFTs5DcFzIFRR1EbysW87CIYXMLNjcPVPhREWJxHwAJpc24VyOv4wAAAABJRU5ErkJggg==',
			'4AB2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpI37pjAEsIYyTHVAFgthDGFtdAgIQBIDirSyNgQ6iCCJsU4RaXRtdGgQQXLftGnTVqaGrloVheS+AIi6RmQ7QkNFQ10bAlpR3QJUB1SNIQZ0C4ZYKGNoyGAIP+pBLO4DAK2Uzb1BUmXhAAAAAElFTkSuQmCC',
			'4593' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpI37poiGMgChA7JYiEgDo6OjQwCSGCNQjLUhoEEESYx1ikgISCwAyX3Tpk1dujIzamkWkvsCpjA0OoTA1YFhaChQDM08hikijY4YYqyt6G5hmMIYguHmgQo/6kEs7gMAsTTMwZeCdvQAAAAASUVORK5CYII=',
			'8FE8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAU0lEQVR4nGNYhQEaGAYTpIn7WANEQ11DHaY6IImJTBFpYG1gCAhAEgtoBYkxOojgVgd20tKoqWFLQ1dNzUJyH7HmEWEH1M1AMTQ3D1T4URFicR8A34TL60dAMAQAAAAASUVORK5CYII=',
			'49BE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpI37pjCGsIYyhgYgi4WwtrI2Ojogq2MMEWl0bQhEEWOdAhRDqAM7adq0pUtTQ1eGZiG5L2AKY6ArmnmhoQwY5jFMYcEihukWrG4eqPCjHsTiPgBtfsrhb07JKQAAAABJRU5ErkJggg==',
			'FA54' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkMZAlhDHRoCkMQCGhhDWBsYGlHFWFuBYq2oYiKNrlMZpgQguS80atrK1MysqCgk94HUOTQEOqDqFQXaGhgagm4ekES3w9ER3X1A80IZUMQGKvyoCLG4DwBZEM/gI0jTJwAAAABJRU5ErkJggg==',
			'64C4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WAMYWhlCHRoCkMREpjBMZXQIaEQWC2hhCGVtEGhFEWtgdGVtYJgSgOS+yKilS5euWhUVheS+kCkirawNQBOR9baKhro2MIaGoIgxANUJoLulFaQTWQybmwcq/KgIsbgPAACUzbY0hBtyAAAAAElFTkSuQmCC',
			'C5DB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7WENEQ1lDGUMdkMREWkUaWBsdHQKQxAIagWINgQ4iyGINIiEgsQAk90Wtmrp06arI0Cwk9wHlG10R6lDERFDtwBATaWVtRXcLawhjCLqbByr8qAixuA8ADYnM7uKMGjEAAAAASUVORK5CYII=',
			'2656' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAe0lEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDHaY6IImJTGFtZW1gCAhAEgtoFWlkbWB0EEDW3SrSwDqV0QHFfdOmhS3NzEzNQnZfgGgrQ0MginmMDiKNDg2BDiLIbmkQaXRFEwPa0Mro6ICiNzSUMYQhlAHFzQMVflSEWNwHAHLSyuQBIl5ZAAAAAElFTkSuQmCC',
			'0772' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7GB1EQ11DA6Y6IImxBjA0OjQEBAQgiYlMAYkFOoggiQW0MrSCREWQ3Be1dNW0VUuBNJL7gOoCGMD6kfUyOgBFWxlQ7GAFugeoEsUtIg2sDUCVKG4GiTGGhgyC8KMixOI+AFDiy90lMLPXAAAAAElFTkSuQmCC',
			'4809' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpI37pjCGMExhmOqALBbC2soQyhAQgCTGGCLS6Ojo6CCCJMY6hbWVtSEQJgZ20rRpK8OWroqKCkNyXwBYXcBUZL2hoSKNrg0BDSIobgHZ4eCAKobpFqxuHqjwox7E4j4AXuXLuuDKXKYAAAAASUVORK5CYII=',
			'0C2B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7GB0YQxlCGUMdkMRYA1gbHR0dHQKQxESmiDS4NgQ6iCCJBbSCeIEwdWAnRS2dtmrVyszQLCT3gdW1MqKYBxabwohiHsgOhwBUMbBbHFD1gtzMGhqI4uaBCj8qQizuAwCb2crbOfOVjgAAAABJRU5ErkJggg==',
			'714F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7QkMZAhgaHUNDkEVbGQMYWh0dUFS2sgYwTEUTmwLUGwgXg7gpalXUyszM0Cwk9zE6MASwNqLqZW0AioUGooiJNIDdgiIWgFWMNRRdbKDCj4oQi/sA9vfIAPXx83UAAAAASUVORK5CYII=',
			'DACE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QgMYAhhCHUMDkMQCpjCGMDoEOiCrC2hlbWVtEEQTE2l0bWCEiYGdFLV02srUVStDs5Dch6YOKiYaiikGUodmxxSRRkc0t4QGiDQ6oLl5oMKPihCL+wAKssxVUknIeAAAAABJRU5ErkJggg==',
			'F21E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkMZQximMIYGIIkFNLC2MoQwOjCgiIk0OmKIMTQ6TIGLgZ0UGrVq6appK0OzkNwHVDeFYQqG3gBMMSAfQ4y1AVNMNNQRCJHdPFDhR0WIxX0AOAPKrok1ddgAAAAASUVORK5CYII=',
			'5D83' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7QkNEQxhCGUIdkMQCGkRaGR0dHQJQxRpdQSSSWGCASCNQWUMAkvvCpk1bmRW6amkWsvtaUdTBxdDNC8AiJjIF0y2sAZhuHqjwoyLE4j4AOa7Nuh+mknoAAAAASUVORK5CYII=',
			'30DF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7RAMYAlhDGUNDkMQCpjCGsDY6OqCobGVtZW0IRBWbItLoihADO2ll1LSVqasiQ7OQ3YeqDmoeNjFMO7C5BepmVL0DFH5UhFjcBwCIx8n1UCfQugAAAABJRU5ErkJggg==',
			'E9AE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QkMYQximMIYGIIkFNLC2MoQyOjCgiIk0Ojo6Yoi5NgTCxMBOCo1aujR1VWRoFpL7AhoYA5HUQcUYGl1D0cVYGjHVsbayoomB3AwUQ3HzQIUfFSEW9wEAAB3MPuJHf7YAAAAASUVORK5CYII=',
			'2097' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nM3QrRGAMAyG4UR0gwzUCvwnWsMGZYogsgHHDjAlPyo9kHCQuMfkvdB6GaU/7St9AQQqXLIzmThziirOYMGCojEyGbvd4Pvmeam1XwbfBxljhvm7HHdTTE2LBmMFvIkeLSl6K+Vsbuyr/z24N30bqCrKu3VixxgAAAAASUVORK5CYII=',
			'7207' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nM2QsRGAIAxFQ8EGuE8o7FOQhhGcIhRskBUoZEqxC6elnuZ37/4l7wL9MgJ/yit+zC6BOk6WVl+BQcLEQokRZ6ZQVqER45d7az3vm/FzCOqFqr3rBWgwtSyMpotIltHZ5LFhYgujzuyr/z2YG78DR5XLR5GUKHoAAAAASUVORK5CYII=',
			'F348' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkNZQxgaHaY6IIkFNIi0MrQ6BASgiIFUOTqIoIq1MgTC1YGdFBq1KmxlZtbULCT3gdSxNmKa5xoaiG5eo0Mjuh1At2DoxXTzQIUfFSEW9wEAHAPOmOhp3wIAAAAASUVORK5CYII=',
			'30F6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7RAMYAlhDA6Y6IIkFTGEMYW1gCAhAVtnK2srawOgggCw2RaTRFSiG7L6VUdNWpoauTM1Cdh9EHZp5EL0iWOwQIeAWsJsbGFDcPFDhR0WIxX0AFCHKflGXGIIAAAAASUVORK5CYII=',
			'D705' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nM2QsRGAMAhFSZENdB9S2GNBYaYhRTZAN0iTKU1JjKXehV/xDo53QB1KYKb84se0MqpjMowUErJDO0cZUgjhybKXfUPjF0u9Sj1iNH5tjryQLN2uw5F5ce1Gx7R1DGT9mBpTOHGC/32YF78bMgPNBe8pTo0AAAAASUVORK5CYII=',
			'DB9A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QgNEQxhCGVqRxQKmiLQyOjpMdUAWaxVpdG0ICAhAFWtlbQh0EEFyX9TSqWErMyOzpiG5D6SOIQSuDm6eQ0NgaAiamGMDmjqwWxxRxCBuZkQRG6jwoyLE4j4A/lfNerBMuQQAAAAASUVORK5CYII=',
			'94FD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WAMYWllDA0MdkMREpjBMZW1gdAhAEgtoZQgFiYmgiDG6IomBnTRt6tKlS0NXZk1Dch+rq0grul6GVtFQVzQxgVYGDHVAt7SiuwXs5gZGFDcPVPhREWJxHwA3Msm7z4RUmAAAAABJRU5ErkJggg==',
			'2CEB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7WAMYQ1lDHUMdkMREprA2ujYwOgQgiQW0ijSAxESQdQPFWBHqIG6aNm3V0tCVoVnI7gtAUQeGIJNY0cxjbcC0A6gKwy2hoZhuHqjwoyLE4j4AZBjKukYei5YAAAAASUVORK5CYII=',
			'E58F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QkNEQxlCGUNDkMQCGkQaGB0dHRjQxFgbAtHFQpDUgZ0UGjV16arQlaFZSO4LaGBodMQwj6HRFdM8LGKsrehuCQ1hDAG6GUVsoMKPihCL+wBiGsqwpgAY+AAAAABJRU5ErkJggg==',
			'CCDC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7WEMYQ1lDGaYGIImJtLI2ujY6BIggiQU0ijS4NgQ6sCCLNYg0sALFkN0XtWraqqWrIrOQ3YemDrcYFjuwuQWbmwcq/KgIsbgPAGKEzSWHE48xAAAAAElFTkSuQmCC',
			'1082' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGaY6IImxOjCGMDo6BAQgiYk6sLayNgQ6iKDoFWl0dHRoEEFy38qsaSuzQletikJyH1RdowOaXteGgFZUt4DsCJiCKgZxC7KYaAjIzYyhIYMg/KgIsbgPAPMLyNN/mIAwAAAAAElFTkSuQmCC',
			'632D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WANYQxhCGUMdkMREpoi0Mjo6OgQgiQW0MDS6NgQ6iCCLNTC0MiDEwE6KjFoVtmplZtY0JPeFTAGqa2VE1dvK0OgwBYtYAKoY2C0OjChuAbmZNTQQxc0DFX5UhFjcBwCYu8rieKPk4gAAAABJRU5ErkJggg==',
			'5D62' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7QkNEQxhCGaY6IIkFNIi0Mjo6BASgijW6Njg6iCCJBQaAxBgaRJDcFzZt2srUqatWRSG7rxWoztGhEdkOsFhDQCuyWwIgYlOQxUSmQNyCLMYaAHIzY2jIIAg/KkIs7gMA7DXNa79X5JIAAAAASUVORK5CYII=',
			'21DD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7WAMYAlhDGUMdkMREpjAGsDY6OgQgiQW0sgawNgQ6iCDrbmVAFoO4adqqqKWrIrOmIbsvgAFDL6MDphhrA6aYCEgMzS2hoayh6G4eqPCjIsTiPgBaw8kizdqyAgAAAABJRU5ErkJggg==',
			'7E73' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QkNFQ1lDA0IdkEVbRYBkoEMAhlhAgwiy2BQgr9GhIQDZfVFTw1YtXbU0C8l9jA5AdVMYGpDNYwWZFMCAYp4IEDI6oIqBbGQFigagiAHd3MCA6uYBCj8qQizuAwB1YsxGrzwLdwAAAABJRU5ErkJggg==',
			'624A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WAMYQxgaHVqRxUSmsLYytDpMdUASC2gRaQSKBAQgizUAdQY6OogguS8yatXSlZmZWdOQ3BcyhWEKayNcHURvK0MAa2hgaAiKGKMDA5o6oFsa0MVYA0RDHdDEBir8qAixuA8ACurMrYQPwPAAAAAASUVORK5CYII=',
			'B4AB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7QgMYWhmmMIY6IIkFTGGYyhDK6BCALNYKFHF0dBBBUcfoytoQCFMHdlJo1NKlS1dFhmYhuS9gikgrkjqoeaKhrqGBqOa1MoDVodrBgKEX5GagGIqbByr8qAixuA8AJSXNODOqMRcAAAAASUVORK5CYII=',
			'75AB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7QkNFQxmmMIY6IIu2ijQwhDI6BKCJMTo6Ooggi00RCWFtCISpg7gpaurSpasiQ7OQ3MfowNDoilAHhqwNQLHQQBTzRBpEwOqQxQIaWFtZ0fQGNDCC7EV18wCFHxUhFvcBAJG/zA/3Q5N8AAAAAElFTkSuQmCC',
			'01D3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7GB0YAlhDGUIdkMRYAxgDWBsdHQKQxESmsAawNgQ0iCCJBbQygMUCkNwXtRSCspDch6YORUwExQ5MMdYABgy3MDqwhqK7eaDCj4oQi/sAlWXK8m1K2L8AAAAASUVORK5CYII=',
			'85A4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nM2QsRGAIAxFQ5EN4j5Y2Ic7aDJNLNggjkDDlFICWuppfvfuX/IuUC+j8Ke84oe8JDBQ7hgZKSTYe8aZ1K0+T72IysadX5GjlCoinR8Z7JsGP+5rLIUUxxutx5MLZpwYsosz++p/D+bG7wR28c8dn4NXZwAAAABJRU5ErkJggg==',
			'1D10' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7GB1EQximMLQii7E6iLQyhDBMdUASE3UQaXQMYQgIQNEr0ugwBUgiuW9l1jQIQnIfmjoCYhh2tALdh+qWENEQxlAHFDcPVPhREWJxHwDVEsmmaC/1TAAAAABJRU5ErkJggg==',
			'DE99' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7QgNEQxlCGaY6IIkFTBFpYHR0CAhAFmsVaWBtCHQQwS0GdlLU0qlhKzOjosKQ3AdSxxASMBVdL5BsQBdjbAhAtQOLW7C5eaDCj4oQi/sAXsTNLnpwFXcAAAAASUVORK5CYII=',
			'9A00' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WAMYAhimMLQii4lMYQxhCGWY6oAkFtDK2sro6BAQgCIm0ujaEOggguS+aVOnrUxdFZk1Dcl9rK4o6iCwVTQUXUwAaJ4jmh0iU0QaHdDcwhoAFENz80CFHxUhFvcBADSzzG+Nn52GAAAAAElFTkSuQmCC',
			'F600' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7QkMZQximMLQiiwU0sLYyhDJMdUARE2lkdHQICEAVa2BtCHQQQXJfaNS0sKWrIrOmIbkvoEG0FUkd3DxXLGKOGHZgcwummwcq/KgIsbgPAEGpzSshBvfEAAAAAElFTkSuQmCC',
			'95C9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nM2QMQ6AMAhFcWCvCd6nS3cG6+BpcOAGrTdw6Sk1TrQ6apS/vXzCC1AuI/CnvOKHPESIPnvDKJF0npkNYyVB6T3VbMSjScZvzXnbSpkn44cBliCQ7S7oycQyp3QwV92ghNq6IHdj6/zV/x7Mjd8OnfXLuwmcw5oAAAAASUVORK5CYII=',
			'704B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7QkMZAhgaHUMdkEVbGUMYWh0dAlDEWFsZpjo6iCCLTRFpdAiEq4O4KWrayszMzNAsJPcxOog0ujaimsfaABQLDUQxT6QBaEcjqh0BDUC3oOkFsjHdPEDhR0WIxX0AcCLLtY1Q6K0AAAAASUVORK5CYII=',
			'EDA5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7QkNEQximMIYGIIkFNIi0MoQyOjCgijU6OjpiiLk2BLo6ILkvNGraytRVkVFRSO6DqAOS6HpDsYg1BDqgibWyNgQEILsP5Gag2FSHQRB+VIRY3AcAbNnOYg4DwmoAAAAASUVORK5CYII=',
			'D263' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7QgMYQxhCGUIdkMQCprC2Mjo6OgQgi7WKNLo2ODSIoIgxAMWANJL7opauWrp06qqlWUjuA6qbwuro0IBqHkMAK1AE1TxGBwyxKawN6G4JDRANdUBz80CFHxUhFvcBAPNZzmAmf90EAAAAAElFTkSuQmCC',
			'AA5F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7GB0YAlhDHUNDkMRYAxhDWEEySGIiU1hb0cUCWkUaXafCxcBOilo6bWVqZmZoFpL7QOocGgJR9IaGioaii4HNwyLm6OiIIeYQiuqWgQo/KkIs7gMA5DDKzt/z+ykAAAAASUVORK5CYII=',
			'3A4E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7RAMYAhgaHUMDkMQCpjCGMLQ6OqCobGVtZZiKJjZFpNEhEC4GdtLKqGkrMzMzQ7OQ3QdU59qIbp5oqGtoIJoY0Dw0dQFTMMVEA8BiKG4eqPCjIsTiPgD66cuEGTzcNQAAAABJRU5ErkJggg==',
			'CB8A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7WENEQxhCGVqRxURaRVoZHR2mOiCJBTSKNLo2BAQEIIs1gNQ5OogguS9q1dSwVaErs6YhuQ9NHUwMaF5gaAiGHYEo6iBuQdULcTMjithAhR8VIRb3AQAnuMwLoKRlRgAAAABJRU5ErkJggg==',
			'2F65' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nM2QsQ2AMAwE7SIbmH2cgt4Upsg0btiAsAEFTElC5QhKkOLvTv/SyXA+zqCn/OIXZFBWVHGMVjKMkX1PFrJgLYOb4cjeb8vzno+UvJ+UXmQjt0WuW2lYsMom9oysurB4P9XSUMjcwf8+zIvfBTmsysrEdM0vAAAAAElFTkSuQmCC',
			'9D35' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7WANEQxhDGUMDkMREpoi0sjY6OiCrC2gVaXRoCMQUa3R0dUBy37Sp01ZmTV0ZFYXkPlZXkDqHBhFkm8HmBaCICUDtEMFwi0MAsvsgbmaY6jAIwo+KEIv7ACZEzRDQrjvKAAAAAElFTkSuQmCC',
			'39E2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7RAMYQ1hDHaY6IIkFTGFtZW1gCAhAVtkq0ujawOgggiw2BSTG0CCC5L6VUUuXpoauWhWF7L4pjIFAdY0OKOYxgPS2orimlQUkNoUBi1sw3ewYGjIIwo+KEIv7ANWgy9I9Km84AAAAAElFTkSuQmCC',
			'9E47' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7WANEQxkaHUNDkMREpog0MLQ6NIggiQW0AnlTsYgFOjQEILlv2tSpYSszs1ZmIbmP1VWkgbXRoRXFZqBe1tCAKchiAiDzGh0CGNDd0ujogMXNKGIDFX5UhFjcBwBbsMwE/hy5IAAAAABJRU5ErkJggg=='        
        );
        $this->text = array_rand( $images );
        return $images[ $this->text ] ;    
    }
    
    function out_processing_gif(){
        $image = dirname(__FILE__) . '/processing.gif';
        $base64_image = "R0lGODlhFAAUALMIAPh2AP+TMsZiALlcAKNOAOp4ANVqAP+PFv///wAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFCgAIACwAAAAAFAAUAAAEUxDJSau9iBDMtebTMEjehgTBJYqkiaLWOlZvGs8WDO6UIPCHw8TnAwWDEuKPcxQml0Ynj2cwYACAS7VqwWItWyuiUJB4s2AxmWxGg9bl6YQtl0cAACH5BAUKAAgALAEAAQASABIAAAROEMkpx6A4W5upENUmEQT2feFIltMJYivbvhnZ3Z1h4FMQIDodz+cL7nDEn5CH8DGZhcLtcMBEoxkqlXKVIgAAibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkphaA4W5upMdUmDQP2feFIltMJYivbvhnZ3V1R4BNBIDodz+cL7nDEn5CH8DGZAMAtEMBEoxkqlXKVIg4HibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpjaE4W5tpKdUmCQL2feFIltMJYivbvhnZ3R0A4NMwIDodz+cL7nDEn5CH8DGZh8ONQMBEoxkqlXKVIgIBibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpS6E4W5spANUmGQb2feFIltMJYivbvhnZ3d1x4JMgIDodz+cL7nDEn5CH8DGZgcBtMMBEoxkqlXKVIggEibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpAaA4W5vpOdUmFQX2feFIltMJYivbvhnZ3V0Q4JNhIDodz+cL7nDEn5CH8DGZBMJNIMBEoxkqlXKVIgYDibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpz6E4W5tpCNUmAQD2feFIltMJYivbvhnZ3R1B4FNRIDodz+cL7nDEn5CH8DGZg8HNYMBEoxkqlXKVIgQCibbK9YLBYvLtHH5K0J0IACH5BAkKAAgALAEAAQASABIAAAROEMkpQ6A4W5spIdUmHQf2feFIltMJYivbvhnZ3d0w4BMAIDodz+cL7nDEn5CH8DGZAsGtUMBEoxkqlXKVIgwGibbK9YLBYvLtHH5K0J0IADs=";
        $binary = is_file($image) ? join("",file($image)) : base64_decode($base64_image); 
        header("Cache-Control: post-check=0, pre-check=0, max-age=0, no-store, no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: image/gif");
        echo $binary;
    }

}
# end of class phpfmgImage
# ------------------------------------------------------
# end of module : captcha


# module user
# ------------------------------------------------------
function phpfmg_user_isLogin(){
    return ( isset($_SESSION['authenticated']) && true === $_SESSION['authenticated'] );
}


function phpfmg_user_logout(){
    session_destroy();
    header("Location: admin.php");
}

function phpfmg_user_login()
{
    if( phpfmg_user_isLogin() ){
        return true ;
    };
    
    $sErr = "" ;
    if( 'Y' == $_POST['formmail_submit'] ){
        if(
            defined( 'PHPFMG_USER' ) && strtolower(PHPFMG_USER) == strtolower($_POST['Username']) &&
            defined( 'PHPFMG_PW' )   && strtolower(PHPFMG_PW) == strtolower($_POST['Password']) 
        ){
             $_SESSION['authenticated'] = true ;
             return true ;
             
        }else{
            $sErr = 'Login failed. Please try again.';
        }
    };
    
    // show login form 
    phpfmg_admin_header();
?>
<form name="frmFormMail" action="" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:380px;height:260px;">
<fieldset style="padding:18px;" >
<table cellspacing='3' cellpadding='3' border='0' >
	<tr>
		<td class="form_field" valign='top' align='right'>Email :</td>
		<td class="form_text">
            <input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" class='text_box' >
		</td>
	</tr>

	<tr>
		<td class="form_field" valign='top' align='right'>Password :</td>
		<td class="form_text">
            <input type="password" name="Password"  value="" class='text_box'>
		</td>
	</tr>

	<tr><td colspan=3 align='center'>
        <input type='submit' value='Login'><br><br>
        <?php if( $sErr ) echo "<span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
        <a href="admin.php?mod=mail&func=request_password">I forgot my password</a>   
    </td></tr>
</table>
</fieldset>
</div>
<script type="text/javascript">
    document.frmFormMail.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();
}


function phpfmg_mail_request_password(){
    $sErr = '';
    if( $_POST['formmail_submit'] == 'Y' ){
        if( strtoupper(trim($_POST['Username'])) == strtoupper(trim(PHPFMG_USER)) ){
            phpfmg_mail_password();
            exit;
        }else{
            $sErr = "Failed to verify your email.";
        };
    };
    
    $n1 = strpos(PHPFMG_USER,'@');
    $n2 = strrpos(PHPFMG_USER,'.');
    $email = substr(PHPFMG_USER,0,1) . str_repeat('*',$n1-1) . 
            '@' . substr(PHPFMG_USER,$n1+1,1) . str_repeat('*',$n2-$n1-2) . 
            '.' . substr(PHPFMG_USER,$n2+1,1) . str_repeat('*',strlen(PHPFMG_USER)-$n2-2) ;


    phpfmg_admin_header("Request Password of Email Form Admin Panel");
?>
<form name="frmRequestPassword" action="admin.php?mod=mail&func=request_password" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:580px;height:260px;text-align:left;">
<fieldset style="padding:18px;" >
<legend>Request Password</legend>
Enter Email Address <b><?php echo strtoupper($email) ;?></b>:<br />
<input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" style="width:380px;">
<input type='submit' value='Verify'><br>
The password will be sent to this email address. 
<?php if( $sErr ) echo "<br /><br /><span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
</fieldset>
</div>
<script type="text/javascript">
    document.frmRequestPassword.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();    
}


function phpfmg_mail_password(){
    phpfmg_admin_header();
    if( defined( 'PHPFMG_USER' ) && defined( 'PHPFMG_PW' ) ){
        $body = "Here is the password for your form admin panel:\n\nUsername: " . PHPFMG_USER . "\nPassword: " . PHPFMG_PW . "\n\n" ;
        if( 'html' == PHPFMG_MAIL_TYPE )
            $body = nl2br($body);
        mailAttachments( PHPFMG_USER, "Password for Your Form Admin Panel", $body, PHPFMG_USER, 'You', "You <" . PHPFMG_USER . ">" );
        echo "<center>Your password has been sent.<br><br><a href='admin.php'>Click here to login again</a></center>";
    };   
    phpfmg_admin_footer();
}


function phpfmg_writable_check(){
 
    if( is_writable( dirname(PHPFMG_SAVE_FILE) ) && is_writable( dirname(PHPFMG_EMAILS_LOGFILE) )  ){
        return ;
    };
?>
<style type="text/css">
    .fmg_warning{
        background-color: #F4F6E5;
        border: 1px dashed #ff0000;
        padding: 16px;
        color : black;
        margin: 10px;
        line-height: 180%;
        width:80%;
    }
    
    .fmg_warning_title{
        font-weight: bold;
    }

</style>
<br><br>
<div class="fmg_warning">
    <div class="fmg_warning_title">Your form data or email traffic log is NOT saving.</div>
    The form data (<?php echo PHPFMG_SAVE_FILE ?>) and email traffic log (<?php echo PHPFMG_EMAILS_LOGFILE?>) will be created aumotically when the form is submitted. 
    However, the script doesn't have writable permission to create those files. In order to save your valuable information, please set the directory to writable.
     If you don't know how to do it, please ask for help from your web Administrator or Technical Support of your hosting company.   
</div>
<br><br>
<?php
}


function phpfmg_log_view(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    
    phpfmg_admin_header();
   
    $file = $files[$n];
    if( is_file($file) ){
        if( 1== $n ){
            echo "<pre>\n";
            echo join("",file($file) );
            echo "</pre>\n";
        }else{
            $man = new phpfmgDataManager();
            $man->displayRecords();
        };
     

    }else{
        echo "<b>No form data found.</b>";
    };
    phpfmg_admin_footer();
}


function phpfmg_log_download(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );

    $file = $files[$n];
    if( is_file($file) ){
        phpfmg_util_download( $file, PHPFMG_SAVE_FILE == $file ? 'form-data.csv' : 'email-traffics.txt', true, 1 ); // skip the first line
    }else{
        phpfmg_admin_header();
        echo "<b>No email traffic log found.</b>";
        phpfmg_admin_footer();
    };

}


function phpfmg_log_delete(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    phpfmg_admin_header();

    $file = $files[$n];
    if( is_file($file) ){
        echo unlink($file) ? "It has been deleted!" : "Failed to delete!" ;
    };
    phpfmg_admin_footer();
}


function phpfmg_util_download($file, $filename='', $toCSV = false, $skipN = 0 ){
    if (!is_file($file)) return false ;

    set_time_limit(0);


    $buffer = "";
    $i = 0 ;
    $fp = @fopen($file, 'rb');
    while( !feof($fp)) { 
        $i ++ ;
        $line = fgets($fp);
        if($i > $skipN){ // skip lines
            if( $toCSV ){ 
              $line = str_replace( chr(0x09), ',', $line );
              $buffer .= phpfmg_data2record( $line, false );
            }else{
                $buffer .= $line;
            };
        }; 
    }; 
    fclose ($fp);
  

    
    /*
        If the Content-Length is NOT THE SAME SIZE as the real conent output, Windows+IIS might be hung!!
    */
    $len = strlen($buffer);
    $filename = basename( '' == $filename ? $file : $filename );
    $file_extension = strtolower(substr(strrchr($filename,"."),1));

    switch( $file_extension ) {
        case "pdf": $ctype="application/pdf"; break;
        case "exe": $ctype="application/octet-stream"; break;
        case "zip": $ctype="application/zip"; break;
        case "doc": $ctype="application/msword"; break;
        case "xls": $ctype="application/vnd.ms-excel"; break;
        case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
        case "gif": $ctype="image/gif"; break;
        case "png": $ctype="image/png"; break;
        case "jpeg":
        case "jpg": $ctype="image/jpg"; break;
        case "mp3": $ctype="audio/mpeg"; break;
        case "wav": $ctype="audio/x-wav"; break;
        case "mpeg":
        case "mpg":
        case "mpe": $ctype="video/mpeg"; break;
        case "mov": $ctype="video/quicktime"; break;
        case "avi": $ctype="video/x-msvideo"; break;
        //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
        case "php":
        case "htm":
        case "html": 
                $ctype="text/plain"; break;
        default: 
            $ctype="application/x-download";
    }
                                            

    //Begin writing headers
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public"); 
    header("Content-Description: File Transfer");
    //Use the switch-generated Content-Type
    header("Content-Type: $ctype");
    //Force the download
    header("Content-Disposition: attachment; filename=".$filename.";" );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".$len);
    
    while (@ob_end_clean()); // no output buffering !
    flush();
    echo $buffer ;
    
    return true;
 
    
}
?>