<?php declare(strict_types=1);

namespace Flux\scan\table;

use Flux\scan\ScanContext;
use Flux\scan\ScanName;

final class TableScanContext extends ScanContext {
    
    private static function BaseScanReport(): array {
        return [
            "table" => "",
            "length" => 0,
            "schema" => [],
        ];
    }

    private static function UnknownScan(): self {
        $ctx = new self([], ScanName::Unknown);
        $ctx->failed();
        return $ctx;
    }

    public static function Create(ScanName $scan): self {
        $ctx = match ($scan) {
        ScanName::BaseTableScan => new TableScanContext(
            TableScanContext::BaseScanReport(), $scan
            ),
        default => TableScanContext::UnknownScan(),
        };
        return $ctx;
    }
}
