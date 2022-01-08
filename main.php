<?php

require_once 'vendor/autoload.php';
use hellokant\query\Query;
use hellokant\factory\ConnectionFactory;
use hellokant\models\Model;
use hellokant\models\Article;
use hellokant\models\Categorie;

$conf = parse_ini_file('conf/conf.ini') ;
ConnectionFactory::makeConnection($conf);

/**
 * -- QUERIES --
 */

$select = Query::table('article')->select(['nom', 'descr'])->where('nom', '=', "roller")->get();

//$insert = Query::table('article')->insert(['nom' => 'skateboard', 'descr' => 'planche à 4 roues', 'tarif' => 49.99, 'id_categ' => 1]);

$article1 = new Article(['nom' => 'trottinette', 'descr' => 'trottinette pour enfant', 'tarif' => 39.99, 'id_categ' => 1]);
//$article1->insert();

$delete = Query::table('article')->where('nom', '=', "trottinette");
//$delete->delete();


/**
 * -- FINDERS --
 */

$liste = Article::all() ;
//foreach ($liste as $article) print "$article->nom<br />";

/**
 *  find
 */

$find = Article::find(64);
//$find = Article::find(65, ['nom', 'descr']);
//$find = Article::find(['tarif', '>', '60'], ['nom', 'tarif']);
//$find = Article::find([['tarif', '>', '60'], ['tarif', '<', '150']], ['nom', 'tarif']);

//print_r($find);

/**
 *  first
 */

$first = Article::first(65);
//$first = Article::first(['tarif', '>', 60]);
//$first = Article::first(['tarif', '>', 60], ['nom', 'descr']);

//print_r($first);


/**
 * -- ASSOCIATIONS --
 */

$liste = Article::first(64, ['*']);
$categorie = $liste->belongs_to('Categorie', 'id_categ');
//print_r($categorie);


$m = Categorie::first(1);
$list_article = $m->has_many('Article', 'id_categ');
//print_r($list_article);


$categorie = Article::first(65)->categorie();
//print_r($categorie);


$list = Categorie::first(1)->articles;
//print_r($list);
