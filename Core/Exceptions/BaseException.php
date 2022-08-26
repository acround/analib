<?php

namespace analib\Core\Exceptions;

use analib\Core\Application\Application;
use ErrorException;

class BaseException extends ErrorException
{

    protected $code = -1000;
    protected int $httpCode = 400;
    protected $message = 'Fatal error';

    public function printTrace(array $trace): string
    {
        $traceString = array();
        $traceString[] = '<tr><td>#0' .
            '</td><td>' . $this->getFile() .
            '</td><td>' . $this->getLine() .
            '</td><td></td></tr>';
        foreach ($trace as $k => $line) {
            $traceString[] = '<tr><td>#' . (count($trace) - $k) .
                '</td><td>' . ($line['file'] ?? '') .
                '</td><td>' . ($line['line'] ?? '') .
                '</td><td>' . ($line['function'] ?? '') . '</td></tr>';
        }
        return implode("\n", $traceString);
    }

    public function __toString()
    {
        if (Application::isConsole()) {
            $message = array();
            $message[] = '';
            $message[] = '';
            $message[] = get_class($this) . ":";
            $message[] = 'Code:' . $this->code;
            $message[] = 'Message:' . $this->message;
            $message[] = 'File:' . $this->file;
            $message[] = 'Line:' . $this->line;
            $message[] = 'Severity:' . $this->severity;
            $message[] = '';
            $message[] = 'Trace:';
            $message[] = '';
            $trace = $this->getTrace();
            foreach ($trace as $k => $line) {
                $message[] = '#' . (count($trace) - $k) . "\t" . ($line['file'] ?? '') . ':' . ($line['line'] ?? '') . '(' . ($line['function'] ?? '') . ')';
            }
            $message = implode("\n", $message);
        } else {
            $trace = $this->getTrace();
            $message = '<table><tbody>' .
                '<tr><th colspan="4">Exception</th></tr>' .
                '<tr><td colspan="4">' . get_class($this) . ': ' . $this->message . '</td></tr>' .
                '<tr><th colspan="4"><h3>Trace</h3></th></tr>' .
                '<tr><th>#</th><th>File</th><th>Line</th><th>Function</th></tr>' .
                self::printTrace($trace) .
                '</tbody></table>';
        }
        return $message;
    }

}
