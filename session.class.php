<?php

/**
 * Classe d'exception associée aux problèmes de session
 */
class SessionException extends Exception
{
}

/**
 * Classe associée à la gestion de la session
 */
class Session
{

    /**
     * Démarrer une session
     *
     * @see session_status()
     * @see headers_sent($file, $line)
     * @see session_start()
     *
     * @throws SessionException si la session ne peut être démarrée
     * @throws RuntimeException si le résultat de session_status() est incohérent
     *
     * @return void
     */
    public static function start()
    {
        if (!headers_sent()) {
            if (session_status() == 0) {
                throw new SessionException("la session ne peut etre demarree !");
            } else if (session_status() == 1) {
                session_start();
            } else if ((session_status() != 2)) {
                throw new RuntimeException("le résultat de session_status() est incohérent !");
            }
        }
    }
}
