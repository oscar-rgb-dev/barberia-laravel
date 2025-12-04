@extends('layouts.app')

@section('title', 'Calificar Servicio - Barbería')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Calificar Servicio</h5>
                </div>
                <div class="card-body">
                    <!-- Información de la cita -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">Detalles de la cita</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Fecha:</strong> {{ $cita->fecha_hora->format('d/m/Y H:i') }}</p>
                                    <p class="mb-1"><strong>Barbero:</strong> {{ $cita->barbero->nombre ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Servicio:</strong> {{ $cita->servicio->nombre ?? 'N/A' }}</p>
                                    <p class="mb-1"><strong>Total:</strong> ${{ number_format($cita->total, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario de calificación -->
                    <form action="{{ route('calificacion.guardar', $cita->id) }}" method="POST">
                        @csrf
                        
                        <div class="form-group mb-4">
                            <label for="calificacion" class="form-label fw-bold mb-3">
                                ¿Cómo calificarías nuestro servicio?
                            </label>
                            
                            <div class="rating-stars text-center mb-3">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="star{{ $i }}" name="calificacion" value="{{ $i }}" 
                                           class="rating-input" {{ old('calificacion') == $i ? 'checked' : '' }}>
                                    <label for="star{{ $i }}" class="star-label">
                                        <i class="far fa-star"></i>
                                    </label>
                                @endfor
                            </div>
                            
                            <div class="text-center text-muted" id="rating-description">
                                Selecciona una calificación
                            </div>
                            
                            @error('calificacion')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="comentario" class="form-label fw-bold">Comentarios (Opcional)</label>
                            <textarea name="comentario" id="comentario" rows="4" 
                                      class="form-control" 
                                      placeholder="¿Algo que quieras comentar sobre el servicio?">{{ old('comentario') }}</textarea>
                            <small class="form-text text-muted">
                                Máximo 500 caracteres
                            </small>
                            @error('comentario')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('citas.historial') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-star"></i> Enviar Calificación
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating-stars {
    font-size: 2.5rem;
    direction: rtl;
    unicode-bidi: bidi-override;
}

.rating-input {
    display: none;
}

.star-label {
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
    padding: 0 5px;
}

.star-label:hover,
.star-label:hover ~ .star-label {
    color: #ffc107;
}

.rating-input:checked ~ .star-label {
    color: #ffc107;
}

.star-label .fa-star {
    transition: all 0.2s;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ratingInputs = document.querySelectorAll('.rating-input');
    const ratingDescription = document.getElementById('rating-description');
    
    const descriptions = {
        1: 'Muy Malo - Lamentamos tu experiencia',
        2: 'Malo - Necesitamos mejorar',
        3: 'Regular - Aceptable, pero podemos mejorar',
        4: 'Bueno - Estamos contentos de que te gustó',
        5: 'Excelente - ¡Gracias por tu confianza!'
    };
    
    ratingInputs.forEach(input => {
        input.addEventListener('change', function() {
            const value = this.value;
            ratingDescription.textContent = descriptions[value];
            
            // Actualizar todas las estrellas
            document.querySelectorAll('.star-label .fa-star').forEach((star, index) => {
                const starNumber = 5 - index;
                if (starNumber <= value) {
                    star.classList.remove('far');
                    star.classList.add('fas');
                    star.style.color = '#ffc107';
                } else {
                    star.classList.remove('fas');
                    star.classList.add('far');
                    star.style.color = '#ddd';
                }
            });
        });
        
        // Simular hover para previsualización
        const label = document.querySelector(`label[for="star${input.value}"]`);
        label.addEventListener('mouseover', function() {
            const hoverValue = this.htmlFor.replace('star', '');
            
            document.querySelectorAll('.star-label .fa-star').forEach((star, index) => {
                const starNumber = 5 - index;
                if (starNumber <= hoverValue) {
                    star.classList.remove('far');
                    star.classList.add('fas');
                    star.style.color = '#ffcc00';
                } else {
                    star.classList.remove('fas');
                    star.classList.add('far');
                    star.style.color = '#ddd';
                }
            });
            
            ratingDescription.textContent = `Previsualización: ${descriptions[hoverValue]}`;
        });
        
        label.addEventListener('mouseout', function() {
            const checkedInput = document.querySelector('.rating-input:checked');
            if (checkedInput) {
                checkedInput.dispatchEvent(new Event('change'));
            } else {
                document.querySelectorAll('.star-label .fa-star').forEach(star => {
                    star.classList.remove('fas');
                    star.classList.add('far');
                    star.style.color = '#ddd';
                });
                ratingDescription.textContent = 'Selecciona una calificación';
            }
        });
    });
    
    // Establecer inicialmente si hay valor anterior
    const checkedInput = document.querySelector('.rating-input:checked');
    if (checkedInput) {
        checkedInput.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
@endsection