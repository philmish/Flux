<?php declare(strict_types=1);

namespace Flux\scan\table;

use Flux\cli\Flags;
use Flux\lib\error\ExecutorException;
use Flux\lib\error\ScanException;
use Flux\lib\Executor;
use Flux\scan\Scan;
use Flux\scan\ScanContext;
use Flux\scan\ScanName;

final class BaseTableScan extends Scan {
    
    /**
     * @throws ScanException
     *
     * @param Flags $flags
     * @param Executor $db
     *
     * @return ScanContext
     *
     */
    static public function Run(Flags $flags, Executor $db): ScanContext {
        $ctx = TableScanContext::Create(ScanName::BaseTableScan);
        $args = ["table"];
        $loadedArgs = $flags->getArgs($args);
        if (count($loadedArgs['missing']) > 0) {
            $err = new ScanException(
                "Missing args for BaseTableScan ". implode(", ", $loadedArgs["missing"])
            );
            $ctx->pushErr($err);
            $ctx->failed();
            return $ctx;
        }
        $args = $loadedArgs["found"];
        $ctx->set("table", $args["table"]);

        try {
            $ctx->set("length", $db->countTableRows($args["table"]));
        } catch (ExecutorException $e) {
            $err = new ScanException(
                "BaseTableScan failed to count rows of " . $args["table"],
                previous:$e
            );
            $ctx->pushErr($err);
            $ctx->failed();
            return $ctx;
        }
        try {
            $ctx->set("schema", $db->getTableSchema($args["table"])->getFields());
        } catch (ExecutorException $e) {
            $err = new ScanException(
                "BaseTableScan failed to retrieve schema from " . $args["table"],
                previous:$e
            );
            $ctx->pushErr($err);
            $ctx->failed();
            return $ctx;
        }
        $ctx->succeeded();
        return $ctx;
    }
}
