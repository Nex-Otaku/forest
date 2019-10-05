<?php

namespace app;

class Application
{
    public function run(): void
    {
        $response = $this->runAction();
        echo $response;
    }

    private function runAction(): string
    {
        $route = $this->getRoute();
        if ($route === '/') {
            return $this->actionMainPage();
        }
        $this->setNotFoundHeader();
        return $this->notFoundResponse();
    }

    private function notFoundResponse(): string
    {
        return 'Ой. Страница не найдена. Нам очень жаль :(';
    }

    private function getRoute(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    private function actionMainPage()
    {
        return 'Главная страница';
    }

    private function setNotFoundHeader(): void
    {
        http_response_code(404);
    }
}