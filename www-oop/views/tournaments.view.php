<h1 class="page-header">TOURNAMENTS</h1>

<div class="panel panel-default">
    <div class="panel-heading"><span class="glyphicon glyphicon-filter"></span> Joined</div>
    <div class="panel-body">
        <?php if ($tournaments_joined): ?>
            <form action="" method="POST">
                <input type="hidden" name="form_name" value="tournaments_leave">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Leave</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tournaments_joined as $tournament): ?>
                                <tr>
                                    <td><?= $tournament->name; ?></td>
                                    <td><?= $tournament->startDate; ?></td>
                                    <td><?= $tournament->endDate; ?></td>
                                    <td><input type="checkbox" name="tournaments[]" value="<?= $tournament->id; ?>"></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-warning pull-right">Leave</button>
            </form>
        <?php else: ?>
            <p>You have not joined any tournament yet.</p>
        <?php endif; ?>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><span class="glyphicon glyphicon-list-alt"></span> Available</div>
    <div class="panel-body">
        <?php if ($tournaments_available): ?>
            <form action="" method="POST">
                <input type="hidden" name="form_name" value="tournaments_join">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Join</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($tournaments_available as $tournament): ?>
                            <tr>
                                <td><?= $tournament->name; ?></td>
                                <td><?= $tournament->startDate; ?></td>
                                <td><?= $tournament->endDate; ?></td>
                                <td><input type="checkbox" name="tournaments[]" value="<?= $tournament->id; ?>"></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-success pull-right">Join</button>
            </form>
        <?php else: ?>
            <p>You have no new tournaments to join.</p>
        <?php endif; ?>
    </div>
</div>