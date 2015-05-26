<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
class BaseModel extends Model {

    public $ignoredFields = array(
        'id' , 'created_at' , 'updated_at'
    );

    public function getAllColumnsNames($edit = false)
    {
        switch (\DB::connection()->getConfig('driver')) {
            case 'pgsql':
                $query = "SELECT column_name FROM information_schema.columns WHERE table_name = '".$this->table."'";
                $column_name = 'column_name';
                $reverse = true;
                break;

            case 'mysql':
                $query = 'SHOW COLUMNS FROM '.$this->table;
                $column_name = 'Field';
                $reverse = false;
                break;

            case 'sqlite':
                $query = "PRAGMA table_info(".$this->table.")";
                $column_name = 'name';
                $reverse = false;
                break;

            case 'sqlsrv':
                $parts = explode('.', $this->table);
                $num = (count($parts) - 1);
                $table = $parts[$num];
                $query = "SELECT column_name FROM ".DB::connection()->getConfig('database').".INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'".$table."'";
                $column_name = 'column_name';
                $reverse = false;
                break;

            default: 
                $error = 'Database driver not supported: '.DB::connection()->getConfig('driver');
                throw new Exception($error);
                break;
        }

        $columns = array();

        foreach(\DB::select($query) as $column){
            if (!($edit && in_array($column->$column_name, $this->ignoredFields)))
                $columns[] = $column->$column_name;
        }

        if($reverse){
            $columns = array_reverse($columns);
        }

        return $columns;
    }

}