<?php declare(strict_types=1);

namespace Flux\scan;

enum ScanName: string {
case BaseTableScan = "baseTableScan";
case DataComparison = "dataComp";
case Unknown = "unknown";
}
