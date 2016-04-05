<?php
/**
 * @var $this app\views\View
 * @var $model app\models\StadiumModel
 */

?>

<table id="green-sector" class="green-sector">
    <thead>
    <tr>
        <th>Кол-во свободных мест <span class="free_places"></span></th>
        <th>Кол-во в прцессе бронирования <span class="reserve_places"></span></th>
        <th colspan="2">Кол-во забронированных мест <span class="close_places"></span></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($model->green_sector as $row => $rows): ?>
        <tr>
        <?php foreach($rows as $k => $r): ?>
            <td data-row="<?= $row ?>" data-place="<?= $k ?>"><?= $k ?></td>
        <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<table id="yellow-sector" class="green-sector yellow-sector">
    <thead>
    <tr>
        <th>Кол-во свободных мест <span class="free_places"></span></th>
        <th>Кол-во забронированных мест<span class="reserve_places"></span></th>
        <th  colspan="2">Кол-во в прцессе бронирования <span class="close_places"></span></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($model->yellow_sector as $row): ?>
        <tr>
            <?php foreach($row as $k => $r): ?>
                <td><?= $k ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
