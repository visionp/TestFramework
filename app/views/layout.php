<!DOCTYPE html>
<html>
<head>
    <title>TestApp</title>
    <meta charset="utf-8">
    <link href="<?= $this->getBaseUrl() ?>/web/css/main.css" rel="stylesheet">
    <link href="<?= $this->getBaseUrl() ?>/web/css/table.css" rel="stylesheet">
    <?= $this->renderJs($this->getKeyPosition(1)) ?>
</head>
<body>
<?= $this->getContent() ?>
<script src="<?= $this->getBaseUrl() ?>/web/js/jquery.js"></script>
<script src="https://rawgit.com/centrifugal/centrifuge-js/master/centrifuge.js"></script>
<script src="<?= $this->getBaseUrl() ?>/web/js/orders.js"></script>
<?= $this->renderJs($this->getKeyPosition(0)) ?>
</body>
</html>