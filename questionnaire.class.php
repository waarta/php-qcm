<?php

require_once 'entity.class.php';
// Pour plus tard :
// require_once 'question.class.php' ;
// require_once 'session.class.php' ;

/**
 * Classe permettant la gestion d'un questionnaire
 */
class Questionnaire extends Entity
{
    /**
     * Nom de la clé de session utilisée pour mémoriser les informations de la classe
     */
    const SESSION = 'Questionnaire';
    /**
     * @var array<Question> Tableau des questions du questionnaire
     */
    protected $questions = null;
    /**
     * @var int Question courante (index dans le tableau retourné par getQuestions())
     */
    protected $current = 0;

    /**
     * Usine pour fabriquer une instance à partir d'un identifiant.
     * Les données sont issues de la base de données
     *
     * @param int $id identifiant BD du questionnaire à créer
     *
     * @throws LogicException si le questionnaire ne peut pas être trouvé dans la base de données
     *
     * @return Questionnaire instance correspondant à $id
     */
    public static function createFromID($id)
    {

        $pdo = myPDO::getInstance();
        $stmt = $pdo->prepare(<<<SQL
            SELECT id, text
            FROM questionnaire
            WHERE id = $id
SQL
        );
        $stmt->execute();
        $stmt->setFetchMode($pdo::FETCH_CLASS, 'Questionnaire');
        $q = $stmt->fetch();
        if ($q == null) {
            throw new LogicException("le questionnaire ne peut pas être trouvé dans la base de données");
        }
        return $q;

    }

    /**
     * Lire l'ensemble des enregistrements de questionnaire de la base de données triés par ordre alphabétique
     *
     * @return array<Questionnaire> tableau d'instances de Questionnaire
     */
    public static function getAll()
    {
        $pdo = myPDO::getInstance();
        $stmt = $pdo->prepare(<<<SQL
            SELECT id, text
            FROM questionnaire
            ORDER BY 2
SQL
        );
        $stmt->execute();
        $stmt->setFetchMode($pdo::FETCH_CLASS, 'Questionnaire');
        $questions = $stmt->fetchAll();
        return $questions;

    }
}
