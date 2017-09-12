<?php

require_once 'Database.php';

$db = new Database();

$items = $db->getItems();

if(!empty($_POST['add'])){
    $db->addItem($_POST['name']);
}

if(isset($_GET['as'], $_GET['item'])) {
    if($_GET['as'] == 'done'){
        $db->markItem($_GET['item']);
    }
    if($_GET['as'] == 'delete'){
        $db->delItem($_GET['item']);
    }
}


?>


<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>ToDo List</title>
	<link rel="stylesheet" href="css/main.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

	<meta name="vierport" content="width=device-width,user-scalable=no, initial-scale=1.0, minimum-scale=1.0">

</head>
<body>
	<div class="list">
		<h1 class="header">To do.</h1>

		<?php if(!empty($items)): ?>

		<ul class="items">
			<?php foreach ($items as $item): ?>
				<li>
					<span class="item<?php echo $item['done'] ? ' done' : ''; ?>">
						<?= $item['name'];?>
					</span>
					<?php if(!$item['done']): ?>
						<a href="index.php?as=done&item=<?php echo $item['id']; ?>" class="done-button">Выполнено</a>
					<?php endif; ?>
					<a href="index.php?as=delete&item=<?php echo $item['id']; ?>" class="delete-button">Удалить</a>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php else: ?>
			<p>Вы еще не добавили какое-либо задание!</p>
		<?php endif; ?>

		<form class="item-add" method="POST">
			<input type="text" name="name" placeholder="Что тебе нужно сделать?" class="input">
			<input type="submit" name="add" value="Добавить" class="submit">
		</form>
	</div>
</body>
</html>