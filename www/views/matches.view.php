<h1 class="page-header">MATCHES</h1>

<div class="panel panel-default">
    <div class="panel-heading"><span class="glyphicon glyphicon-filter"></span>Filter bar</div>
    <div class="panel-body">
        <form action="" method="GET">
            <input type="hidden" name="page" value="<?= $page ?>">
            <div class="row">
                <div class="form-group col-sm-4">
                    <label>Tournaments</label>
                    <select name="tournament_id" class="form-control" width="">
                        <option value="ALL">All joined</option>
                        <?php foreach ($data['tournaments'] as $row): ?>
                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
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

<div class="row">
    <div class="col-sm-2"><label>Name</label></div>
    <div class="col-sm-2"><label>Start</label></div>
    <div class="col-sm-2"><label>Home</label></div>
    <div class="col-sm-2"><label>Away</label></div>
    <div class="col-sm-2"><label>Status</label></div>
    <div class="col-sm-2"><label>Action</label></div>
</div>
<?php foreach ($data['matches'] as $row): ?>
    <form action="" method="POST">
        <div class="row">

                <input type="hidden" name="match_id" value="<?= $row['match_id'] ?>">
                <div class="form-group col-sm-2"><?php echo $row['home_team'] ?> - <?php echo $row['away_team'] ?></div>
                <div class="form-group col-sm-2"><?php echo $row['datetime'] ?></div>
                <div class="form-group col-sm-2">
                    <?php if ($row['p_home_goals'] == null && $row['p_away_goals'] == null): ?>
                        <input <?= $row['disabled'] ?> type="text" name="home_goals" value="" size="5">
                    <?php else: ?>
                        <input <?= $row['disabled'] ?> type="text" name="home_goals" value="<?= $row['p_home_goals'] ?>" size="5">
                    <?php endif; ?>
                </div>
                <div class="form-group col-sm-2">
                    <?php if ($row['p_home_goals'] == null && $row['p_away_goals'] == null): ?>
                        <input <?= $row['disabled'] ?> type="text" name="away_goals" value="" size="5">
                    <?php else: ?>
                        <input <?= $row['disabled'] ?> type="text" name="away_goals" value="<?= $row['p_away_goals'] ?>" size="5">
                    <?php endif; ?>
                </div>
                <div class="form-group col-sm-2">
                    <?= ($row['disabled'] == "disabled") ? "Match locked. Already started" : ""; ?>
                    <span class="msg-error">
                        <?=
                        ( isset($data['match_id'], $data['prediction_value']) && !$data['prediction_value'] && $data['match_id'] == $row['match_id'] )
                            ? $data['prediction_status']
                            : "";
                        ?>
                        </span>
                </div>


                <div class="form-group col-sm-2">
                    <?php if ($row['p_home_goals'] == null && $row['p_away_goals'] == null): ?>
                        <button <?= $row['disabled'] ?> type="submit" class="btn btn-success">BET</button>
                    <?php else: ?>
                        <button <?= $row['disabled'] ?> type="submit" class="btn btn-success">UPDATE BET</button>
                    <?php endif; ?>
                </div>
        </div>
 </form>
<?php endforeach; ?>

<?php if ( !$data['matches'] ): ?>
    <p>No matches to display.</p>
<?php endif; ?>
