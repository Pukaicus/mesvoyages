<?php

namespace App\Tests;

use App\Entity\Voyage;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class VoyageTest extends KernelTestCase
{
    public function getEntity(): Voyage
    {
        // On utilise une date qui est clairement passée (2 jours avant)
        // pour ne jamais bloquer les tests de note ou de température.
        return (new Voyage())
            ->setTitre("Un super voyage")
            ->setDescription("Une description assez longue pour passer les validations")
            ->setVille("Paris")
            ->setPays("France")
            ->setNote(10)
            ->setTempmin(15)
            ->setTempmax(25)
            ->setDatecreation((new \DateTime())->modify('-2 days'));
    }

    public function assertHasErrors(Voyage $voyage, int $number = 0)
    {
        self::bootKernel();
        $errors = self::getContainer()->get('validator')->validate($voyage);
        
        $messages = [];
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testNoteValide()
    {
        $this->assertHasErrors($this->getEntity()->setNote(10), 0);
        $this->assertHasErrors($this->getEntity()->setNote(21), 1);
        $this->assertHasErrors($this->getEntity()->setNote(-1), 1);
    }

    public function testTempMaxValide()
    {
        $voyage = $this->getEntity()->setTempmin(10);
        $this->assertHasErrors($voyage->setTempmax(20), 0);
        $this->assertHasErrors($voyage->setTempmax(5), 1);
        $this->assertHasErrors($voyage->setTempmax(10), 1);
    }

    public function testDateCreation()
    {
        // 1. Test date passée (hier) -> OK
        $hier = (new \DateTime())->modify('-1 day');
        $this->assertHasErrors($this->getEntity()->setDatecreation($hier), 0);

        // 2. Test AUJOURD'HUI
        // On force l'heure à 00:00:00 pour correspondre exactement à la règle "today" de Symfony
        $aujourdhui = new \DateTime('today'); 
        $this->assertHasErrors($this->getEntity()->setDatecreation($aujourdhui), 0);

        // 3. Test FUTUR (demain) -> DOIT ÉCHOUER (1 erreur)
        $demain = (new \DateTime())->modify('+1 day');
        $this->assertHasErrors($this->getEntity()->setDatecreation($demain), 1);
    }
}