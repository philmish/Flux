<?php declare(strict_types=1);

namespace Flux\cli;

enum FlagParseMode: string {
case CLI = "cli";
case JSON = "json";
case ENV = "env";
}
