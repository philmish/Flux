<?php declare(strict_types=1);

namespace Flux;

use Flux\Lib\Executor;
use PDO;
use Flux\lib\error\ExecutorException;
use PDOException;

final class MariaDBExecutor extends Executor {
    
    static public function init(string $dsn): Executor {
        //DSN Format: mysql:host=xxx;port=xxx;dbname=xxx;user=xxx;password=xxx
        try {
            $pdo = new PDO("mysql:" . $dsn) ;
            $pdo->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );
            $pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        } catch (PDOException $e) {
            throw new ExecutorException("Failed to connect to maria db.", previous: $e);
        }
       return new self($pdo);
    }
}

