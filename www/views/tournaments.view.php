<h2>TOURNAMENTS</h2>

<p>You have currently joined these tournaments:</p>

<?php //var_dump($data)?>
<div>
    <?php if ( $data['joined'] ): ?>
    <form action="index.php?page=tournaments" method="POST">
    <ul>
        <li><input type="hidden" name="form_name" value="tournaments_leave"></li>
        <?php foreach ($data['joined'] as $row): ?>
            <li><input type="checkbox" name="tournaments[]" value="<?= $row['id'] ?>"><?= $row['name'] ?></li>
        <?php endforeach; ?>
        <li><button type="submit">Leave</button></li>
    </ul>
    </form>
    <?php else: ?>
        You have not joined any tournament yet.
    <?php endif; ?>
</div>

<br />

<p>Please select tournament to join:</p>
<?php //var_dump($data)?>
<div>
    <?php if ( $data['available'] ): ?>
    <form action="index.php?page=tournaments" method="POST">
        <ul>
            <li><input type="hidden" name="form_name" value="tournaments_join"></li>
            <?php foreach ($data['available'] as $row): ?>
                <li><input type="checkbox" name="tournaments[]" value="<?= $row['id'] ?>"><?= $row['name'] ?></li>
            <?php endforeach; ?>
            <li><button type="submit">Join</button></li>
        </ul>
    </form>
    <?php else: ?>
        You have no new tournaments to join.
    <?php endif; ?>
</div>