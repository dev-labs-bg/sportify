<h1 class="page-header">HISTORY</h1>

<div class="panel panel-default">
    <div class="panel-heading"><span class="glyphicon glyphicon-filter"></span>Filter bar</div>
    <div class="panel-body">
        <form action="" method="GET">
            <input type="hidden" name="page" value="<?= $this->page; ?>">
            <div class="row">
                <div class="form-group col-sm-3">
                    <label>Username</label>
                    <select name="username" class="form-control" width="">
                        <?php foreach ($users as $user): ?>
                            <option <?= $user->selected; ?> value="<?= htmlspecialchars($user->email); ?>"><?= $user->email; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-sm-3">
                    <label>Tournament</label>
                    <select name="tournament_id" class="form-control" width="">
                        <option value="ALL">All joined</option>
                        <?php foreach ($tournaments_joined as $tournament): ?>
                            <option <?= $tournament->selected; ?> value="<?= $tournament->id; ?>"><?= $tournament->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-sm-3">
                    <label>Date from</label>
                    <input type="date" name="date_from" class="form-control" value="<?= ( isset($_GET['date_from']) && !empty($_GET['date_from']) ) ? $_GET['date_from'] : "2016-03-31"; ?>">
                </div>
                <div class="form-group col-sm-3">
                    <label>Date to</label>
                    <input type="date" name="date_to" class="form-control" value="<?= ( isset($_GET['date_to']) && !empty($_GET['date_to']) ) ? $_GET['date_to'] : ""; ?>">
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

<div class="panel panel-default">
    <div class="panel-heading"><span class="glyphicon glyphicon-list-alt"></span> Results</div>
    <div class="panel-body">
        <table class="table">
            <thead>
            <tr>
                <th>Home Team - Away Team</th>
                <th>Start</th>
                <th>Prediction</th>
                <th>Result</th>
                <th>Points gained</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($matches as $match): ?>
                <?php $prediction = $predictions[$match->id]; ?>
                <tr class="<?= $GLOBALS['base_config']['points_css_class'][$prediction->points]; ?>" >
                    <td><?=  $match->homeTeam . ' - ' . $match->awayTeam; ?></td>
                    <td><?= $match->datetime; ?></td>
                    <td><?= $prediction->homeGoals . ' : ' . $prediction->awayGoals; ?></td>
                    <td><?= $match->homeGoals . ' : ' . $match->awayGoals; ?></td>
                    <td><?= $prediction->points; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (!$matches): ?>
            <p>No matches to display.</p>
        <?php endif; ?>
    </div>
</div>