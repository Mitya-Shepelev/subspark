<?php
// Admin-only migration runner for safe Envato updates
include_once __DIR__ . '/../includes/inc.php';
if ($logedIn !== '1' || $userType !== '2') { http_response_code(403); exit('Forbidden'); }

header('Content-Type: text/plain; charset=utf-8');
echo "Starting migrations...\n";

function add_unique_if_missing(string $table, string $index, string $defSql): void {
    $rows = DB::all("SHOW INDEX FROM `$table` WHERE Key_name = ?", [$index]);
    if (empty($rows)) {
        echo "Adding index $index on $table...\n";
        DB::exec($defSql);
    } else {
        echo "Index $index already exists on $table.\n";
    }
}

// 1) i_sessions.session_key -> VARCHAR(96) and unique index
$row = DB::one("SHOW COLUMNS FROM i_sessions LIKE 'session_key'");
if ($row) {
    if (stripos($row['Type'], 'varchar') === false) {
        echo "Altering i_sessions.session_key to VARCHAR(96)...\n";
        DB::exec("ALTER TABLE i_sessions MODIFY session_key VARCHAR(96) NULL");
    } else {
        echo "i_sessions.session_key already VARCHAR.\n";
    }
}
add_unique_if_missing('i_sessions', 'uniq_session_key', 'CREATE UNIQUE INDEX uniq_session_key ON i_sessions (session_key)');

// 2) i_users unique indices (username/email)
add_unique_if_missing('i_users', 'uniq_username', 'CREATE UNIQUE INDEX uniq_username ON i_users (i_username)');
add_unique_if_missing('i_users', 'uniq_email', 'CREATE UNIQUE INDEX uniq_email ON i_users (i_user_email)');

echo "Done.\n";
?>
