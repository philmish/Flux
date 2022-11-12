<?php
// @codingStandardsIgnoreFile
// @codeCoverageIgnoreStart
// this is an autogenerated file - do not edit
spl_autoload_register(
    function($class) {
        static $classes = null;
        if ($classes === null) {
            $classes = array(
                'flux\\cli\\app' => '/cli/App.php',
                'flux\\cli\\command' => '/cli/Commands.php',
                'flux\\cli\\commandcontext' => '/cli/CommandContext.php',
                'flux\\cli\\configuration' => '/cli/Configuration.php',
                'flux\\cli\\driver' => '/cli/Drivers.php',
                'flux\\cli\\escapecolor' => '/cli/EscapeColor.php',
                'flux\\cli\\flagparsemode' => '/cli/FlagParseMode.php',
                'flux\\cli\\flags' => '/cli/Flags.php',
                'flux\\cli\\fs\\collectionloader' => '/cli/fs/CollectionLoader.php',
                'flux\\cmd\\command' => '/cmd/Command.php',
                'flux\\cmd\\execscript' => '/cmd/ExecScript.php',
                'flux\\cmd\\feedjson' => '/cmd/FeedJSON.php',
                'flux\\cmd\\help' => '/cmd/Help.php',
                'flux\\cmd\\scan' => '/cmd/Scan.php',
                'flux\\cmd\\truncate' => '/cmd/Truncate.php',
                'flux\\lib\\data' => '/lib/Data.php',
                'flux\\lib\\datacollection' => '/lib/DataCollection.php',
                'flux\\lib\\datafield' => '/lib/DataField.php',
                'flux\\lib\\datavalidator' => '/lib/DataValidator.php',
                'flux\\lib\\error\\cliexception' => '/lib/error/CliException.php',
                'flux\\lib\\error\\commandexception' => '/lib/error/CommandException.php',
                'flux\\lib\\error\\datacollectionexception' => '/lib/error/DataCollectionException.php',
                'flux\\lib\\error\\dataexception' => '/lib/error/DataException.php',
                'flux\\lib\\error\\datafieldexception' => '/lib/error/DataFieldException.php',
                'flux\\lib\\error\\executorexception' => '/lib/error/ExecutorException.php',
                'flux\\lib\\error\\fluxexception' => '/lib/error/FluxException.php',
                'flux\\lib\\error\\fsexception' => '/lib/error/FSException.php',
                'flux\\lib\\error\\scanexception' => '/lib/error/ScanException.php',
                'flux\\lib\\error\\schemaexception' => '/lib/error/SchemaException.php',
                'flux\\lib\\error\\validatorexception' => '/lib/error/ValidatorException.php',
                'flux\\lib\\executor' => '/lib/Executor.php',
                'flux\\lib\\query' => '/lib/Query.php',
                'flux\\lib\\schema' => '/lib/Schema.php',
                'flux\\scan\\scan' => '/scan/Scan.php',
                'flux\\scan\\scancontext' => '/scan/ScanContext.php',
                'flux\\scan\\scanname' => '/scan/ScanName.php',
                'flux\\scan\\scanner' => '/scan/Scanner.php',
                'flux\\scan\\table\\basetablescan' => '/scan/table/BaseTableScan.php',
                'flux\\scan\\table\\tablescancontext' => '/scan/table/TableScanContext.php',
                'flux\\sqliteexecutor' => '/SQLiteExecutor.php'
            );
        }
        $cn = strtolower($class);
        if (isset($classes[$cn])) {
            require __DIR__ . $classes[$cn];
        }
    },
    true,
    false
);
// @codeCoverageIgnoreEnd
