<?php
while (ob_get_level()) ob_end_clean();
header('Content-Type: application/json');

echo json_encode([
    'status' => 'test',
    'message' => 'API работает',
    'data' => ['test' => 'ok']
]);

