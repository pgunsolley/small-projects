<?php
// Demonstrate converting large SQL data into CSV file using Iterators/Generators

require_once 'constants.php';

function create_csv_from_dbiterator(string $path, Iterator $db_result): void {
    $headerCreated = false;
    $file = fopen($path, 'w');
    foreach ($db_result as $result) {
        if (!$headerCreated) {
            $headerCreated = true;
            $result = array_keys($result);
        }
        fputcsv($file, $result);
    }
    fclose($file);
}

// TODO: Create a custom type for this.
function query_db(PDO $db, string $query): Iterator {
    $stmt = $db->prepare($query);
    $stmt->execute();
    while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
        yield $r;
    }
}

// Main entry
function main(): void {
    create_csv_from_dbiterator(
        "users.csv",
        query_db(
            new PDO(DB_CONN, DB_USER, DB_PASS), 
            GET_USERS_QUERY
        )
    );
}
main();