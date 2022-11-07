<?php declare(strict_types=1);

namespace Flux\cli;

enum Driver: string {
case SQLite = "sqlite";
case MySQL = "mysql";
case Maria = "maria";
}
