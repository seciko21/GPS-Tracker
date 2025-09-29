<?php declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domains\Core\Migration\MigrationAbstract;

return new class() extends MigrationAbstract {
    /**
     * @return void
     */
    public function up(): void
    {
        // Corregimos la tabla 'alarm_notification'
        Schema::table('alarm_notification', function (Blueprint $table) {
            // CORRECCIÓN 1 (Error 4108): Eliminamos la columna si ya existe con problemas
            if (Schema::hasColumn('alarm_notification', 'point')) {
                $table->dropSpatialIndex(['point']); // Eliminar índice espacial si existe
                $table->dropColumn('point');
            }
        });

        Schema::table('alarm_notification', function (Blueprint $table) {
            // CORRECCIÓN 2: Volvemos a crear la columna 'point' sin el modificador ->invisible()
            // y la hacemos 'nullable' para evitar conflictos.
            // Si Laravel no soporta ->geometry()->nullable() en tu versión, se debe usar ->point().
            $table->point('point', 4326)->nullable(true); // Cambiado a VISIBLE y nullable
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        // En el down, simplemente revertimos a lo que Laravel haría (eliminar la columna).
        Schema::table('alarm_notification', function (Blueprint $table) {
            if (Schema::hasColumn('alarm_notification', 'point')) {
                $table->dropSpatialIndex(['point']);
                $table->dropColumn('point');
            }
        });
    }
};