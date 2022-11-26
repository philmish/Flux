<?php declare(strict_types=1);

namespace Flux\scan\table;

use Flux\cli\EscapeColor;
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

    private static function TableHasSchemaReport(): array {
        return [
            "tableSchema" => [],
            "compareSchema" => [],
            "matches" => false,
        ];
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

    public function printReport(): void {
        echo "Table: " . $this->report["table"] . "\n";
        echo "Length: " . $this->report["length"] . "\n";
        foreach($this->report["schema"] as $field) {
            echo $field->getName() . ": " . $field->type() . "\n";
        }
        $status = $this->success() ? EscapeColor::green("Status: Success") : EscapeColor::red("Status: Failed");
        echo $status;
    }
}
