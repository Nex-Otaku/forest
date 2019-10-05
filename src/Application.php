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
        if ($route === '/feed') {
            return $this->actionNews();
        }
        return $this->actionNotFound();
    }

    private function actionMainPage(): string
    {
        return 'Главная страница';
    }

    private function actionNews(): string
    {
        return 'Общая лента новостей';
    }

    private function actionNotFound(): string
    {
        $this->setNotFoundHeader();
        return 'Ой. Страница не найдена. Нам очень жаль :(';
    }

    private function setNotFoundHeader(): void
    {
        http_response_code(404);
    }

    private function getRoute(): string
    {
        return $_SERVER['REQUEST_URI'];
    }
}