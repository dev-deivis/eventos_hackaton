<?php

namespace App\Exports;

use App\Models\Participante;
use App\Models\Equipo;
use App\Models\Evaluacion;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReportesExport implements WithMultipleSheets
{
    protected $eventoId;

    public function __construct($eventoId = null)
    {
        $this->eventoId = $eventoId;
    }

    public function sheets(): array
    {
        return [
            new EstadisticasSheet($this->eventoId),
            new ParticipantesSheet($this->eventoId),
            new EquiposSheet($this->eventoId),
            new CarrerasSheet($this->eventoId),
            new RolesSheet($this->eventoId),
        ];
    }
}

// Hoja 1: Estadísticas Generales
class EstadisticasSheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $eventoId;

    public function __construct($eventoId = null)
    {
        $this->eventoId = $eventoId;
    }

    public function collection()
    {
        $totalParticipantes = $this->getTotalParticipantes();
        $equiposFormados = $this->getEquiposFormados();
        $promedioMiembros = $this->getPromedioMiembros();
        $tasaFinalizacion = $this->getTasaFinalizacion();
        $equiposTerminaron = $this->getEquiposTerminaron();
        $puntuacionPromedio = $this->getPuntuacionPromedio();
        $puntuacionMaxima = $this->getPuntuacionMaxima();

        return collect([
            ['Total Participantes', $totalParticipantes],
            ['Equipos Formados', $equiposFormados],
            ['Promedio de Miembros', $promedioMiembros],
            ['Tasa de Finalización (%)', $tasaFinalizacion],
            ['Equipos que Terminaron', $equiposTerminaron],
            ['Puntuación Promedio', $puntuacionPromedio],
            ['Puntuación Máxima', $puntuacionMaxima],
        ]);
    }

    public function headings(): array
    {
        return ['Métrica', 'Valor'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
        ];
    }

    public function title(): string
    {
        return 'Estadísticas Generales';
    }

    private function getTotalParticipantes()
    {
        if ($this->eventoId) {
            return DB::table('participantes')
                ->join('equipo_participante', 'participantes.id', '=', 'equipo_participante.participante_id')
                ->join('equipos', 'equipo_participante.equipo_id', '=', 'equipos.id')
                ->where('equipos.evento_id', $this->eventoId)
                ->distinct()
                ->count('participantes.id');
        }
        return Participante::count();
    }

    private function getEquiposFormados()
    {
        if ($this->eventoId) {
            return Equipo::where('evento_id', $this->eventoId)->count();
        }
        return Equipo::count();
    }

    private function getPromedioMiembros()
    {
        $query = DB::table('equipos')
            ->leftJoin('equipo_participante', 'equipos.id', '=', 'equipo_participante.equipo_id')
            ->select('equipos.id', DB::raw('COUNT(equipo_participante.participante_id) as miembros_count'))
            ->groupBy('equipos.id');

        if ($this->eventoId) {
            $query->where('equipos.evento_id', $this->eventoId);
        }

        $resultados = $query->get();
        
        if ($resultados->isEmpty()) {
            return 0;
        }

        return round($resultados->avg('miembros_count'), 1);
    }

    private function getTasaFinalizacion()
    {
        $query = Equipo::query();
        
        if ($this->eventoId) {
            $query->where('evento_id', $this->eventoId);
        }

        $total = $query->count();
        
        if ($total == 0) {
            return 0;
        }

        $conProyecto = Equipo::query()
            ->when($this->eventoId, function($q) {
                $q->where('evento_id', $this->eventoId);
            })
            ->whereHas('proyecto')
            ->count();
        
        return round(($conProyecto / $total) * 100, 1);
    }

    private function getEquiposTerminaron()
    {
        $query = Equipo::query();
        
        if ($this->eventoId) {
            $query->where('evento_id', $this->eventoId);
        }

        return $query->whereHas('proyecto')->count();
    }

    private function getPuntuacionPromedio()
    {
        $query = Evaluacion::query();
        
        if ($this->eventoId) {
            $query->whereHas('equipo', function($q) {
                $q->where('evento_id', $this->eventoId);
            });
        }

        $promedio = $query->avg('calificacion_total');
        return $promedio ? round($promedio, 1) : 0;
    }

    private function getPuntuacionMaxima()
    {
        $query = Evaluacion::query();
        
        if ($this->eventoId) {
            $query->whereHas('equipo', function($q) {
                $q->where('evento_id', $this->eventoId);
            });
        }

        $maxima = $query->max('calificacion_total');
        return $maxima ? round($maxima, 1) : 100;
    }
}

