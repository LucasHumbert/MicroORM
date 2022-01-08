<?php

namespace hellokant\models;

class Categorie extends Model
{
    static $table      = 'categorie';
    static $primaryKey = 'id';

    public function articles(){
        return $this->has_many('Article', 'id_categ');
    }
}
