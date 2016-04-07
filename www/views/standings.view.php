<h1 class="page-header">STANDINGS</h1>

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

<h5 style="width: 500px">
    <span class="table-field-left">Pos</span>
    <span class="table-field-center">Username</span>
    <span class="table-field-right">Points</span>
</h5>
<br />

<?php $position = 0; ?>
<div>
    <ul>
        <?php foreach ($data['standings'] as $row): ?>
            <?php $position++; ?>
            <li>
                <span class="table-field-left"><?= $position; ?></span>
                <span class="table-field-center"><?= $row['email']; ?></span>
                <span class="table-field-right"><?= $row['points']; ?></span>
            </li>
            <br />
            <br />
        <?php endforeach; ?>
    </ul>
</div>

<?php if ( !$data['standings'] ): ?>
    No data to display.
<?php endif; ?>
