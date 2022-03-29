<?php

class CEP extends Database {
    private $CEP;

    public function setCEP($CEP){
        $this->CEP = $CEP;
    }

    public function getCEP(){
        return $this->CEP;
    }

    public function checkAPIError(){
        $url = 'https://viacep.com.br/ws/' . $this->getCEP() . '/xml/';

        $data = file_get_contents($url);

        $xml = simplexml_load_string($data, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json, true);

        if(isset($array['erro'])){
            return true;
        } else {
            return false;
        }
    }

    public function checkCEP($cep){
        $db = $this->connect();
        $sql = $db->prepare('SELECT cep_raw FROM cep_details WHERE cep_raw = ?');
        $sql->bindValue(1, $cep, PDO::PARAM_INT);
        $sql->execute();

        if($sql->rowCount() == 1){
            return true;
        } else {
            return false;
        }
    }

    public function registerCEP(){
        $url = 'https://viacep.com.br/ws/' . $this->getCEP() . '/xml/';

        $data = file_get_contents($url);

        $xml = simplexml_load_string($data, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json, true);

        $db = $this->connect();
        $sql = $db->prepare("INSERT INTO cep_details(cep, logradouro, bairro, localidade, uf, ddd, cep_raw) VALUES(?, ?, ?, ?, ?, ?, ?)");
        $sql->bindValue(1, $array['cep']);
        $sql->bindValue(2, $array['logradouro']);
        $sql->bindValue(3, $array['bairro']);
        $sql->bindValue(4, $array['localidade']);
        $sql->bindValue(5, $array['uf']);
        $sql->bindValue(6, $array['ddd']);
        $sql->bindValue(7, $this->getCEP());
        $sql->execute();

        return $this->getCEPInfo();
    }

    public function getCEPInfo(){
        $db = $this->connect();
        $sql = $db->prepare('SELECT cep, logradouro, bairro, localidade, uf, ddd, cep_raw FROM cep_details WHERE cep_raw = ?');
        $sql->bindValue(1, $this->getCEP(), PDO::PARAM_INT);
        $sql->execute();

        return $sql;
    }
}