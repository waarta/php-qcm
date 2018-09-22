<?php

require_once 'webpage.class.php';
require_once 'questionnaire.class.php';

$page = new WebPage('Gestion des questionnaires');

$page->appendCssUrl("https://code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css");
$page->appendCssUrl("questionnaire.css");

/* Génération d'une liste déroulante contenant les questionnaires disponibles
 * La liste déroulante sera nommée 'id'
 * Chaque option affichera le titre du questionnaire et la valeur associée sera l'identifiant du questionnaire
 */
$select = "<select name=\"id\">";

$questionaires = Questionnaire::getAll();
foreach ($questionaires as $q) {
    $select .= "<option value=\"$q->id\">$q->text</option> ";
}
$select .= "</select>";

$page->appendContent(<<<HTML
        <h1 class="ui-widget-header">Gestion des questionnaires</h1>
        <div class="questionnaire ui-widget">
            <div class="ui-widget-header">Selection d'un questionnaire</div>
            <div class="ui-widget-content">
                <form class="main" action="questionnaire.php" method="GET">
                    {$select}
                    <input type="submit" value="go&nbsp;!">
                </form>
            </div>
        </div>
HTML
);

echo $page->toHTML();
