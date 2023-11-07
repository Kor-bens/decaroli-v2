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
        $result = $this->daoAppli->recuperationUser('nom_test','dzadazd'); 
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

    
    public function testTraitementImage(){
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

        $this->daoAppli->traitementImage('url', 'nom_image', 'id_page');
    }

    public function testSupprimerImage(){
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

        $this->daoAppli->supprimerImage('idImage');
    }

    public function testModifierImage(){
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

        $this->daoAppli->modifierImage('nom_image', 'url' ,'id_image');
    }
    protected function tearDown(): void
    {
        // Libérez les ressources ou effectuez d'autres opérations de nettoyage si nécessaire.
        $this->daoAppli = null;
    }
}