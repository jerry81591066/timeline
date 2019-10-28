<?php

class Sqlite {
    public $pdo;

    public function __construct() {
        $dir = dirname(__FILE__);
        $this->pdo = new PDO('sqlite:' . $dir . '/sqlite.db');
    }

    public function getData(string $order = 'ASC') {

        $result = [];
        foreach ($this->getHeaderData() as $data)
            $result[] = $data;


        if (! in_array(strtoupper($order), ['ASC', 'DESC']))
            $order = 'ASC';

        $sql = "SELECT * FROM main WHERE position = 'normal' ORDER BY date " . $order;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC))
            $result[] = $data;

        foreach ($this->getFooterData() as $data)
            $result[] = $data;

        return $result;
    }

    public function getHeaderData()
    {
        $sql = "SELECT * FROM main WHERE position = 'header' ORDER BY created_at";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC))
            $result[] = $data;

        return $result;
    }

    public function getFooterData()
    {
        $sql = "SELECT * FROM main WHERE position = 'footer' ORDER BY created_at";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC))
            $result[]  =$data;

        return $result;
    }
}

?>
