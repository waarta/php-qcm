<?php

require_once 'entity.class.php';
// Pour plus tard :
require_once 'question.class.php';
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
        $questionnaires = $stmt->fetchAll();
        return $questionnaires;

    }

    /**
     * Usine permettant de construire une instance de Questionnaire
     * Si le paramètre $id est différent de null, rechercher dans la base de données avec createFromId
     * /!\ Si le paramètre $id vaut null, lancer une exception (c'est une solution transitoire avant de passer à la suite)
     *
     * @return Questionnaire
     */
    public static function create($id = null)
    {
        if ($id == null) {
            throw new LogicException("le paramètre id vaut null");
        } else {
            return Self::createFromID($id);
        }
    }

    /**
     * Le questionnaire est-il en cours ?
     * La valeur de l'attribut $current doit être inférieure à la taille du tableau retourné par getQuestions()
     *
     * @see count()
     *
     * @return bool
     */
    public function isInProgress()
    {
        return ($this->current < $this->getQuestions()) ? true : false;
    }

    /**
     * Retourner les éléments du questionnaire par index de proposition
     * Si l'attribut $questions est null, le tableau des questions lui sera affecté
     *
     * @see Question::getFromQuestionnaireId
     *
     * @return array<Question> tableau d'instances de Question
     */
    public function getQuestions()
    {
        if ($this->questions == null) {
            $this->questions = Question::getFromQuestionnaireId($this->id);
        }
        return $this->questions;
    }

    /**
     * Donne la question courante
     *
     * @return Question
     */
    public function getCurrentQuestion()
    {
        return $this->questions[$this->current];
    }

    /**
     * Donne l'étape courante du questionnaire (current+1)
     *
     * @return int
     */
    public function getStep()
    {
        return $this->current + 1;
    }

    /**
     * Donne le nombre de questions
     *
     * @return int
     */
    public function getTotal()
    {
        return sizeof($this->questions);
    }

}
