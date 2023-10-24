<?php
require_once __DIR__ . '/../../src/dao/DaoAppli.php';
use PHPUnit\Framework\TestCase;

class DaoAppliTest extends TestCase
{
    private $daoAppli;

    protected function setUp(): void
    {
        // Initialisation : créez une instance de DaoAppli
        // et configurez tout ce dont vous avez besoin.
        $this->daoAppli = new DaoAppli();
    }

    public function testGetAdminByNom()
    {
        // Ici, vous pouvez simuler une entrée et tester la sortie
        $result = $this->daoAppli->getAdminByNom('nom_test');
        $this->assertIsArray($result);
        // Autres assertions selon vos besoins...
    }

    public function testModifBackgroundTitre()
    {
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);
        
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->willReturn($stmtMock);
        
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->willReturn(true);
        
        $daoAppliReflection = new ReflectionClass(DaoAppli::class);
        $dbProperty = $daoAppliReflection->getProperty('db');
        $dbProperty->setAccessible(true);
        $dbProperty->setValue($this->daoAppli, $pdoMock);
        
        $this->daoAppli->modifBackgroundTitre('titre', 'background', 'color', 'font-family', 'font-large', 'font-medium', 'font-small');
    }

    public function testGetTitreCouleurImage() {
        // 1. Création des mocks
        $pdoMock = $this->createMock(PDO::class);
        $stmtMock = $this->createMock(PDOStatement::class);
    
        // 2. Configuration des mocks
        // Simuler la récupération des données
        $mockData = [
            [
                'titre' => 'test',
                'titre_color' => '#FFF',
                'titre_font_family' => 'montserrat',
                'titre_font_size_grand_ecran' => '80px',
                'titre_font_size_moyen_ecran' => '50px',
                'titre_font_size_petit_ecran' => '30px',
                'bkgd_color' => 'black',
                'nom_image' => "bibi",
                'url' => "123bibi",
                'id_image' => "320"
            ],
            // ... ajouter d'autres données mockées si nécessaire ...
        ];
    
        $pdoMock->expects($this->once())
                ->method('query')
                ->with(Requete::REQ_TITRE_COULEUR_IMAGE)
                ->willReturn($stmtMock);
    
        $stmtMock->expects($this->exactly(count($mockData) + 1))  // +1 pour le dernier appel qui retourne false
                 ->method('fetch')
                 ->will($this->onConsecutiveCalls(...$mockData, false));
    
        // 3. Injection des mocks dans DaoAppli
        $daoAppli = new DaoAppli();
        $reflection = new ReflectionClass(DaoAppli::class);
        $property = $reflection->getProperty('db');
        $property->setAccessible(true);
        $property->setValue($daoAppli, $pdoMock);
    
        // 4. Appel de la méthode
        $result = $daoAppli->getTitreCouleurImage();
    
        // 5. Vérification du résultat
        $this->assertEquals($mockData, $result);
    }
    // Continuez avec d'autres méthodes de test pour chaque méthode de DaoAppli

    protected function tearDown(): void
    {
        // Libérez les ressources ou effectuez d'autres opérations de nettoyage si nécessaire.
        $this->daoAppli = null;
    }
}