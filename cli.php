<?php

if (! isset($argv[1])) exit;

$dir = dirname(__FILE__);

require($dir . '/db.php');

$db = new Sqlite();

switch ($argv[1]) {
case 'build':
    $sql = 'CREATE TABLE main (' .
        'date TEXT, ' .
        'description TEXT, ' .
        'title TEXT NOT NULL, ' .
        'content TEXT NOT NULL, ' .
        'photos TEXT, ' .
        'position TEXT NOT NULL DEFAULT normal,' .
        'created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL)';

    $stmt = $db->pdo->prepare($sql);
    $stmt->execute();

    $status = $stmt->errorInfo();
    if ($status[0] !== '00000')
        echo json_encode($status, JSON_PRETTY_PRINT);

    break;

case 'dump':
    $sql = "SELECT * FROM main";
    $stmt = $db->pdo->prepare($sql);
    $stmt->execute();
    while ($data = $stmt->fetch())
        printf("%s - %s\n%s\n%s\n%s\n%s\n=====\n", $data['date'], $data['description'], $data['title'], $data['content'], $data['photos'], $data['created_at']);

    break;


case 'import':
    $data = json_decode(file_get_contents($argv[2]), true);

    foreach ($data as $item) {
        $sql = 'INSERT INTO main(' .
            join(', ', array_keys($item)) .
            ') VALUES (:' .
            join(', :', array_keys($item)) .
            ')';

        $stmt = $db->pdo->prepare($sql);
        foreach ($item as $key => $value) {
            if (is_array($value))
                $value = json_encode($value);
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();

        $status = $stmt->errorInfo();
        if ($status[0] !== '00000')
            echo json_encode($status, JSON_PRETTY_PRINT);
    }

    break;

case 'export':
    $sql = "SELECT * FROM main ORDER BY created_at ASC";
    $stmt = $db->pdo->prepare($sql);
    $stmt->execute();
    $result = [];
    while ($data = $stmt->fetch(PDO::FETCH_ASSOC))
        $result[] = $data;

    echo json_encode($result, JSON_PRETTY_PRINT);

    break;

default:
	echo "Unknown argument: {$argv[1]}";
}

?>
