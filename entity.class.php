<?php

require_once 'myPDO.mysql.multiple-choice.include.php';

/**
 * Classe abstraite permettant de gérer les atttributs id et text issus de la base de données
 */
abstract class Entity
{
    /// Identifiant
    private $id = "";
    /// Texte
    private $text = "";
    /**
     * Constructeur non accessible
     */
    public function __constructor($id, $txt)
    {
        $this->id = $id;
        $this->text = $txt;
    }

    /**
     * Accesseur sur id
     *
     * @return string Identifiant
     */
    public function id()
    {return $this->id+"";}

    /**
     * Accesseur sur text
     *
     * @return string Texte
     */
    public function text()
    {return $this->text;}
}
