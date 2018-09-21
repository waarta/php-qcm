<?php

require_once 'entity.class.php';

/**
 * Classe représentant une proposition à une question
 */
class Answer extends Entity
{
    /**
     * Est-ce que la proposition est la bonne réponse ?
     * @var int 1 lorsque la proposition est la bonne réponse, 0 sinon
     */
    protected $correct = null;

    /**
     * Accesseur sur correct
     *
     * @return bool Est-ce que la proposition est la bonne réponse ?
     */
    public function isCorrect()
    {
        return ($correct == 1) ? true : false;
    }

    /**
     * Retourner les propositions de la question identifiée par $questionId, par ordre de proposition
     *
     * @param int $questionId identifiant BD de la question à laquelle sont attachées les propositions
     *
     * @return array<Answer> tableau d'instances de Answer
     */
    public static function getFromQuestionId($questionId)
    {
        $pdo = myPDO::getInstance();
        $stmt = $pdo->prepare(<<<SQL
            SELECT id, text
            FROM question
            WHERE questionId = $questionId
            ORDER BY 2
SQL
        );
        $stmt->execute();
        $stmt->setFetchMode($pdo::FETCH_CLASS, 'Answer');
        $answers = $stmt->fetchAll();
        return $answers;
    }
}
