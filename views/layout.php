<!DOCTYPE html>
<html>
<head>
    <title>TestApp</title>
    <meta charset="utf-8">
    <link href="<?= $this->getBaseUrl() ?>/web/css/main.css" rel="stylesheet">
</head>
<body>
<ul class="menu">
    <li><a href="<?= $this->getBaseUrl() ?>/index.php/index/index">Главная</a></li>
    <li><a href="<?= $this->getBaseUrl() ?>/index.php/index/list">Список</a></li>
    <li><a href="<?= $this->getBaseUrl() ?>/index.php/index/history">История</a></li>
</ul>
<?= $this->getContent() ?>
<script src="<?= $this->getBaseUrl() ?>/web/js/jquery.js"></script>
<script src="<?= $this->getBaseUrl() ?>/web/js/form.js"></script>
</body>
</html>