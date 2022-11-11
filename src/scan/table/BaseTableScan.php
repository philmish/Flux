<?php declare(strict_types=1);

namespace Flux\scan\table;

use Exception;
use Flux\cli\Flags;
use Flux\lib\Executor;
use Flux\scan\Scan;
use Flux\scan\ScanContext;
use Flux\scan\ScanName;

final class BaseTableScan extends Scan {
    
    static public function Run(Flags $flags, Executor $db): ScanContext {
        $ctx = TableScanContext::Create(ScanName::BaseTableScan);
        $args = ["table"];
        $loadedArgs = $flags->getArgs($args);
        if (count($loadedArgs['missing']) > 0) {
            $err = new Exception(
                "Missing args for BaseTableScan ". implode(", ", $loadedArgs["missing"])
            );
            $ctx->pushErr($err);
            $ctx->failed();
            return $ctx;
        }
        $args = $loadedArgs["found"];
        $ctx->set("table", $args["table"]);


    }
}
