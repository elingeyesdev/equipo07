<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Param;

class ParamSeeder extends Seeder
{
    public function run(): void
    {
        $mk = fn($g,$n,$p=null,$c=null)=> Param::firstOrCreate(
            ['grupo'=>$g,'nombre'=>$n,'parent_id'=>$p],
            ['clave'=>$c]
        );

        foreach (['Disponible','En reparación','Reservado','Vendido'] as $n)
            $mk('estado_maquinaria',$n,null,strtolower($n));

        $tractor  = $mk('tipo_maquinaria','Tractor');
        $cosecha  = $mk('tipo_maquinaria','Cosechadora');

        $jd_tr    = $mk('marca_maquinaria','John Deere',$tractor->id,'jd');
        $case_tr  = $mk('marca_maquinaria','CASE IH',$tractor->id,'case');
        $cla_co   = $mk('marca_maquinaria','CLAAS',$cosecha->id,'claas');

        $mk('modelo_maquinaria','6130M',$jd_tr->id);
        $mk('modelo_maquinaria','Magnum 250',$case_tr->id);
        $mk('modelo_maquinaria','LEXION 760',$cla_co->id);

$add = fn($g,$n,$p=null,$c=null)=> \App\Models\Param::firstOrCreate(
  ['grupo'=>$g,'nombre'=>$n,'parent_id'=>$p],
  ['clave'=>$c]
);

foreach (['Disponible','Agotado','En cosecha','Reservado'] as $n) $add('estado_organico',$n,null,strtolower($n));

$fruta   = $add('cat_organico','Fruta');
$verdura = $add('cat_organico','Verdura');
$grano   = $add('cat_organico','Grano');
$lacteo  = $add('cat_organico','Lácteo');

$add('var_organico','Manzana',$fruta->id);
$add('var_organico','Plátano',$fruta->id);
$add('var_organico','Tomate',$verdura->id);
$add('var_organico','Lechuga',$verdura->id);
$add('var_organico','Trigo',$grano->id);
$add('var_organico','Maíz',$grano->id);

foreach (['KG','CAJA','SACO','UNIDAD','LITRO'] as $u) $add('unidad',$u);

    }
}
