<?php
/**
 * During a large data migration, you get the following error: Fatal error:
 * Allowed memory size of 134217728 bytes exhausted (tried to allocate 54 bytes).
 * You've traced the problem to the following snippet of code:
 */

$stmt = $pdo->prepare('SELECT * FROM largeTable');
$stmt->execute();

while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {}

$stmt->closeCursor();
