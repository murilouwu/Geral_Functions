<?php
$db = array(
    'host'=>'hostname',
    'user'=>'username',
    'pass'=> 'senha',
    'nm'=>'nm_database'
);

$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['nm']) or die ('Sem Conecção ao database');

function InsertBd($Infs, $Dts, $Vls, $Vers, $Configs, $Up){
    /*
        $Infs = array('nm_table', );
        $Dts = array('date1', 'date2', ...);
        $Vls = array('insert_value1','insert_value2'...);
        $Vers = array(0, 1, 2..); key from var Dts
        $Configs = array(array('OR ou AND 0', 'OR ou AND 1'...), );
        $Up = array($file0, $file1...);
    */
    $VersToText = '';
    for ($i=0; $i<count($Vers); $i++){
        if($i==0){
            $VersToText = $VersToText.$Dts[$Vers[$i]].'= '.$Vls[$Vers[$i]];
        }else{
            $VersToText = $VersToText.' '.$Configs[0][($i-1)].' '.$Dts[$Vers[$i]].'= '.$Vls[$Vers[$i]];
        }
    }
    $query = 'SELECT * FROM '.$Infs[0].' WHERE '.$VersToText;
    $res = $GLOBALS['conn']->query($query);
    $rows = mysqli_num_rows($res);

    if($rows > 0){
        mensage($Configs['ERROR'][0]);
    }else if($rows == 0){
        if(count($Up) > 0){
            upFileinPage($Up);
        }else{
            $DtsToText = '';
            for ($i=0; $i<count($Dts); $i++){
                if($i==0){
                    $DtsToText = $DtsToText.$Dts[$i];
                }else{
                    $DtsToText = $DtsToText.', '.$Dts[$i];
                }
            }
            $VlsToText = '';
            for ($i=0; $i<count($Vls); $i++){
                if($i==0){
                    $VlsToText = $VlsToText.$Vls[$i];
                }else{
                    $VlsToText = $VlsToText.', '.$Vls[$i];
                }
            }

            $query = 'INSERT INTO '.$Infs[0].' ( '.$DtsToText.' ) VALUES ( '.$VlsToText.' )';
            $res = $GLOBALS['conn']->query($query);
            if($res){
                return GetBd($Infs, $Dts, $Vls);
            }else{
                mensage($Configs['ERROR'][1]);
            }
        }
    }
}

function HeaderEcho($Title, $assets, $itemPlus) {
		$res = '
			<!DOCTYPE html>
			<html>
			<head>
		';
	
		if (is_array($assets)) {
			foreach ($assets as $asset) {
				$type = $asset[0];
				$link = $asset[1];
				$extra = isset($asset[2]) ? $asset[2] : null;
	
				if ($type == 0) {
					$res .= '<meta ' . $link . '>';
				} elseif ($type == 1) {
					$res .= '<link rel="stylesheet" type="text/css" href="' . $link . '"';
					if ($extra !== null) {
						$res .= ' ' . $extra;
					}
					$res .= '>';
				} elseif ($type == 2) {
					$res .= '<script src="' . $link . '"';
					if ($extra !== null) {
						$res .= ' ' . $extra;
					}
					$res .= '></script>';
				}
			}
		}
	
		$res .= '
				<link rel="shortcut icon" href="'.$itemPlus.'">
				<title>'.$Title.'</title>
			</head>
		';
	
		echo $res;
	}

function mensage($txt){
    echo '<script>alert("'.$txt.'");</script>';
}

function footEcho(){
    $res = '
        </html>
    ';
    echo($res);
}
?>
