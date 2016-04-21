<h1 class="page-header">STANDINGS</h1>

<div class="panel panel-default">
    <div class="panel-heading"><span class="glyphicon glyphicon-filter"></span> Filter bar</div>
    <div class="panel-body">
        <form action="" method="GET">
            <input type="hidden" name="page" value="<?= $page ?>">
            <div class="row">
                <div class="form-group col-sm-12">
                    <label>Tournament</label>
                    <select name="tournament_id" class="form-control" width="">
                        <?php foreach ($data['tournaments'] as $row): ?>
                            <option <?= $row['selected']; ?> value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary center-block">Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>


<h4><?= $data['tournament_name']; ?></h4>

<div class="panel panel-default">
    <div class="panel-heading"><span class="glyphicon glyphicon-list-alt"></span> Results</div
    <div class="panel-body">
        <?php $position = 0; ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Position</th>
                        <th>Username</th>
                        <th>History</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($data['standings'] as $row): ?>
                    <?php $position++; ?>
                    <tr>
                        <td><?= $position; ?></td>
                        <td><?= $row['email']; ?></td>
                        <td><a href="index.php?page=history&username=<?= htmlspecialchars($row['email']); ?>&tournament_id=<?= htmlspecialchars($row['tournament_id']); ?>">View</a></td>
                        <td><?= $row['points']; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if ( !$data['standings'] ): ?>
            <p>No data to display.</p>
        <?php endif; ?>
    </div>
</div>