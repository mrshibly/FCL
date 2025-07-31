<?php
require_once '../src/database.php';
require_once '../src/functions.php';

$seasons = getSeasons();

?>

<div class="container">
    <h2>Seasons</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($seasons as $season): ?>
                <tr>
                    <td><a href="index.php?page=season_details&id=<?php echo $season['id']; ?>"><?php echo htmlspecialchars($season['name']); ?></a></td>
                    <td><?php echo htmlspecialchars($season['start_date']); ?></td>
                    <td><?php echo htmlspecialchars($season['end_date']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
