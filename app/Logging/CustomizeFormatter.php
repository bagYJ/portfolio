<?php
declare(strict_types=1);

namespace App\Logging;

use Monolog\Formatter\NormalizerFormatter;
use Monolog\Handler\FormattableHandlerInterface;

class CustomizeFormatter
{
    /**
     * Customize the given logger instance.
     */
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            if ($handler instanceof FormattableHandlerInterface) {
                $formatter = $handler->getFormatter();
                if ($formatter instanceof NormalizerFormatter) {
                    $formatter->setMaxNormalizeItemCount(10000);
                }
            }
        }
    }
}
