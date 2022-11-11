<?php declare(strict_types=1);

namespace Flux\scan;

use Flux\cli\Flags;
use Flux\lib\error\ScanException;
use Flux\lib\Executor;
use Flux\scan\table\BaseTableScan;

final class Scanner {
    
    /**
     * @throws ScanException
     * @param Flags $flags
     * @param Executor $db
     *
     * @return ScanContext
     *
     */
    public static function Execute(Flags $flags, Executor $db): ScanContext {
        $args = ["scanName"];
        $loaded = $flags->getArgs($args);
        $miss = $loaded["missing"];
        if (count($miss) > 0) {
            throw new ScanException("Missing args to run a Scanner " . implode(", ", $miss));
        }
        $args = $loaded["found"];
        $scan = ScanName::tryFrom($args["scanName"]) ?? ScanName::Unknown;
        switch ($scan) {
        case ScanName::BaseTableScan:
            return BaseTableScan::Run($flags, $db);

        }
    }
}