// Hoja 2: Participantes
class ParticipantesSheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $eventoId;

    public function __construct($eventoId = null)
    {
        $this->eventoId = $eventoId;
    }

    public function collection()
    {
        $query = DB::table('participantes')
            ->join('users', 'participantes.user_id', '=', 'users.id')
            ->join('carreras', 'participantes.carrera_id', '=', 'carreras.id')
            ->select('users.name', 'users.email', 'carreras.nombre as carrera', 'participantes.no_control', 'participantes.semestre');

        if ($this->eventoId) {
            $query->join('equipo_participante', 'participantes.id', '=', 'equipo_participante.participante_id')
                ->join('equipos', 'equipo_participante.equipo_id', '=', 'equipos.id')
                ->where('equipos.evento_id', $this->eventoId)
                ->distinct();
        }

        return $query->get();
    }

    public function headings(): array
    {
        return ['Nombre', 'Email', 'Carrera', 'No. Control', 'Semestre'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Participantes';
    }
}

// Hoja 3: Equipos
class EquiposSheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $eventoId;

    public function __construct($eventoId = null)
    {
        $this->eventoId = $eventoId;
    }

    public function collection()
    {
        $query = DB::table('equipos')
            ->leftJoin('equipo_participante', 'equipos.id', '=', 'equipo_participante.equipo_id')
            ->leftJoin('proyectos', 'equipos.id', '=', 'proyectos.equipo_id')
            ->select(
                'equipos.nombre',
                DB::raw('COUNT(DISTINCT equipo_participante.participante_id) as miembros'),
                DB::raw("CASE WHEN proyectos.id IS NOT NULL THEN 'Sí' ELSE 'No' END as proyecto_entregado"),
                'equipos.estado'
            )
            ->groupBy('equipos.id', 'equipos.nombre', 'equipos.estado', 'proyectos.id');

        if ($this->eventoId) {
            $query->where('equipos.evento_id', $this->eventoId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return ['Equipo', 'Miembros', 'Proyecto Entregado', 'Estado'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Equipos';
    }
}

// Hoja 4: Participación por Carrera
class CarrerasSheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $eventoId;

    public function __construct($eventoId = null)
    {
        $this->eventoId = $eventoId;
    }

    public function collection()
    {
        $query = DB::table('participantes')
            ->join('carreras', 'participantes.carrera_id', '=', 'carreras.id')
            ->select('carreras.nombre as carrera', DB::raw('COUNT(participantes.id) as total'));

        if ($this->eventoId) {
            $query->join('equipo_participante', 'participantes.id', '=', 'equipo_participante.participante_id')
                ->join('equipos', 'equipo_participante.equipo_id', '=', 'equipos.id')
                ->where('equipos.evento_id', $this->eventoId);
        }

        $resultados = $query
            ->groupBy('carreras.nombre')
            ->orderByDesc('total')
            ->get();

        $total = $resultados->sum('total');

        return $resultados->map(function($item) use ($total) {
            return [
                'carrera' => $item->carrera,
                'total' => $item->total,
                'porcentaje' => $total > 0 ? round(($item->total / $total) * 100, 1) . '%' : '0%'
            ];
        });
    }

    public function headings(): array
    {
        return ['Carrera', 'Total', 'Porcentaje'];
    }

    public function styles(Worksheet $sheet)
    {
        // Estilos del header
        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5']
            ],
            'font' => ['color' => ['rgb' => 'FFFFFF']]
        ]);
        
        // Auto-width
        $sheet->getColumnDimension('A')->setWidth(40);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        
        return [];
    }

    public function title(): string
    {
        return 'Por Carrera';
    }
}

// Hoja 5: Distribución de Roles
class RolesSheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $eventoId;

    public function __construct($eventoId = null)
    {
        $this->eventoId = $eventoId;
    }

    public function collection()
    {
        $query = DB::table('equipo_participante')
            ->join('perfiles', 'equipo_participante.perfil_id', '=', 'perfiles.id')
            ->select('perfiles.nombre as rol', DB::raw('COUNT(*) as total'));

        if ($this->eventoId) {
            $query->join('equipos', 'equipo_participante.equipo_id', '=', 'equipos.id')
                ->where('equipos.evento_id', $this->eventoId);
        }

        $resultados = $query
            ->groupBy('perfiles.nombre')
            ->orderByDesc('total')
            ->get();

        $total = $resultados->sum('total');

        return $resultados->map(function($item) use ($total) {
            return [
                'rol' => $item->rol,
                'total' => $item->total,
                'porcentaje' => $total > 0 ? round(($item->total / $total) * 100, 1) . '%' : '0%'
            ];
        });
    }

    public function headings(): array
    {
        return ['Rol', 'Total', 'Porcentaje'];
    }

    public function styles(Worksheet $sheet)
    {
        // Estilos del header
        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'EC4899']
            ],
            'font' => ['color' => ['rgb' => 'FFFFFF']]
        ]);
        
        // Auto-width
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        
        return [];
    }

    public function title(): string
    {
        return 'Roles';
    }
}
