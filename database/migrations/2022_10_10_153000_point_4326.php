<?php declare(strict_types=1);

use App\Domains\CoreApp\Migration\MigrationAbstract;

return new class() extends MigrationAbstract {
    /**
     * @return void
     */
    public function up(): void
    {
        // CORRECCIÓN CLAVE: COMENTAMOS O ELIMINAMOS LA LLAMADA A $this->tables();
        // Esto evita que se ejecute el código SQL incompatible (ALTER TABLE ... SRID 4326)
        // ya que la migración base ya creó la columna.
        // $this->tables(); 
    }

    /**
     * @return void
     */
    protected function tables(): void
    {
        // El contenido original (que contenía los ALTER TABLE con sintaxis fallida)
        // se anula al no llamarse desde up().
        // Si no se puede comentar la llamada a $this->tables(), 
        // vacía el contenido de esta función:
        /*
        $this->db()->unprepared('ALTER TABLE `city` MODIFY COLUMN `point` POINT NOT NULL SRID 4326;');
        $this->db()->unprepared('ALTER TABLE `position` MODIFY COLUMN `point` POINT NOT NULL SRID 4326;');
        // ... etc.
        */
    }
};