<h2>MATCHES</h2>

<?php //var_dump($data)?>
    <?php if ( $data['matches'] ): ?>
        <?php foreach ($data['matches'] as $row): ?>
            <div>
                <form action="index.php?page=matches" method="POST">
                    <ul>
                        <li><input type="hidden" name="form_name" value="<?= $row['id'] ?>"></li>
                            <li><?= $row['datetime'] . '   ' . $row['home_team'] . ' - ' . $row['away_team'] ?></li>
                            <li>
                                <input type="text" name="home_goals">
                                <input type="text" name="away_goals">
                                <button type="submit">BET</button>
                            </li>
                    </ul>
                </form>
            </div>
            <br />
        <?php endforeach; ?>
    <?php else: ?>
        No matches to display.
    <?php endif; ?>
