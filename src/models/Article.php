<?php
namespace hellokant\models;

class Article extends Model
{
    static $table      = 'article';
    static $primaryKey = 'id';

    public function categorie(){
        return $this->belongs_to('Categorie', 'id_categ');
    }
}
