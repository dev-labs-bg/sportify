<h2>MATCHES</h2>

<?php //var_dump($data)?>
    Filter tournaments:
    <form action="index.php?page=matches" method="POST">
        <select name="tournament_id">
            <option value="ALL">All joined</option>
            <?php foreach ($data['tournaments'] as $row): ?>
                <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Select</button>
    </form>
    <br />
    <?php foreach ($data['matches'] as $row): ?>
        <div>
            <form action="index.php?page=matches" method="POST">
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
                                <button type="submit">EDIT</button>
                            <?php endif; ?>
                        </li>
                </ul>
            </form>
        </div>
        <br />
    <?php endforeach; ?>
    <?php if ( !$data['matches'] ): ?>
        No matches to display.
    <?php endif; ?>
