<?php

$_errorTypes = array(
    E_ERROR =>
        array(
            'label' => 'E_ERROR',
            'class' => 'error',
            'fatal' => true
        ),
    E_WARNING =>
        array(
            'label' => 'E_WARNING',
            'class' => 'notice',
            'fatal' => false
        ),
    E_PARSE =>
        array(
            'label' => 'E_PARSE',
            'class' => 'parse',
            'fatal' => false
        ),
    E_NOTICE =>
        array(
            'label' => 'E_NOTICE',
            'class' => 'notice',
            'fatal' => false
        ),
    E_CORE_ERROR =>
        array(
            'label' => 'E_CORE_ERROR',
            'class' => 'core-error',
            'fatal' => true
        ),
    E_CORE_WARNING =>
        array(
            'label' => 'E_CORE_WARNING',
            'class' => 'core-warning',
            'fatal' => false
        ),
    E_COMPILE_ERROR =>
        array(
            'label' => 'E_COMPILE_ERROR',
            'class' => 'compile-error',
            'fatal' => true
        ),
    E_COMPILE_WARNING =>
        array(
            'label' => 'E_COMPILE_WARNING',
            'class' => 'compile-warning',
            'fatal' => false
        ),
    E_USER_ERROR =>
        array(
            'label' => 'E_USER_ERROR',
            'class' => 'user-error',
            'fatal' => true
        ),
    E_USER_WARNING =>
        array(
            'label' => 'E_USER_WARNING',
            'class' => 'user-warning',
            'fatal' => false
        ),
    E_USER_NOTICE =>
        array(
            'label' => 'E_USER_NOTICE',
            'class' => 'user-notice',
            'fatal' => false
        ),
    E_STRICT =>
        array(
            'label' => 'E_STRICT',
            'class' => 'strict',
            'fatal' => false
        ),
    E_RECOVERABLE_ERROR =>
        array(
            'label' => 'E_RECOVERABLE_ERROR',
            'class' => 'recoverable-error',
            'fatal' => true
        ),
    E_DEPRECATED =>
        array(
            'label' => 'E_DEPRECATED',
            'class' => 'deprecated',
            'fatal' => false
        ),
    E_USER_DEPRECATED =>
        array(
            'label' => 'E_USER_DEPRECATED',
            'class' => 'user-deprecated',
            'fatal' => false
        )
);

function dyndns_error_handler($errno, $errstr, $errfile, $errline) {

    global $_errorTypes;
    if (!(error_reporting() & $errno))
        return true;
    $errorType = $_errorTypes[$errno];
    $show = $errorType['fatal'];

    $typeText = sprintf('%s (%d)', $errorType['label'], $errno);
    ob_start();
    debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    $backtrace = ob_get_contents();
    ob_end_clean();
    dyndns_logerror($errorType['class'], $typeText, $errstr, $errfile, $errline, $backtrace, $show);

    if ($errorType['fatal'])
        die();

    return true;

}

function dyndns_exception_handler(Throwable $exception) {
    dyndns_logerror('exception',
        'Exception ('.get_class($exception).')',
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine(),
        $exception->getTraceAsString(),
        true);
    die();
}

function dyndns_logerror($typeClass, $typeText, $message, $file, $line, $trace, $show) {

    if ($show) {
        echo "500 Internal Server Error<br>";
    }

    $timestamp = date('Y-m-d H:i:s');

    $html = sprintf('[<span class="timestamp">%s</span>] <span class="type">%s</span> @ <span class="filename">%s</span>:<span class="line">%d</span>',
        $timestamp,
        $typeText,
        $file,
        $line);
    $html .= "\n\t<span class=\"message\">{$message}</span>";
    $html .= "\n\t<div class=\"trace\">{$trace}</div>";
    $html = "<div class=\"entry entry-{$typeClass}\">\n\t{$html}\n</div>\n";

    $plain = sprintf("[%s][%s][%s:%d] %s\n", $timestamp, $typeText, $file, $line, $message);

    $file_base = ERROR_LOG_PATH . '/errors-' . date('Y-m-d');
    file_put_contents($file_base.'.html', $html, FILE_APPEND | LOCK_EX);
    file_put_contents($file_base.'.log', $plain, FILE_APPEND | LOCK_EX);

    if ($show) {
        echo "Details are written to log.";
    }

}

set_error_handler('dyndns_error_handler');
set_exception_handler('dyndns_exception_handler');
error_reporting(ERROR_REPORTING_LEVEL);

?>