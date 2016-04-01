<h2>STANDINGS</h2>

<?php //var_dump($data);?>
<div class="filter">
    <form action="" method="GET">
        <input type="hidden" name="page" value="<?= $page ?>">
        <div class="filter-field">
            Tournaments:
            <br />
            <select name="tournament_id" width="">
<!--                <option value="ALL">All joined</option>-->
                <?php foreach ($data['tournaments'] as $row): ?>
                    <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <br />
        <br />
        <br />
        <button type="submit">Filter</button>
    </form>
</div>
<br />

<h4>Viewing: <?= $data['tournament_name']; ?></h4>

Pos --- Username --- Points
<?php $position = 0; ?>
<div>
    <?php foreach ($data['standings'] as $row): ?>
        <?php $position++; ?>

            <ul>
                <li><?= $position . ' --- ' . $row['email'] . ' --- ' . $row['points']; ?></li>
            </ul>
        <br />
    <?php endforeach; ?>
</div>

<?php if ( !$data['standings'] ): ?>
    No data to display.
<?php endif; ?>
