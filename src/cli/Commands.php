<?php declare(strict_types=1);

namespace Flux\cli;

enum Command: string {
case ExecScript = "execScript";
case Truncate = "truncate";
case Help = "help";
case FeedJSON = "feedJson";
case Scan = "scan";
}


