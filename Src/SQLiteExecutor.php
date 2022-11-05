<?php declare(strict_types=1);

namespace Flux;

use Flux\Lib\DataCollection;
use Flux\Lib\Executor;
use PDO;

final class SQLiteExecutor extends Executor {
    
    static public function init(string $dsn): Executor {
       $pdo = new PDO("sqlite:" . $dsn) ;
       return new self($pdo);
    }

    public function feed(DataCollection $data, string $table) {
        
    }
}


