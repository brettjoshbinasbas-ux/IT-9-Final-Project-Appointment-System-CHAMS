    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Schema\ForeignIdColumnDefinition;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::create('appointments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('client_id')->constrained()->onDelete('cascade');
                $table->foreignId('staff_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
                $table->string('service_type');
                $table->date('appointment_date');
                $table->time('appointment_time');
                $table->enum('status', [
                    'scheduled',
                    'confirmed',
                    'completed',
                    'cancelled',
                    'no_show',
                ])->default('scheduled');
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('appointments');
        }
    };
