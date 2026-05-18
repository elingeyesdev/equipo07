<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatoSanitario extends Model
{
    protected $table = 'datos_sanitarios';

    protected $fillable = [
        'user_id',
        'ganado_id',
        'destino_matadero_campo',
        'hoja_ruta_foto',
    ];

    public function ganado()
    {
        return $this->belongsTo(Ganado::class);
    }

    public function tratamientoMedicamento()
    {
        return $this->hasOne(TratamientoMedicamento::class);
    }

    public function vacunacion()
    {
        return $this->hasOne(DatoSanitarioVacunacion::class);
    }

    public function marcaAnimal()
    {
        return $this->hasOne(MarcaAnimal::class);
    }

    public function datoDueno()
    {
        return $this->hasOne(DatoDueno::class);
    }

    public function logroReconocimiento()
    {
        return $this->hasOne(LogroReconocimiento::class);
    }

    public function imagenesCertificadoCampeon()
    {
        return $this->hasMany(ImagenCertificadoCampeon::class)->orderBy('orden');
    }

    public function imagenCertificadoCampeonPrincipal()
    {
        return $this->hasOne(ImagenCertificadoCampeon::class)->oldest('orden')->oldest('id');
    }

    public function archivosArbolGenealogico()
    {
        return $this->hasMany(ArchivoArbolGenealogico::class)->orderBy('orden');
    }

    public function archivoArbolGenealogicoPrincipal()
    {
        return $this->hasOne(ArchivoArbolGenealogico::class)->oldest('orden')->oldest('id');
    }

    public function getVacunaAttribute($value)
    {
        return $this->vacunacion?->vacuna ?? $value;
    }

    public function getVacunadoFiebreAftosaAttribute($value)
    {
        return $this->vacunacion?->vacunado_fiebre_aftosa ?? (bool) $value;
    }

    public function getVacunadoAntirabicaAttribute($value)
    {
        return $this->vacunacion?->vacunado_antirabica ?? (bool) $value;
    }

    public function getCertificadoImagenAttribute($value)
    {
        return $this->vacunacion?->imagenPrincipal?->ruta ?? $value;
    }

    public function getCertificadoCampeonImagenAttribute($value)
    {
        return $this->imagenCertificadoCampeonPrincipal?->ruta ?? $value;
    }

    public function getArbolGenealogicoAttribute($value)
    {
        return $this->archivoArbolGenealogicoPrincipal?->ruta ?? $value;
    }

    public function getTratamientoAttribute($value)
    {
        return $this->tratamientoMedicamento?->tratamiento ?? $value;
    }

    public function getMedicamentoAttribute($value)
    {
        return $this->tratamientoMedicamento?->medicamento ?? $value;
    }

    public function getFechaAplicacionAttribute($value)
    {
        return $this->tratamientoMedicamento?->fecha_aplicacion ?? $value;
    }

    public function getProximaFechaAttribute($value)
    {
        return $this->tratamientoMedicamento?->proxima_fecha ?? $value;
    }

    public function getVeterinarioAttribute($value)
    {
        return $this->tratamientoMedicamento?->veterinario ?? $value;
    }

    public function getObservacionesAttribute($value)
    {
        return $this->tratamientoMedicamento?->observaciones ?? $value;
    }

    public function getMarcaGanadoAttribute($value)
    {
        return $this->marcaAnimal?->marca_ganado ?? $value;
    }

    public function getSenalNumeroAttribute($value)
    {
        return $this->marcaAnimal?->senal_numero ?? $value;
    }

    public function getMarcaGanadoFotoAttribute($value)
    {
        return $this->marcaAnimal?->imagenPrincipal?->ruta ?? $value;
    }

    public function getNombreDuenoAttribute($value)
    {
        return $this->datoDueno?->nombre_dueno ?? $value;
    }

    public function getCarnetDuenoFotoAttribute($value)
    {
        return $this->datoDueno?->carnet_dueno_foto ?? $value;
    }

    public function getLogroCampeonRazaAttribute($value)
    {
        return $this->logroReconocimiento?->bellezaEstructura?->logro_campeon_raza ?? (bool) $value;
    }

    public function getLogroGranCampeonMachoAttribute($value)
    {
        return $this->logroReconocimiento?->bellezaEstructura?->logro_gran_campeon_macho ?? (bool) $value;
    }

    public function getLogroGranCampeonHembraAttribute($value)
    {
        return $this->logroReconocimiento?->bellezaEstructura?->logro_gran_campeon_hembra ?? (bool) $value;
    }

    public function getLogroMejorUbreAttribute($value)
    {
        return $this->logroReconocimiento?->bellezaEstructura?->logro_mejor_ubre ?? (bool) $value;
    }

    public function getLogroCampeonaLitrosDiaAttribute($value)
    {
        return $this->logroReconocimiento?->produccionLeche?->logro_campeona_litros_dia ?? (bool) $value;
    }

    public function getLogroMejorLactanciaAttribute($value)
    {
        return $this->logroReconocimiento?->produccionLeche?->logro_mejor_lactancia ?? (bool) $value;
    }

    public function getLogroMejorCalidadLecheAttribute($value)
    {
        return $this->logroReconocimiento?->produccionLeche?->logro_mejor_calidad_leche ?? (bool) $value;
    }

    public function getLogroMejorNovilloAttribute($value)
    {
        return $this->logroReconocimiento?->produccionCarne?->logro_mejor_novillo ?? (bool) $value;
    }

    public function getLogroGranCampeonCarneAttribute($value)
    {
        return $this->logroReconocimiento?->produccionCarne?->logro_gran_campeon_carne ?? (bool) $value;
    }

    public function getLogroMejorSementalAttribute($value)
    {
        return $this->logroReconocimiento?->produccionCarne?->logro_mejor_semental ?? (bool) $value;
    }

    public function getLogroMejorMadreAttribute($value)
    {
        return $this->logroReconocimiento?->reproduccionLogro?->logro_mejor_madre ?? (bool) $value;
    }

    public function getLogroMejorPadreAttribute($value)
    {
        return $this->logroReconocimiento?->reproduccionLogro?->logro_mejor_padre ?? (bool) $value;
    }

    public function getLogroMejorFertilidadAttribute($value)
    {
        return $this->logroReconocimiento?->reproduccionLogro?->logro_mejor_fertilidad ?? (bool) $value;
    }
}
