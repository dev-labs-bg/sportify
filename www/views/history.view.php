<h2>HISTORY</h2>

<?php //var_dump($_GET);?>
<div class="filter">
    <form action="" method="GET">
        <input type="hidden" name="page" value="<?= $page ?>">
        <div class="filter-field">
            Tournaments:
            <br />
            <select name="tournament_id" width="">
                <option value="ALL">All joined</option>
                <?php foreach ($data['tournaments'] as $row): ?>
                    <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-field">
            Date from:
            <br />
            <input type="date" name="date_from" value="<?= ( isset($_GET['date_from']) && !empty($_GET['date_from']) ) ? $_GET['date_from'] : "2016-03-31"; ?>">
        </div>
        <div class="filter-field">
            Date to:
            <br />
            <input type="date" name="date_to" value="<?= ( isset($_GET['date_to']) && !empty($_GET['date_to']) ) ? $_GET['date_to'] : ""; ?>">
        </div>
        <br />
        <br />
        <br />
        <button type="submit">Filter</button>
    </form>
</div>
<br />
<?php foreach ($data['matches'] as $row): ?>
    <div class="history-<?= $row['points']; ?>">
        <ul>
            <li><?= $row['datetime'] . ' ' . $row['home_team'] . ' - ' . $row['away_team'] ?></li>
            <li>
                Your prediction:
                <input disabled type="text" name="home_goals" value="<?= $row['p_home_goals'] ?>" size="5">
                <input disabled type="text" name="away_goals" value="<?= $row['p_away_goals'] ?>" size="5">
            </li>
            <li>
                <span class="history-field-1">
                    Final score: <?= $row['m_home_goals'] ?> : <?= $row['m_away_goals'] ?>
                </span>
                <span class="history-field-2">
                    Points gained: <?= $row['points'] ?>
                </span>
            </li>
        </ul>
    </div>
    <br />
<?php endforeach; ?>

<?php if ( !$data['matches'] ): ?>
    No matches to display.
<?php endif; ?>
