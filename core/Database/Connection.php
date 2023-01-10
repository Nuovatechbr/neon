<?php

namespace Nuovatech\Neon\Database;

use PDO;
use PDOException;
use \Nuovatech\Neon\Neon;

/**
 * @desc: classe de persistência ao banco de dados
 * @author: Eduardo Marinho
 * @version: 1.0.0.0;
 */
abstract class Connection
{
    private static function connect()
    {
        try {
            $pdo = new PDO(
                Neon::$app->database->driver . ":host=" . Neon::$app->database->host . ";port=" . Neon::$app->database->port . "dbname=" . Neon::$app->database->name,
                Neon::$app->database->user,
                Neon::$app->database->password
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (\PDOException $e) {
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }
    }

    /**
     * @desc: executa uma query sem retornar valores
     * @param: $query String contendo a query;
     * @param: $params Array variável de auxílio para execução.
     * @return: boolean TRUE, caso tenha sido realizado alterações; FALSE, sem alteração.
     */
    public static function execute($query, $params = array())
    {
        $statement = self::connect()->prepare($query);
        $statement->execute($params);
        return ($statement->rowCount() > 0) ? true :  false;
    }

    /**
     * @desc: realiza a consulta no banco de dados e retorna valores
     * @param: $query String contendo a query;
     * @param: $params Array variável de auxílio para execução.
     */
    public static function select($query, $params = array())
    {
        $statement = self::connect()->prepare($query);
        $statement->execute($params);
        $data = $statement->fetchAll();
        return $data;
    }
}
