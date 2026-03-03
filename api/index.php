<?php

/**
 * Vercel Serverless Router - Diagnostic Version
 *
 * This file wraps the Laravel bootstrap with error handling for debugging.
 * Once deployment works, replace this with just:
 *
 * <?php
 * require __DIR__ . '/../public/index.php';
 */

// ============================================================
// DIAGNOSTIC: Enable full error reporting
// ============================================================
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

// Log startup to stderr so we can see it in Vercel logs
error_log('[Vercel] Starting Laravel bootstrap from api/index.php');

// ============================================================
// DIAGNOSTIC: Catch fatal errors
// ============================================================
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $message = sprintf(
            '[Vercel] FATAL ERROR: %s in %s on line %d',
            $error['message'],
            $error['file'],
            $error['line']
        );
        error_log($message);
    }
});

// ============================================================
// DIAGNOSTIC: Catch uncaught exceptions
// ============================================================
set_exception_handler(function ($exception) {
    $message = sprintf(
        "[Vercel] UNCAUGHT EXCEPTION: %s in %s on line %d\nStack trace:\n%s",
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine(),
        $exception->getTraceAsString()
    );
    error_log($message);
});

// ============================================================
// DIAGNOSTIC: Catch warnings and notices
// ============================================================
set_error_handler(function ($severity, $message, $file, $line) {
    if (! (error_reporting() & $severity)) {
        return false;
    }
    error_log("[Vercel] PHP ERROR [$severity]: $message in $file on line $line");

    return true;
});

// ============================================================
// PROCEED: Boot Laravel
// ============================================================
error_log('[Vercel] Requiring public/index.php...');

require __DIR__.'/../public/index.php';
