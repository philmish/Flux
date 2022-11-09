<?php declare(strict_types=1);

namespace Flux\lib\error;

use Exception;

abstract class FluxException extends Exception {

    protected string $module;

    protected function __construct(string $msg, string $module, int $code = 0, ?Exception $previous = null) {
       $this->module = $module; 
       parent::__construct($msg, $code, $previous);
    }

    public function asArray(): array {
        return [
            "module" => $this->module,
            "code" => $this->getCode(),
            "message" => $this->getMessage(),
            "previous" => $this->getPrevious()->getMessage(),
        ];
    }

    public function asString(): string {
        $arr = $this->asArray();
        return "module: " . $arr['module'] ."\n" .
            "code: " . $arr['code'] . "\n" .
            "message: " . $arr['message'] . "\n\n" .
            "previous: " . $arr['previous'] . "\n";
    }
}


