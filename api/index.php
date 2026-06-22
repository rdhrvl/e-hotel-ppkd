<?php

try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    error_log("CATCH_ERROR: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
    http_response_code(500);
    echo "Internal Server Error";
}
