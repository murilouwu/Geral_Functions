<?php
class Connect
{
    public $db;
    public $host;
    public $user;
    public $pass;

    function makeConnection($db, $host, $user, $pass)
    {   
        $dns = "mysql:host=".$host."; dbname=".$db;
        return new PDO($dns, $user, $pass, [
            PDO::ATTR_ERRMODE, 
            PDO::ERRMODE_EXCEPTION
        ]);
    }
}

try{
    $connect = new Connect();
    $pdo = $connect->makeConnection("NameBd", "localhost", "root", "");
} catch(PDOException $e){
    echo 'Error: '.$e->getMessage();
}

	final class HtmlBased
    {
        function HeaderEcho($Title, $assets, $itemPlus) {
            $res = '
                <!DOCTYPE html>          
                <html lang="pt-br">
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
        
        function foot(){
            $res = '
                </html>
            ';
            echo($res);
        }
        
        function mensage($txt){
            echo '<script>alert("'.$txt.'");</script>';
        }

        function Atalho($pag) {
            header("Location: $pag");
            exit();
        }              
    }

    final class BankUse
    {
        public $NameTable;
        public $Dates;

        function InsertUser($pdo, $Vls, $Vers, $Configs)
        {
            $VersToText = '';
            foreach ($Vers as $key => $ver) {
                if ($key === 0) {
                    $VersToText .= $this->Dates[$ver].'= :'.$this->Dates[$ver];
                } else {
                    $VersToText .= ' '. $Configs[0][$key - 1].' '.$this->Dates[$ver].'= :'.$this->Dates[$ver];
                }
            }

            $sql = "SELECT * FROM ".$this->NameTable." WHERE ".$VersToText;
            $stmt = $pdo->prepare($sql);
            foreach($Vers as $key => $ver){
                $dateParam = ':'.$this->Dates[$ver];
                $stmt->bindParam($dateParam, $Vls[$ver]);
            }
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return "cadastrado já existe, >:(";
            }else{
                try {
                    $DtsToText = implode(', ', $this->Dates);
                    $VlsToText = '';
                    foreach($Vls as $key => $vl){
                        $valueParam = ':'.$this->Dates[$key];

                        if($key === (count($Vls) - 1)){
                            $VlsToText .= $valueParam;
                        }else{
                            $VlsToText .= $valueParam.', ';
                        }
                    }

                    $sql = "INSERT INTO ".$this->NameTable." (".$DtsToText.") VALUES (".$VlsToText.")";
                    $stmt = $pdo->prepare($sql);
                    foreach($Vls as $key => $vl){
                        $dateParam = ':'.$this->Dates[$key];
                        $stmt->bindParam($dateParam, $Vls[$key]);
                    }
                    if ($stmt->execute()) {
                        return 'cadastrado com sucesso :)';
                    } else {
                        throw new Exception('Erro ao cadastrar ;-;');
                    }
                } catch (PDOException $e) {
                    return "Error: " . $e->getMessage();
                }
            }
        }

        function GetUser($pdo, $DateRequire, $Where){
            if($DateRequire == ""){
                $DateRequire = "*";
            }
            try{
                $sql = "SELECT ".$DateRequire." FROM ".$this->NameTable." WHERE ".$Where.";";
                $stmt = $pdo->prepare($sql);
                if($stmt->execute()){
                    return $stmt;       
                }else{
                    throw new Exception('Erro');
                }
            } catch(PDOException $e){
                return 'Error: '.$e->getMessage();
            }
        }
    }
?>
