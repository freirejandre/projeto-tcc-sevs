<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAluminumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aluminums', function (Blueprint $table) {
            $table->increments('id');
            $table->string('perfil');
            $table->string('descricao')->nullable();
            $table->double('medida')->nullable();
            $table->integer('qtd');
            $table->double('peso')->nullable();
            $table->double('preco')->nullable();
            $table->string('tipo_medida');
            $table->integer('espessura')->nullable();
            $table->string('imagem')->nullable();
            $table->integer('is_modelo');
            $table->integer('maluminum_id')->nullable();
            $table->integer('categoria_aluminio_id')->unsigned();
            $table->integer('product_id')->nullable()->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('categoria_aluminio_id')->references('id')->on('categories')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('m_product_aluminum', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('m_produto_id')->unsigned();
            $table->integer('aluminio_id')->unsigned();
            $table->foreign('m_produto_id')->references('id')->on('m_products')->onDelete('cascade');
            $table->foreign('aluminio_id')->references('id')->on('aluminums')->onDelete('cascade');
        });

        Schema::create('provider_aluminum', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('provider_id')->unsigned();
            $table->integer('aluminio_id')->unsigned();
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');
            $table->foreign('aluminio_id')->references('id')->on('aluminums')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('provider_aluminum');
        Schema::dropIfExists('m_product_aluminum');
        Schema::dropIfExists('aluminums');
    }
}
