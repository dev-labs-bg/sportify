<h2>MATCHES</h2>

<?php var_dump($_SERVER['REQUEST_URI']);?>
    <div>
        <div class="filter">
            Filter tournaments:
            <form action="<?= $_SERVER['REQUEST_URI']; ?>" method="GET">
                <input type="hidden" name="page" value="matches">
                <select name="tournament_id" width="50">
                    <option value="ALL">All joined</option>
                    <?php foreach ($data['tournaments'] as $row): ?>
                        <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Select</button>
            </form>
        </div>
        <div class="filter">
            <form action="<?= $_SERVER['REQUEST_URI']; ?>" method="GET">
                <input type="hidden" name="page" value="matches">
                Date from:
                <input type="date" name="date_from">
                Date to:
                <input type="date" name="date_to">
                <button type="submit">Select</button>
            </form>
        </div>
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
                                <button type="submit">EDIT BET</button>
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
