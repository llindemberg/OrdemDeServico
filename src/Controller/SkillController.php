<?php

declare(strict_types=1);

namespace Root\Controller;

use Root\Adapter\Connection;
use Root\Entity\Skill;
use Root\Entity\User;
use Root\Validator\SkillValidator;

class SkillController extends AbstractController
{
    private $entityManager;

    public function __construct()
    {
        $this->entityManager = Connection::getEntityManager();
    }

    public function add(): void
    {
        if (!$_POST) {
          $this->render('skill/add');
          return;
        }

        if (!SkillValidator::validateRequest($_POST)) {
          header('location: /nova-habilidade');
        }

        $skill = new Skill();
        $skill->setName($_POST['name']);
        $skill->setDescription($_POST['description']);

        $this->entityManager->persist($skill);
        $this->entityManager->flush();
        header('location: /habilidades');
    }

    public function list(): void
    {
        $skills = $this->entityManager->getRepository(Skill::class)->findAll();

        $this->render('skill/list', $skills);
    }

    public function remove(): void
    {
        $skill = $this->entityManager->getRepository(Skill::class)->find($_GET['id']);

        if (!$skill) {
            $_SESSION['errors'] = ['Habilidade não encontrado'];
            header('location: /habilidades');
            return;
        }

        $this->entityManager->remove($skill);
        $this->entityManager->flush();

        header('location: /habilidades');
    }
}
