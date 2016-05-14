<h1 class="page-header">MATCHES</h1>

<div class="panel panel-default">
    <div class="panel-heading"><span class="glyphicon glyphicon-filter"></span>Filter bar</div>
    <div class="panel-body">
        <form action="" method="GET">
            <input type="hidden" name="page" value="<?= $this->page ?>">
            <div class="row">
                <div class="form-group col-sm-4">
                    <label>Tournament</label>
                    <select name="tournament_id" class="form-control" width="">
                        <option value="ALL">All joined</option>
                        <?php foreach ($tournaments_joined as $tournament): ?>
                            <option <?= $tournament->selected; ?> value="<?= $tournament->id; ?>"><?= $tournament->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <label>Date from</label>
                    <br />
                    <input type="date" name="date_from" class="form-control" value="<?= ( isset($_GET['date_from']) && !empty($_GET['date_from']) ) ? $_GET['date_from'] : date("Y-m-d"); ?>">
                </div>
                <div class="form-group col-sm-4">
                    <label>Date to</label>
                    <br />
                    <input type="date" name="date_to" class="form-control" value="<?= ( isset($_GET['date_to']) && !empty($_GET['date_to']) ) ? $_GET['date_to'] : ''; ?>">
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
    <div class="panel-heading">
        <span class="glyphicon glyphicon-list-alt"></span> Matches
        <button type="submit" id="btn-bet-all" class="btn btn-success center-block">BET / UPDATE ALL</button>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table id="matches" class="table table-striped">
                <thead>
                    <tr>
                        <th>Home Team - Away Team</th>
                        <th>Start</th>
                        <th>Status</th>
                        <th>Prediction</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($matches as $match): ?>
                    <tr>
                        <td>
                            <?php echo $match->homeTeam; ?> - <?php echo $match->awayTeam; ?>
                        </td>
                        <td>
                            <?php echo $match->datetime; ?>
                        </td>
                        <td>
                            <?= ($match->disabled === 'disabled') ? 'Match locked. Already started' : ''; ?>
                            <span class="msg-error">
                                <?=
                                (isset($match_id, $status_value) && !$status_value && $match_id == $match->id)
                                    ? $status_message
                                    : '';
                                ?>
                            </span>
                        </td>
                        <?php $prediction = $predictions[$match->id]; ?>
                        <td>
                            <form action="" method="POST">
                                <input type="hidden" name="match_id" value="<?= $match->id; ?>">
                                <?php if ($prediction->homeGoals == null && $prediction->awayGoals == null): ?>
                                    <input <?= $match->disabled; ?> type="text" name="home_goals" placeholder="home" value="" size="5"> -
                                    <input <?= $match->disabled; ?> type="text" name="away_goals" placeholder="away" value="" size="5">
                                <?php else: ?>
                                    <input <?= $match->disabled; ?> type="text" name="home_goals" placeholder="home"  value="<?= $prediction->homeGoals; ?>" size="5"> -
                                    <input <?= $match->disabled; ?> type="text" name="away_goals" placeholder="away" value="<?= $prediction->awayGoals; ?>" size="5">
                                <?php endif; ?>

                                <?php if ($prediction->homeGoals == null && $prediction->awayGoals == null): ?>
                                    <button <?= $match->disabled; ?> type="submit" class="btn btn-success pull-right">BET</button>
                                <?php else: ?>
                                    <button <?= $match->disabled; ?> type="submit" class="btn btn-warning pull-right">UPDATE BET</button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if (!$matches): ?>
            <p>No matches to display.</p>
        <?php endif; ?>
    </div>
</div>