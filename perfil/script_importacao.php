<?php
define('SERVER', "localhost");
define('USER', "root", true);
define('PASS', "", true);

class DbController
{
    public static $conn;

    protected function connection($banco)
    {
        if ($banco == "ig") {
            $db = "igsis";
        } elseif ($banco == "sis") {
            $db =  "siscontrat";
        } elseif ($banco == "cep") {
            $db =  "cep";
        }
        $sgbd = "mysql:host=".SERVER.";dbname=".$db;
        if (!isset(self::$conn)) {
            self::$conn = new PDO($sgbd, USER, PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$conn;
    }

    public function killConn(){
        if (isset(self::$conn)) {
            self::$conn = null;
        }
    }

    public function sqlSimples($consulta, $db) {
        $pdo = self::connection($db);
        $statement = $pdo->prepare($consulta);
        $statement->execute();

        return $statement;
    }
}



$dbObj = new DbController();

/*
 * ids de PF da EMIA
 * (160,404,727,735,766,770,789,801,837,891,962,974,994,1002,1006,1145,1157,1361,1363,1364,2247,2264,2765,3115,3116,3117,3118,3119,3120,3121,3122,3123,3124,3125,3126,3127,3128,3129,3130,3131,3132,3133,3134,3135,3136,3137,3138,3139,3140,3141,3142,3143,3146,3343,4240,5473,5745,6938,8734,8826,9689,9910,10008)
 * */

/* Inserir Pessoa Física do IGSIS que não existe no SisContrat */
$dbObj->killConn();
$verificaPf = $dbObj->sqlSimples("
    SELECT igsis_pf.Id_PessoaFisica as ig_idPf, sis_pf.id as sis_idPf, igsis_pf.Nome, igsis_pf.NomeArtistico, igsis_pf.CPF, igsis_pf.RG, igsis_pf.DataNascimento, igsis_pf.Email, igsis_pf.DataAtualizacao
        FROM sis_pessoa_fisica AS igsis_pf
        LEFT outer JOIN siscontrat.pessoa_fisicas AS sis_pf ON sis_pf.cpf = igsis_pf.CPF
        WHERE sis_pf.id IS NULL
    ", "ig")->fetchAll(PDO::FETCH_OBJ);
foreach ($verificaPf as $dado){
    $dado->NomeArtistico = addslashes($dado->NomeArtistico);

    $dbObj->killConn();
    $insert = $dbObj->sqlSimples("
        INSERT IGNORE INTO pessoa_fisicas(nome, nome_artistico, cpf, rg, data_nascimento, email, ultima_atualizacao, nacionalidade_id) 
        VALUES ('{$dado->Nome}','{$dado->NomeArtistico}', '{$dado->CPF}', '{$dado->RG}', '{$dado->DataNascimento}', '{$dado->Email}', '{$dado->DataAtualizacao}', 1)
    ","sis");
}
/* ./Inserir Pessoa Física do IGSIS que não existe no SisContrat */


// Pausa o script por 10 segundos:
sleep(10);

/* Verifica as Pessoas Físicas que existem IGSIS e no SisContrat */
$dbObj->killConn();
$pfComumSisIg = $dbObj->sqlSimples("
    SELECT igsis_pf.Id_PessoaFisica as ig_idPf, sis_pf.id as sis_idPf
    FROM sis_pessoa_fisica AS igsis_pf
    INNER JOIN siscontrat.pessoa_fisicas AS sis_pf ON sis_pf.cpf = igsis_pf.CPF
    WHERE igsis_pf.Id_PessoaFisica <= 1000",
    "ig")->fetchAll(PDO::FETCH_OBJ);
/* ./Verifica as Pessoas Físicas que existem IGSIS e no SisContrat */

foreach ($pfComumSisIg as $pfs){
    /* Telefone */
    $dbObj->killConn();
    $verificaTelefone = $dbObj->sqlSimples("
        SELECT Id_PessoaFisica, CPF, Telefone1, Telefone2, Telefone3 FROM sis_pessoa_fisica AS igsis_pf 
        WHERE Id_PessoaFisica = {$pfs->ig_idPf}
    ", "ig")->fetchAll(PDO::FETCH_OBJ);

    $dbObj->killConn();
    foreach ($verificaTelefone as $dado){
        if ($dado->Telefone1 != ""){
            $insert = $dbObj->sqlSimples("
            INSERT IGNORE INTO pf_telefones (pessoa_fisica_id, telefone, publicado) 
            VALUES ('{$pfs->sis_idPf}', '{$dado->Telefone1}', 1)","sis");
        }
        if ($dado->Telefone2 != ""){
            $insert = $dbObj->sqlSimples("
            INSERT IGNORE INTO pf_telefones (pessoa_fisica_id, telefone, publicado) 
            VALUES ('{$pfs->sis_idPf}', '{$dado->Telefone2}', 1)","sis");
        }
        if ($dado->Telefone3 != ""){
            $insert = $dbObj->sqlSimples("
            INSERT IGNORE INTO pf_telefones (pessoa_fisica_id, telefone, publicado) 
            VALUES ('{$pfs->sis_idPf}', '{$dado->Telefone3}', 1)","sis");
        }
    }
    /* ./Telefone */

    /* Endereço */
    $erroCep = [];

    $dbObj->killConn();
    $verificaCEP = $dbObj->sqlSimples("
        SELECT Id_PessoaFisica, CPF, igsis_pf.CEP, igsis_pf.Numero, igsis_pf.Complemento FROM sis_pessoa_fisica AS igsis_pf 
        WHERE igsis_pf.CEP != '' AND Id_PessoaFisica = {$pfs->ig_idPf}
    ", "ig")->fetchAll(PDO::FETCH_OBJ);

    foreach ($verificaCEP as $dado) {
        $cep = $dado->CEP;
        $cep = str_replace("-", "", $cep);

        $endereco = "https://viacep.com.br/ws/" . $cep . "/json/";

        $viacep = file_get_contents($endereco);
        $json = json_decode($viacep);

        //var_dump($json);

        if (isset($json->erro)) {
            array_push($erroCep,  $cep);
            var_dump($erroCep);
            //$erroCep[]['idPf'] = $pfs->ig_idPf;
        } else {
            $logradouro = addslashes($json->logradouro);
            $numero = $dado->Numero;
            $complemento = $dado->Complemento;
            $bairro = addslashes($json->bairro);
            $cidade = addslashes($json->localidade);
            $uf = $json->uf;
            $cep = $json->cep;
            $dbObj->killConn();

            $insert = $dbObj->sqlSimples("
                INSERT IGNORE INTO pf_enderecos (pessoa_fisica_id, logradouro, numero, complemento, bairro, cidade, uf, cep) 
                VALUES ('{$pfs->sis_idPf}', '$logradouro', '$numero', '$complemento', '$bairro', '$cidade', '$uf', '$cep')","sis");
        }
    }
    /* ./Endereço */

    /* DRT */
    $dbObj->killConn();
    $verificaDRT = $dbObj->sqlSimples("
        SELECT Id_PessoaFisica, CPF, DRT FROM sis_pessoa_fisica AS igsis_pf 
        WHERE DRT != '' AND Id_PessoaFisica = {$pfs->ig_idPf}
    ", "ig")->fetchAll(PDO::FETCH_OBJ);

    $dbObj->killConn();
    foreach ($verificaDRT as $dado){
        $insert = $dbObj->sqlSimples("
            INSERT IGNORE INTO drts (pessoa_fisica_id, drt, publicado) 
            VALUES ('{$pfs->sis_idPf}', '{$dado->DRT}', 1)","sis");
    }
    /* ./DRT */

    /* INSS */
    $dbObj->killConn();
    $verificaNIT = $dbObj->sqlSimples("
        SELECT Id_PessoaFisica, CPF, igsis_pf.InscricaoINSS FROM sis_pessoa_fisica AS igsis_pf 
        WHERE igsis_pf.InscricaoINSS != '' AND Id_PessoaFisica = {$pfs->ig_idPf}
    ", "ig")->fetchAll(PDO::FETCH_OBJ);

    $dbObj->killConn();
    foreach ($verificaNIT as $dado){
        $insert = $dbObj->sqlSimples("
            INSERT IGNORE INTO nits (pessoa_fisica_id, nit, publicado) 
            VALUES ('{$pfs->sis_idPf}', '{$dado->InscricaoINSS}', 1) ","sis");
    }
    /* ./INSS */

    /* OMB */
    $dbObj->killConn();
    $verificaOMB = $dbObj->sqlSimples("
        SELECT Id_PessoaFisica, CPF, OMB FROM sis_pessoa_fisica AS igsis_pf 
        WHERE OMB != '' AND Id_PessoaFisica = {$pfs->ig_idPf}
    ", "ig")->fetchAll(PDO::FETCH_OBJ);

    $dbObj->killConn();
    foreach ($verificaOMB as $dado){
        $insert = $dbObj->sqlSimples("
            INSERT IGNORE INTO ombs (pessoa_fisica_id, omb, publicado) 
            VALUES ('{$pfs->sis_idPf}', '{$dado->OMB}', 1) ","sis");
    }
    /* ./OMB */

    /* BANCO */
    $dbObj->killConn();
    $verificaBanco = $dbObj->sqlSimples("
        SELECT Id_PessoaFisica, CPF, codBanco, agencia, conta FROM sis_pessoa_fisica AS igsis_pf 
        WHERE (codBanco IS NOT NULL AND codBanco != '0' AND agencia !='') AND Id_PessoaFisica = {$pfs->ig_idPf}
    ", "ig")->fetchAll(PDO::FETCH_OBJ);

    $dbObj->killConn();
    foreach ($verificaBanco as $dado){
        $insert = $dbObj->sqlSimples("
            INSERT IGNORE INTO pf_bancos (pessoa_fisica_id, banco_id, agencia, conta, publicado) 
            VALUES ('{$pfs->sis_idPf}', '{$dado->codBanco}', '{$dado->agencia}', '{$dado->conta}', 1)","sis");
    }
    /* ./BANCO */
}