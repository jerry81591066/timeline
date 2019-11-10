<?php


if (! isset($_GET['order'])) {
    if (count($_GET) === 0)
        header('Location: ' . $_SERVER['REQUEST_URI'] . '?order=asc');
    else
        header('Location: ' . $_SERVER['REQUEST_URI'] . '&order=asc');
    exit;
}

require('db.php');

$db = new Sqlite();

$data = $db->getData($_GET['order']);

?>

<!DOCTYPE html>
<html>
<head>
  <title>Timeline</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" type="text/css" href="main.css"/>
  <script type="text/javascript" src="js/timer.js"></script>
</head>

<div class="timeline">

  <div class="entry">
    <div class="title">
      <h3>Days in counting</h3>
    </div>
    <div class="body" style="margin-bottom: 10px">
      <p><span id="days" style="font-size: larger"></span></p>
    </div>
  </div>

<?php
foreach ($data as $item) { 
    $date = $item['date'];
    $description = $item['description'] ?? null;
    $title = $item['title'];
    $content = preg_replace('/[\x0a]/', '<br>', $item['content']);
    $photos = $item['photos'] ?? null;
    $tag = $item['tag'];

    echo <<<EOF
  <div class="entry">
    <div class="title">
      <h3><a name="$tag">$date</a></h3>
EOF;

    if (! is_null($description)) {
        echo <<<EOF
      <p>$description</p>
EOF;
    }
    echo <<<EOF
      <p></p>
    </div>
    <div class="body">
      <p>$title</p>
      $content<br>

EOF;

    if (! is_null($photos)) {
        $photos = json_decode($photos);
        foreach ($photos as $photo) {
            if (sizeof($photo) === 1)
                echo "<img src=\"$photo[0]\">";
            elseif (sizeof($photo) === 2) {
                echo <<<EOF
      <div class="photos">
        <img class="two" src="$photo[0]">
        <img class="two" src="$photo[1]">
      </div>  
EOF;
            }
            elseif (sizeof($photo) === 3) {
                echo <<<EOF
      <div class="photos">
        <img class="three" src="$photo[0]">
        <img class="three" src="$photo[1]">
        <img class="three" src="$photo[2]">
      </div>  
EOF;
            }
        }
    }

    echo <<<EOF
    </div>
  </div>

EOF;
}
?>

</div>
</html>
