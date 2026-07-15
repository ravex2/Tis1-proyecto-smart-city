<?php

require_once __DIR__ . '/../models/Area.php';
require_once __DIR__ . '/../models/usuario.php';
require_once __DIR__ . '/../models/publicacion.php';
require_once __DIR__ . '/../models/participacion.php';
require_once __DIR__ . '/../models/sector.php';
require_once __DIR__ . '/../models/reporte.php';
require_once __DIR__ . '/../models/negocio_local.php';

class AnaliticasController {
    private Participacion $participacion;
    private Sector $sector;
    private Usuario $usuario;
    private Publicacion $publicacion;
    private Reporte $reporte;
    private NegocioLocal $negocioLocal;

    private const MESES = [
        '01' => 'Ene', '02' => 'Feb', '03' => 'Mar', '04' => 'Abr',
        '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Ago',
        '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dic',
    ];

    public function __construct() {
        $this->participacion = new Participacion();
        $this->sector = new Sector();
        $this->usuario = new Usuario();
        $this->publicacion = new Publicacion();
        $this->reporte = new Reporte();
        $this->negocioLocal = new NegocioLocal();
    }

    public function resolverRangoFechas(?string $desde, ?string $hasta): array {
        $hasta = ($hasta && $this->esFechaValida($hasta)) ? $hasta : date('Y-m-d');
        $desde = ($desde && $this->esFechaValida($desde)) ? $desde : date('Y-m-d', strtotime('-5 months', strtotime($hasta)));


        if (strtotime($desde) > strtotime($hasta)) {
            [$desde, $hasta] = [$hasta, $desde];
        }
        return [
            'desde' => $desde, 
            'hasta' => $hasta
        ];
    }

    public function obtenerResumenDashboard(): array {
        return [
            'totalUsuarios' => $this->usuario->countAll(),
            'totalPublicaciones' => $this->publicacion->countAll(),
            'totalDepartamentos' => contarAreas(),
            'emprendimientosEnRevision' => $this->negocioLocal->countByEstado('pendiente a aprobacion'),
            'reportesEnRevision' => $this->reporte->countByEstado('pendiente'),
        ];
    }

    public function obtenerDatosGraficos(string $fechaDesde, string $fechaHasta): array {
        return [
            'participacionCiudadana' => $this->obtenerParticipacionCiudadana($fechaDesde, $fechaHasta),
            'participacionPorSector' => $this->obtenerParticipacionPorSector($fechaDesde, $fechaHasta),
            'rango' => [
                'desde' => $fechaDesde,
                'hasta' => $fechaHasta,
            ],
        ];
    }

    public function obtenerParticipacionCiudadana(string $fechaDesde, string $fechaHasta): array {
        $registros = $this->participacion->getParticipacionMensual($fechaDesde, $fechaHasta);
        $mapa = [];

        foreach ($registros as $registro) {
            $mapa[$registro['periodo']] = (int) $registro['total_participantes'];
        }

        $totalCiudadanos = $this->usuario->countCiudadanos();
        $labels = [];
        $valores = [];
        $porcentajes = [];

        $inicio = new DateTime($fechaDesde);
        $inicio->modify('first day of this month');
        $fin = new DateTime($fechaHasta);
        $fin->modify('first day of next month');

        $periodo = new DatePeriod($inicio, new DateInterval('P1M'), $fin);

        foreach ($periodo as $mes) {
            
            $clave = $mes->format('Y-m');
            $participantes = $mapa[$clave] ?? 0;
            $porcentaje = $totalCiudadanos > 0
                ? round(($participantes / $totalCiudadanos) * 100, 1)
                : 0;

            $labels[] = $this->formatearMes($clave);
            $valores[] = $participantes;
            $porcentajes[] = $porcentaje;
        }
                
        return [
            'labels' => $labels,
            'valores' => $valores,
            'porcentajes' => $porcentajes,
            'totalCiudadanos' => $totalCiudadanos,
        ];
    }

    public function obtenerParticipacionPorSector(string $fechaDesde, string $fechaHasta): array {
        $registros = $this->sector->getParticipacionPorSector($fechaDesde, $fechaHasta);

        return [
            'labels' => array_column($registros, 'sector'),
            'valores' => array_map('intval', array_column($registros, 'total')),
        ];
    }

    private function esFechaValida(string $fecha): bool {
        $partes = DateTime::createFromFormat('Y-m-d', $fecha);
        return $partes && $partes->format('Y-m-d') === $fecha;
    }

    private function formatearMes(string $periodo): string {
        [$anio, $mes] = explode('-', $periodo);
        $nombreMes = self::MESES[$mes] ?? $mes;
        return $nombreMes . ' ' . $anio;
    }
}
