<?php

namespace App\Services;

class WordService {

    private $storage;

    public function __construct() {
        $this->storage = new StorageService();
    }

    public function get($id) {
        $result = [];
        $query = "SELECT id, word FROM special_characters WHERE id = :id";
        $params = [":id" => $id];
        $selectResult = $this->storage->query($query, $params);

        if (count($selectResult["data"]) > 0) {
            $word = $selectResult["data"][0];
            $result["message"] = "Se encontró la palabra.";

            $result["data"] = [
                "id" => $word["id"],
                "word" => $word["word"]
            ];
        } else {
            $result["message"] = "No se encontró la palabra.";
            $result["error"] = true;
        }

        return $result;
    }

    public function wordList() {
        $query = "SELECT id, word FROM special_characters";
        $selectResult = $this->storage->query($query, $params);

        if (count($selectResult["data"]) > 0) {
            $words = $selectResult["data"];
            $result["message"] = "Se encontraron palabras.";

            foreach ($words as $word) {
                $result["data"][] = [
                    "id" => $word["id"],
                    "word" => $word["word"]
                ];
            }
        } else {
            $result["message"] = "No se encontraron palabras.";
            $result["error"] = true;
        }

        return $result;
    }

    public function add($word) {
        $query = "INSERT INTO special_characters (word) VALUES(:word);";
        $params = [":word" => $word];
        return $this->storage->query($query, $params);
    }

}
