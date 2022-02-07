<?php
namespace Core\Database;

use \PDO;

class Database {

    private $db_name;
    private $db_user;
    private $db_pass;
    private $db_host;
    private $pdo;

    public function __construct($db_name, $db_host, $db_user, $db_pass) {
        $this->db_name = $db_name;
        $this->db_host = $db_host;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
    }

    // Récupère la connexion à la base de données
    private function getPDO() {
        // Si la connexion à la base de données n'a jamais été demandée
        if($this->pdo === null) {
            // Initialisation d'une nouvelle connexion
            $pdo = new PDO('mysql:dbname=' . $this->db_name . ';charset=UTF8;host=' . $this->db_host, $this->db_user, $this->db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo = $pdo;
        }

        // Retour de la connexion
        return $this->pdo;

    }

    // Récupère les résultats de la requête SQL sous forme d'objet spécifique
    public function query($statement, $class = '') {
        $req = $this->getPDO()->query($statement);
        if(!empty($class)) {
            return $req->fetchAll(PDO::FETCH_CLASS, $class);
        } else {
            return $req->fetchAll(PDO::FETCH_CLASS);
        }
    }

    // Récupère les résultats de la requête SQL sous forme d'objet spécifique
    public function prepare($statement, $attributs, $class, $one = false) {
        $req = $this->getPDO()->prepare($statement);
        $req->execute($attributs);
        // Mise en forme de la récupération : objet spécifique
        $req->setFetchMode(PDO::FETCH_CLASS, $class);
        // Si un seul élément est demandé : récupère un seul élément
        if($one) {
            return $req->fetch();
            // Sinon : récupère tous les éléments
        } else {
            return $req->fetchAll();
        }
    }

    // Récupère les résultats de la requête SQL sous forme d'objet spécifique
    public function execute($statement, $attributs) {
        $req = $this->getPDO()->prepare($statement);
        $req->execute($attributs);
        return $req->rowCount();
    }
}

?>
