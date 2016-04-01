<h2>MATCHES</h2>

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
                <input type="date" name="date_from" value="<?= ( isset($_GET['date_from']) && !empty($_GET['date_from']) ) ? $_GET['date_from'] : date("Y-m-d"); ?>">
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
        <div>
            <form action="" method="POST">
                <ul>
                    <li><input type="hidden" name="match_id" value="<?= $row['match_id'] ?>"></li>
                        <li><?= $row['datetime'] . ' ' . $row['home_team'] . ' - ' . $row['away_team'] ?></li>
                        <li>
                            <?php if ($row['p_home_goals'] == null && $row['p_away_goals'] == null): ?>
                                <input <?= $row['disabled'] ?> type="text" name="home_goals" value="" size="5">
                                <input <?= $row['disabled'] ?> type="text" name="away_goals" value="" size="5">
                                <button type="submit">BET</button>
                            <?php else: ?>
                                <input <?= $row['disabled'] ?> type="text" name="home_goals" value="<?= $row['p_home_goals'] ?>" size="5">
                                <input <?= $row['disabled'] ?> type="text" name="away_goals" value="<?= $row['p_away_goals'] ?>" size="5">
                                <button type="submit">UPDATE BET</button>
                            <?php endif; ?>
                            <span class="msg-error">
                                <?=
                                ( isset($data['match_id'], $data['prediction_value']) && !$data['prediction_value'] && $data['match_id'] == $row['match_id'] )
                                    ? $data['prediction_status']
                                    : "";
                                ?>
                            </span>
                        </li>
                </ul>
            </form>
        </div>
        <br />
    <?php endforeach; ?>

    <?php if ( !$data['matches'] ): ?>
        No matches to display.
    <?php endif; ?>
