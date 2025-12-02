<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $nosotros = [
            [
                'titulo' => 'Nuestra Experiencia',
                'descripcion' => 'Nuestros barberos están en constante actualización, perfeccionando sus técnicas y aprendiendo las últimas tendencias en cortes y afeitados.',
                'icono' => 'graduation-cap'
            ],
            [
                'titulo' => 'Atención Personalizada',
                'descripcion' => 'Nos enfocamos en entender tus necesidades y preferencias para ofrecerte un servicio que refleje tu estilo único.',
                'icono' => 'user-check'
            ],
            [
                'titulo' => 'Pasión por la Barbería',
                'descripcion' => 'Cada miembro de nuestro equipo comparte una verdadera pasión por la barbería, lo que se traduce en atención al detalle y compromiso con la calidad.',
                'icono' => 'heart'
            ],
            [
                'titulo' => 'Ambiente Acogedor',
                'descripcion' => 'Desde el momento en que entras, te recibimos en un espacio cálido y profesional, donde puedes relajarte y desconectar del ajetreo diario.',
                'icono' => 'home'
            ],
            [
                'titulo' => 'Conexión y Confianza',
                'descripcion' => 'Establecemos una relación cercana con nuestros clientes, creando un ambiente de confianza donde te sientes cómodo para expresar tus deseos.',
                'icono' => 'handshake'
            ],
            [
                'titulo' => 'Productos Premium',
                'descripcion' => 'Utilizamos solo los mejores productos del mercado para garantizar resultados excepcionales y cuidado de tu piel y cabello.',
                'icono' => 'spa'
            ]
        ];

        return view('home', compact('nosotros'));
    }
}