<?php
    /*
    print_r($_GET); // To print properly an array, you either loop through it and
                    // echo each element, or you can use print_r
    */

    $db = new SQLite3("scores_db.sqlite", SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $db->query("CREATE TABLE IF NOT EXISTS scores (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                test_id INTEGER,
                correct BOOLEAN,
                response_time INTEGER)");

    $ground_truth = array("diff", "diff", "same");
    $rt = array();
    $response = array();
    
    /* Single prepared statement with a single parameter binding can be used to 
     * insert multiple rows with different values.
     */
    $statement = $db->prepare("INSERT INTO scores (test_id, correct, response_time)
                    VALUES (:tid, :c, :rt)");
    $statement->bindParam(":tid", $i);
    $statement->bindParam(":c", $c);
    $statement->bindParam(":rt", $rt);

    foreach (range(1, count($_GET) / 2) as $i) {
        $c = (int)($_GET['t' . $i . "_response"] === $ground_truth[$i - 1]);
        $rt = $_GET['t' . $i . "_rt"];
        $result = $statement->execute();
    }
    $result->finalize();

    // average accuracy and response time of the current experiment.
    $avg_current = $db->querySingle("SELECT avg(correct), avg(response_time) FROM (
                                     SELECT * FROM scores ORDER BY id DESC LIMIT 3)",
                                    true);
    // average accuracy and response time of all the experiments in history.
    $avg_all = $db->querySingle("SELECT avg(correct), avg(response_time) FROM scores",
                                true);

    echo("本輪實驗的正確率: " . $avg_current["avg(correct)"] * 100 . '(%) ;');
    echo nl2br("\n");
    echo("以及平均反應時間: " . $avg_current["avg(response_time)"] . "(ms) .");
    echo nl2br("\n");
    echo nl2br("\n");
    echo("過去所有實驗(資料庫中所有紀錄)的平均正確率: " . $avg_all["avg(correct)"] * 100 . '(%) ;');
    echo nl2br("\n");
    echo("以及平均反應時間: " . $avg_all["avg(response_time)"] . "(ms) .");
    echo nl2br("\n");
    echo nl2br("\n");

    $db->close();
?>

<form action="index.html">
    <input type="submit" value="Click to Play Again" />
</form>
