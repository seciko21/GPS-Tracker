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
        // ...
        Schema::table('alarm_notification', function (Blueprint $table) {
            // CORRECCIÓN: Se comentan las líneas que causan el ERROR 1901 de función GIS incompatible
            // $table->double('latitude')->storedAs('round(st_latitude(`point`), 5)');
            // $table->double('longitude')->storedAs('round(st_longitude(`point`), 5)');
        });
        // ... (Corregir tablas position, refuel, etc., de la misma manera si existen en este archivo)
    }

    // ... (down function)
};