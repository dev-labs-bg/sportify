<h1 class="page-header">HISTORY</h1>

<div class="panel panel-default">
    <div class="panel-heading"><span class="glyphicon glyphicon-filter"></span>Filter bar</div>
    <div class="panel-body">
        <form action="" method="GET">
            <input type="hidden" name="page" value="<?= $page ?>">
            <div class="row">
                <div class="form-group col-sm-3">
                    <label>Username</label>
                    <select name="username" class="form-control" width="">
                        <?php foreach ($data['usernames'] as $row): ?>
                            <option <?= $row['selected']; ?> value="<?= htmlspecialchars($row['email']); ?>"><?= $row['email']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-sm-3">
                    <label>Tournament</label>
                    <select name="tournament_id" class="form-control" width="">
                        <option value="ALL">All joined</option>
                        <?php foreach ($data['tournaments'] as $row): ?>
                            <option <?= $row['selected']; ?> value="<?= $row['id']; ?>"><?= $row['name']; ?></option>
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
            <?php foreach ($data['matches'] as $row): ?>
                <tr class="<?= $GLOBALS['base_config']['points_css_class'][$row['points']] ?>" >
                    <td><?=  $row['home_team'] . ' - ' . $row['away_team']?></td>
                    <td><?= $row['datetime'] ?></td>
                    <td><?= $row['p_home_goals'] . ' : ' . $row['p_away_goals'] ?></td>
                    <td><?= $row['m_home_goals'] . ' : ' . $row['m_away_goals'] ?></td>
                    <td><?= $row['points'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php if ( !$data['matches'] ): ?>
            <p>No matches to display.</p>
        <?php endif; ?>
    </div>
</div>