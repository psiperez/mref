<?php

use Adianti\Database\TConnection;

/**
 * Database Information Service
 *
 * @version    3.0
 * @package    service
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2014 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SystemDatabaseInformationService
{
    public static function getDatabaseTables($database)
    {
        $info = TConnection::getDatabaseInfo($database);
        $query['pgsql'] =  " SELECT n.nspname || '.' || c.relname AS table_name, obj_description(c.relfilenode, 'pg_class') as comment
                               FROM pg_class c
                                    LEFT JOIN pg_namespace n ON n.oid = c.relnamespace
                                    LEFT JOIN pg_tablespace t ON t.oid = c.reltablespace
                               WHERE (c.relkind = 'r'::\"char\" OR c.relkind = 'v'::\"char\")
                                     AND c.relname not like 'pg%' 
                                     AND n.nspname <> 'information_schema'
                                     AND has_schema_privilege(n.nspname, 'usage')
                                     AND has_table_privilege(n.nspname || '.' || c.relname, 'select')
                               ORDER BY 1";
        $query['sqlite'] = "SELECT name FROM sqlite_master WHERE (type = 'table' or type='view')";
        $query['mysql']  = 'SHOW TABLE STATUS';
        $query['oracle'] = "SELECT table_name FROM cat where table_type in ('TABLE', 'VIEW') AND table_name not like '%$%'";
        $query['mssql']  = "select name from sysobjects where (type = 'U' or type='V') order by name";
        
        if (in_array($info['type'], [ 'pgsql', 'mysql', 'sqlite', 'oracle', 'mssql'] ))
        {
            $table_list = [];
            $sql = $query[ $info['type'] ];
                     
            TTransaction::open($database);
            $conn = TTransaction::get();
            $result = $conn->query($sql);
            $tables = $result->fetchAll();
            
            foreach ($tables as $row)
            {
                $table_name = $row[0];
                $table_list[ $table_name ] = $table_name;
            }
            TTransaction::close();
            return $table_list;
        }
    }
}