<h2>MATCHES</h2>

<?php //var_dump($data)?>
    <?php if ( $data['matches'] ): ?>
        <?php foreach ($data['matches'] as $row): ?>
            <div>
                <form action="index.php?page=matches" method="POST">
                    <ul>
                        <li><input type="hidden" name="match_id" value="<?= $row['match_id'] ?>"></li>
                            <li><?= $row['datetime'] . ' --- ' . $row['home_team'] . ' - ' . $row['away_team'] ?></li>
                            <li>
                                <?php if ($row['p_home_goals'] == null && $row['p_away_goals'] == null): ?>
                                    <input <?= $row['disabled'] ?> type="text" name="home_goals" value="">
                                    <input <?= $row['disabled'] ?> type="text" name="away_goals" value="">
                                    <button type="submit">BET</button>
                                <?php else: ?>
                                    <input <?= $row['disabled'] ?> type="text" name="home_goals" value="<?= $row['p_home_goals'] ?>">
                                    <input <?= $row['disabled'] ?> type="text" name="away_goals" value="<?= $row['p_away_goals'] ?>">
                                    <button type="submit">EDIT</button>
                                <?php endif; ?>
                            </li>
                    </ul>
                </form>
            </div>
            <br />
        <?php endforeach; ?>
    <?php else: ?>
        No matches to display.
    <?php endif; ?>
