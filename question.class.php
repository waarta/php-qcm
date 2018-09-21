<?php

require_once 'entity.class.php';
require_once 'answer.class.php';

/**
 * Classe représentant une question
 */
class Question extends Entity
{
    /**
     * @var array<Answer> Tableau des propositions associées à la Question
     */
    protected $answers = null;
    /**
     * @var Answer Réponse fournie par l'utilisateur pour la Question
     */
    protected $userAnswer = null;

    /**
     * Retourner les propositions de l'élément par ordre d'index
     * Si l'attribut $answers est null, le tableau des propositions lui sera affecté
     *
     * @see Answer::getFromQuestionId
     *
     * @return array<Answer> Tableau d'instances de Answer
     */
    public function getAnswers()
    {
        if ($this->answers == null) {
            $this->answers = Answer::getFromQuestionId($this->id);
        }
        return $this->answers;
    }

    /**
     * Retourner les questions du questionnaire identifié par $questionnaireId par ordre de proposition
     *
     * @param int $questionnaireId identifiant BD du questionnaire auquel sont attachées les questions
     *
     * @return array<Question> tableau d'instances de Question
     */
    public static function getFromQuestionnaireId($questionnaireId)
    {
        $pdo = myPDO::getInstance();
        $stmt = $pdo->prepare(<<<SQL
            SELECT id, text
            FROM question
            WHERE questionnaireId = $questionnaireId
            ORDER BY 2
SQL
        );
        $stmt->execute();
        $stmt->setFetchMode($pdo::FETCH_CLASS, 'Question');
        $questions = $stmt->fetchAll();
        return $questions;
    }
}
