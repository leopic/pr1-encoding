<?php

namespace App\Controllers;

use App\Services\WordService;

class WordController {

    private $service;

    public function __construct() {
        $this->service = new WordService();
    }

    public function get($request) {
        $id = $request->getAttribute("id", null);
        return $this->service->get($id);
    }

    public function add($request) {
        $formData = $request->getParsedBody();
        return $this->service->add($formData['word']);
    }

    public function wordList() {
        return $this->service->wordList();
    }

}
