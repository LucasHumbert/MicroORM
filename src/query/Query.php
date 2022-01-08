<?php
namespace hellokant\query;

use hellokant\factory\ConnectionFactory;

class Query {

    private $sqltable;
    private $fields = '*';
    private $where = null;
    private $args = [];
    private $sql = '';


    public static function table( string $table) : Query {
        $query = new Query;
        $query->sqltable= $table;
        return $query;
    }

    public function select( array $fields) : Query {
        $this->fields = implode( ',', $fields);
        return $this;
    }

    public function delete(){
        $this->sql = 'delete from ' . $this->sqltable . ' where ' . $this->where;
        $myPdo = ConnectionFactory::getConnection();
        $stmt = $myPdo->prepare($this->sql);
        $stmt->execute($this->args);
        return $stmt->rowCount();
    }

    public function insert($array){
        $length = count($array);
        $this->sql = 'insert into ' . $this->sqltable . '(';
        if ($length == 1) {

            $this->sql .= array_keys($array)[0] . ') values (?);';
            array_push($this->args, array_values($array)[0]);
        } else {
            $values = '';
            for ($i = 0; $i < $length; $i++){
                $this->sql .= array_keys($array)[$i] . ',';
                $values .= '?,';
                array_push($this->args, array_values($array)[$i]);
            }
            $this->sql = substr($this->sql, 0, -1);
            $values = substr($values, 0, -1);
            $this->sql .= ") values ($values)";
        }

        $myPdo = ConnectionFactory::getConnection();
        $stmt = $myPdo->prepare($this->sql);
        $stmt->execute($this->args);
        return $myPdo->lastInsertId();
    }

    /*
    les différentes parties de la requête le tableau d'arguments pour la requête préparée PDO
    le texte complet, pour affichage si besoin
    */
    public function where(string $col, string $op, $val) : Query {
        if (!is_null($this->where)) $this->where .= ' and ';
        $this->where .= ' ' . $col . ' ' . $op . ' ? ';
        $this->args[]=$val;
        return $this;
    }

    public function get() {
        $this->sql  = 'select '. $this->fields . ' from ' . $this->sqltable;
        if ($this->where != null){
            $this->sql .= ' where ' . $this->where;
        }

        $myPdo = ConnectionFactory::getConnection();
        $stmt = $myPdo->prepare($this->sql);
        $stmt->execute($this->args);
        $result = $stmt->fetchAll();
        return $result;

        /*
        $stmt = $pdo->prepare($this->sql);
        $stmt->execute($this->args);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        */

    }

}