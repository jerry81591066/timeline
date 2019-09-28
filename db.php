<?php

class Sqlite {
    public $pdo;

    public function __construct() {
        $dir = dirname(__FILE__);
        $this->pdo = new PDO('sqlite:' . $dir . '/sqlite.db');
    }

    public function getData(string $order = 'ASC') {
        if (! in_array(strtoupper($order), ['ASC', 'DESC']))
            $order = 'ASC';

        $sql = "SELECT * FROM main ORDER BY date " . $order;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC))
            $result[] = $data;

        return $result;
    }
}

?>
