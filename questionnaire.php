<?php

require_once 'webpage.class.php';
require_once 'questionnaire.class.php';

try {

    $questionnaire = Questionnaire::create(isset($_GET['id']) ? $_GET['id'] : null);

    if (isset($_GET['answer']) && $questionnaire->isInProgress()) {
        $questionnaire->setUserAnswer($_GET['answer']);
    }
// Pour plus tard
    // $questionnaire->saveIntoSession() ;

    $page = new WebPage("QCM - {$questionnaire->getText()}");
    $page->appendJsUrl("https://code.jquery.com/jquery-1.12.3.min.js");
    $page->appendJsUrl("https://code.jquery.com/ui/1.11.4/jquery-ui.min.js");
    $page->appendCssUrl("https://code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css");
    $page->appendCssUrl("questionnaire.css");

    $page->appendContent(<<<HTML
        <h1 class="ui-widget-header">QCM - {$questionnaire->getText()}</h1>
HTML
    );

    if ($questionnaire->isInProgress()) {

        // Pour plus tard
        // $page->appendJsUrl("questionnaire.js") ;

        $currentQuestion = $questionnaire->getCurrentQuestion();
        $radios = "";
        foreach ($currentQuestion->getAnswers() as $answer) {
            $radios .= "\n                <input type='radio' id='answer{$answer->getId()}' name='answer' value='{$answer->getId()}'><label for='answer{$answer->getId()}'>{$answer->getText()}</label>";
        }

        $page->appendContent(<<<HTML
            <div class="questionnaire ui-widget">
                <div class="ui-widget-header"><span class="header-info step ui-state-active ui-corner-all">{$questionnaire->getStep()}</span>&nbsp;{$currentQuestion->getText()}</div>
                <div class="ui-widget-content">
                    <form action="{$_SERVER['PHP_SELF']}" method="GET" class="main">
                        <div class="radios">
                            $radios
                        </div>
                        <input type="submit" value="next">
                    </form>
                    <div class="progressbar"><div class="progress-label ui-state-default">{$questionnaire->getStep()}/{$questionnaire->getTotal()}</div></div>
                </div>
            </div>
HTML
        );
    } else {
        $page->appendContent(<<<HTML
            <div class="questionnaire ui-widget">
                <div class="ui-widget-header">C'est fini&nbsp;! <span class="header-info score ui-state-active ui-corner-all">{$questionnaire->getScore()}/{$questionnaire->getTotal()}</span></div>
                <div class="ui-widget-content">
                    <a href="detail.php">voir les détails...</a>
                </div>
            </div>
HTML
        );
    }

    echo $page->toHTML();

} catch (Exception $e) {
    header("Location: " . mb_ereg_replace("/[^/]+$", "/questionnaires.php", $_SERVER['SCRIPT_URI']));
}
