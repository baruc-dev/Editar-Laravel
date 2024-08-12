<?php

namespace App\Livewire;

use App\Models\Categoria;
use App\Models\Salario;
use App\Models\Vacante;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class EditarVacante extends Component
{
    public $vacante_id;
    //estos public son el wire:model (name)
    
    public $titulo;
    public $salario;
    public $categoria;
    public $ultimodia;
    public $descripcion;
    public $imagen;
    public $imagen_nueva;

    protected $rules =
    [
        'titulo' => 'required|string',
        'salario' => 'required',
        'categoria' => 'required',
        'ultimodia' => 'required',
        'descripcion' => 'required',
        'imagen_nueva' => 'nullable|image|max:1024'
        

    ];


    use WithFileUploads;



    //Vacante es el Modelo y $vacante es la variable que le pasamos desde la otra vista-> :vacantx="$vacante"
    public function mount(Vacante $vacantx)
    {
        $this->vacante_id= $vacantx->id;
        $this->titulo = $vacantx->titulo;
        $this->salario = $vacantx->salario_id;
        $this->categoria = $vacantx->categoria_id;
        $this->ultimodia = $vacantx->ultimodia;
        $this->descripcion = $vacantx->descripcion;
        $this->imagen = $vacantx->imagen;

    }


    public function editarVacante()
    {
        $datos = $this->validate();

        //revisando si hay nueva imagen

        if($this->imagen_nueva)
        {

            //si hay algo en imagen_nueva entonces lo guardamos, pero eso nos daria la ruta muy larga
            $imagen = $this->imagen_nueva->store('public/vacantes');

            //asi que le asignamos a $datos el valor de la imagen pero quitandole la ruta larga y dejando solo el nombre
            $datos['imagen'] = str_replace('public/vacantes','',$imagen);
        }


        //encontrando la vacante a editar

        $vacante = Vacante::find($this->vacante_id);

        //asignar valores

        $vacante->titulo = $datos['titulo'];
        $vacante->salario_id = $datos['salario'];
        $vacante->categoria_id = $datos['categoria'];
        $vacante->ultimodia = $datos['ultimodia'];
        $vacante->descripcion = $datos['descripcion'];
        $vacante->imagen = $datos['imagen']?? $vacante->imagen;
        

        //guardar la vacante

        $vacante->save();

        //redireccionar

        
        return redirect()->route('vacantes.index')->with('mensaje','Actualizado Correctamente');
    }


    public function render()
    {

        $salarios = Salario::all();
        $categorias = Categoria::all();
        return view('livewire.editar-vacante',[
            'salarios' => $salarios,
            'categorias' => $categorias
        ]);
    }
}
