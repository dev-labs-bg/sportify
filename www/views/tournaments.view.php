<h2>TOURNAMENTS</h2>

<p>You are currently enrolled in these tournaments:</p>

<?php //var_dump($data)?>
<div>
    <?php if ( $data['enrolled'] ): ?>
    <ul>
        <li><input type="hidden" name="form_name" value="tournament_enroll"></li>
        <?php foreach ($data['enrolled'] as $row): ?>
            <li><input type="radio" name="tournament" value="<?= $row['id'] ?>"><?= $row['name'] ?></li>
        <?php endforeach; ?>
    </ul>
    <?php else: ?>
        You have not enrolled in any tournament.
    <?php endif; ?>
</div>

<br />

<p>Please select new tournament to enroll:</p>
<?php //var_dump($data)?>
<div>
    <?php if ( $data['not_enrolled'] ): ?>
    <form action="index.php?page=tournaments" method="POST">
        <ul>
            <li><input type="hidden" name="form_name" value="tournament_enroll"></li>
            <?php foreach ($data['not_enrolled'] as $row): ?>
                <li><input type="radio" name="tournament" value="<?= $row['id'] ?>"><?= $row['name'] ?></li>
            <?php endforeach; ?>
            <li><button type="submit">Enroll</button></li>
        </ul>
    </form>
    <?php else: ?>
        You have no new tournaments to enroll in.
    <?php endif; ?>
</div>