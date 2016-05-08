<h1 class="page-header">STANDINGS</h1>

<div class="panel panel-default">
    <div class="panel-heading"><span class="glyphicon glyphicon-filter"></span> Filter bar</div>
    <div class="panel-body">
        <form action="" method="GET">
            <input type="hidden" name="page" value="<?= $this->page ?>">
            <div class="row">
                <div class="form-group col-sm-12">
                    <label>Tournament</label>
                    <select name="tournament_id" class="form-control" width="">
                        <?php foreach ($tournaments as $tournament): ?>
                            <option <?= $tournament->selected; ?> value="<?= $tournament->id; ?>"><?= $tournament->name; ?></option>
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


<h4><?= $tournament->name; ?></h4>

<div class="panel panel-default">
    <div class="panel-heading"><span class="glyphicon glyphicon-list-alt"></span> Results</div
    <div class="panel-body">
        <?php $position = 0; ?>
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
            <?php foreach ($standings as $score): ?>
                <?php $position++; ?>
                <tr>
                    <td><?= $position; ?></td>
                    <td><?= $score->email; ?></td>
                    <td><a href="index.php?page=history&username=<?= htmlspecialchars($score->email); ?>&tournament_id=<?= htmlspecialchars($score->tournamentId); ?>">View</a></td>
                    <td><?= $score->points; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (!$standings): ?>
            <p>No data to display.</p>
        <?php endif; ?>
    </div>
</div>