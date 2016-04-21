<?php

namespace App\Services;

use \PDO;
use \PDOException;

class StorageService {

    private $pdo;

    public function __construct() {
        // Incluimos el archivo que contiene las credenciales
        require("bd-credenciales.php");

        $this->pdo = new PDO(
            "mysql:host={$config['db_host']};dbname={$config['db_name']}",
            $config['db_user'], $config['db_pass']
        );

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
    }

    public function query($query, $params=[], $usarParametrosConTipo = false) {
        $cuentaDeRegistrosAfectados = null;

        $resultado = [
            "data" => null
        ];

        $isInsert = $this->esInsert($query);
        $isDelete = $this->esDelete($query);
        $isUpdate = $this->esUpdate($query);
        $isSelect = $this->esSelect($query);

        try {
            $stmt = $this->pdo->prepare($query);

            if ($isDelete) {
                $finalQuery = $query;

                foreach ($params as $key => $value) {
                    if (is_int($value)) {
                        $finalQuery = str_replace($key, $value, $finalQuery);
                    } else {
                        $finalQuery = str_replace($key, "'$value'", $finalQuery);
                    }
                }

                $cuentaDeRegistrosAfectados = $this->pdo->exec($finalQuery);
            } else {
                if ($usarParametrosConTipo) {
                    foreach ($params as $parametro => &$valores) {
                        $nombre = $parametro;
                        $valor = &$valores[0];
                        $tipo = $valores[1];
                        $stmt->bindParam($nombre, $valor, $tipo);
                    }

                    $params = null;
                }

                $stmt->execute($params);
            }

            if ($isSelect) {
                while ($content = $stmt->fetch()) {
                    $resultado["data"][] = $content;
                }

                $cuentaDeRegistrosAfectados = count($resultado["data"]);
            }

            if ($isInsert) {
                $resultado["meta"]["id"] = $this->pdo->lastInsertId();
            }

            if ($isUpdate || $isInsert) {
                $cuentaDeRegistrosAfectados = $stmt->rowCount();
            }
        } catch (PDOException $e) {
            $resultado["error"] = true;
            $resultado["message"] = $e->getMessage();
        }

        if (isset($cuentaDeRegistrosAfectados)) {
            $resultado["meta"]["count"] = $cuentaDeRegistrosAfectados;
        }

        return $resultado;
    }

    private function esSelect($query) {
        return $this->revisarTipoDeQuery($query, "SELECT");
    }

    private function esInsert($query) {
        return $this->revisarTipoDeQuery($query, "INSERT");
    }

    private function esUpdate($query) {
        return $this->revisarTipoDeQuery($query, "UPDATE");
    }

    private function esDelete($query) {
        return $this->revisarTipoDeQuery($query, "DELETE");
    }

    private function revisarTipoDeQuery($query, $tipo) {
        return substr_count(strtoupper($query), $tipo) > 0;
    }

}
