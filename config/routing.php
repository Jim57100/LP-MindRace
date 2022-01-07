<?php


use Framework\Routing\Route;
use App\Controller\GameController\GameController;
use App\Controller\AdminController\AdminController;
use App\Controller\PageController\HomePageController;
use App\Controller\PageController\LoginPageController;
use App\Controller\PageController\QrCodePageController;
use App\Controller\PageController\SwitchPageController;
use App\Controller\AdminController\PlayersAddController;
use App\Controller\PageController\RegisterPageController;
use App\Controller\AdminController\PlayersAdminController;
use App\Controller\AdminController\PlayersUpdateController;
use App\Controller\PageController\InvitationPageController;
use App\Controller\AdminController\QuestionsAdminController;
use App\Controller\AdminController\QuestionsUpdateController;



return [
    new Route('GET', '/admin', AdminController::class),
    new Route(['GET', 'POST'], '/admin/players', PlayersAdminController::class),
    new Route(['GET', 'POST'], '/admin/playersUpdate', PlayersUpdateController::class),
    new Route(['GET', 'POST'], '/admin/playersAdd', PlayersAddController::class),
    new Route(['GET', 'POST'], '/admin/questions', QuestionsAdminController::class),
    new Route(['GET', 'POST'], '/admin/questionsUpdate', QuestionsUpdateController::class),
    new Route('GET', '/game', GameController::class),
    new Route('GET', '/', HomePageController::class),
    new Route('GET', '/switch', SwitchPageController::class),
    new Route(['GET', 'POST'], '/invitation', InvitationPageController::class),
    new Route(['GET', 'POST'], '/qrcode', QrCodePageController::class),
    new Route(['GET', 'POST'], '/login', LoginPageController::class),
    new Route(['GET', 'POST'], '/register', RegisterPageController::class),

    // new Route('GET', '/game/question/(id)', QuestionGame::class),
];
