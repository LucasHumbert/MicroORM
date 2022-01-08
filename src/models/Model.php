<?php
namespace hellokant\models;
use hellokant\query\Query;

abstract class Model
{
    protected $attributes = [];

    public function __construct($attributes = []){
        $this->attributes = $attributes;
    }

    public function __get($name){
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        } else if (method_exists(static::class, $name)) {
            return $this->$name();
        } else {
            return null;
        }
    }

    public function __set($name, $value){
        $this->$name = $value;
    }

    public function delete(){
        if (isset($this->attributes[static::$primaryKey])) {
            Query::table($this->table)
            ->where('id', '=', $this->attributes[static::$primaryKey])
            ->delete();
        }
    }

    public function insert(){
        $query = Query::table(static::$table)
                ->insert($this->attributes);
        $this->attributes['id'] = $query;
    }

    static function all(){
        $query = Query::table(static::$table)
                ->select(['*'])
                ->get();
        $objects = [];
        foreach($query as $object){
            $objects[] = new static($object);
        }
        return $objects;
    }

    static function find($param, $fields = null){
        $query = Query::table(static::$table);
        if (is_int($param)){

            if($fields){
                $query = $query->select($fields);
            }
            $query = $query->where(static::$primaryKey, '=', $param)->get();
        } elseif (is_array($param)){
            if($fields){
                $query = $query->select($fields);
            }

            if (is_array($param[0])){
                foreach ($param as $p){
                    $query = $query->where($p[0], $p[1], $p[2]);
                }
            } else{
                $query = $query->where($param[0], $param[1], $param[2]);
            }
            $query = $query->get();

        }

        $objects = [];
        foreach($query as $object){
            $objects[] = new static($object);
        }
        return $objects;
    }

    static function first($param, $fields = null){
        $res = static::find($param, $fields);
        return $res[0];
    }

    public function belongs_to($model, $forKey){
        $nomClasse = __NAMESPACE__.'\\'.$model;
        $obj = new $nomClasse();
        $res = Query::table($obj::$table)->where($obj::$primaryKey, '=', $this->attributes[$forKey])->get();
        return $res;
    }

    public function has_many($model, $forKey){
        $nomClasse = __NAMESPACE__.'\\'.$model;
        $obj = new $nomClasse();
        $res = Query::table($obj::$table)->where($forKey, '=', $this->attributes['id'])->get();
        $objects = [];
        foreach ($res as $r){
            $objects[] = new static($r);
        }
        return $objects;
    }
}
