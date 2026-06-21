<script setup>
import { ref, watch } from 'vue';
import { useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    eventos: Array,
    localidades: Array,
    filtros: Object
});

// Filtros reactivos vinculados con v-model
const filtroNombre = ref(props.filtros.nombre || '');
const filtroLocalidad = ref(props.filtros.localidad_id || '');
const filtroFecha = ref(props.filtros.fecha || '');

watch([filtroNombre, filtroLocalidad, filtroFecha], () => {
    router.get('/eventos', {
        nombre: filtroNombre.value,
        localidad_id: filtroLocalidad.value,
        fecha: filtroFecha.value
    }, { preserveState: true, replace: true });
});

// Formulario reactivo para validar y enviar
const form = useForm({
    id: null,
    nombre_evento: '',
    fecha: '',
    id_localidad: ''
});

const editando = ref(false);

// Parámetro de Cédula 488: Sonido de confirmación sintetizado por el navegador
const reproducirSonidoExito = () => {
    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    const oscilador = audioCtx.createOscillator();
    oscilador.type = 'sine';
    oscilador.frequency.setValueAtTime(587.33, audioCtx.currentTime); // Tono alegre
    oscilador.connect(audioCtx.destination);
    oscilador.start();
    oscilador.stop(audioCtx.currentTime + 0.15);
};

const guardar = () => {
    if (editando.value) {
        form.put(`/eventos/${form.id}`, {
            onSuccess: () => resetear()
        });
    } else {
        form.post('/eventos', {
            onSuccess: () => {
                reproducirSonidoExito(); // Ejecución del sonido obligatorio
                resetear();
            }
        });
    }
};

const editar = (evento) => {
    editando.value = true;
    form.id = evento.id;
    form.nombre_evento = evento.nombre_evento;
    form.fecha = evento.fecha;
    form.id_localidad = evento.id_localidad;
};

const eliminar = (id) => {
    if (confirm('¿Estás seguro de eliminar este evento?')) {
        router.delete(`/eventos/${id}`);
    }
};

const resetear = () => {
    form.reset();
    editando.value = false;
};
</script>

<template>
    <!-- Parámetro de Cédula 488: CSS Paleta de Colores Pastel -->
    <div class="p-8 min-h-screen bg-orange-50 text-gray-700">
        <div class="max-w-5xl mx-auto space-y-6">
            <h1 class="text-3xl font-bold text-teal-800 text-center">Registro de Eventos - UNVES</h1>

            <!-- Formulario CRUD (Estilo Pastel Verde) -->
            <div class="bg-emerald-50 border border-emerald-200 p-6 rounded-2xl shadow-sm">
                <h2 class="text-lg font-semibold mb-3 text-emerald-900">{{ editando ? 'Modificar Evento' : 'Registrar Nuevo Evento' }}</h2>
                <form @submit.prevent="guardar" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-medium mb-1">Nombre del Evento</label>
                        <input v-model="form.nombre_evento" type="text" class="w-full rounded-lg border-emerald-200 bg-white p-2" required />
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1">Fecha</label>
                        <input v-model="form.fecha" type="date" class="w-full rounded-lg border-emerald-200 bg-white p-2" required />
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1">Localidad</label>
                        <select v-model="form.id_localidad" class="w-full rounded-lg border-emerald-200 bg-white p-2" required>
                            <option value="">Seleccione localidad...</option>
                            <option v-for="loc in localidades" :key="loc.id" :value="loc.id">{{ loc.nombre_localidad }}</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="bg-emerald-200 text-emerald-900 font-medium px-4 py-2 rounded-lg hover:bg-emerald-300 transition w-full">
                            {{ editando ? 'Actualizar' : 'Guardar' }}
                        </button>
                        <button v-if="editando" @click="resetear" type="button" class="bg-gray-200 p-2 rounded-lg">X</button>
                    </div>
                </form>
            </div>

            <!-- Panel de Filtros (Estilo Pastel Azul) -->
            <div class="bg-sky-50 border border-sky-200 p-4 rounded-xl flex flex-wrap gap-4 items-center">
                <span class="text-sm font-semibold text-sky-900">Búsqueda avanzada:</span>
                <input v-model="filtroNombre" type="text" placeholder="Buscar por nombre..." class="rounded-lg border-sky-200 p-1.5 text-sm" />
                <select v-model="filtroLocalidad" class="rounded-lg border-sky-200 p-1.5 text-sm">
                    <option value="">Todas las localidades</option>
                    <option v-for="loc in localidades" :key="loc.id" :value="loc.id">{{ loc.nombre_localidad }}</option>
                </select>
                <!-- Parámetro de Cédula 488: Calendario Visual (Date Picker) para filtrar -->
                <input v-model="filtroFecha" type="date" class="rounded-lg border-sky-200 p-1.5 text-sm" />
            </div>

            <!-- Tabla de visualización (Estilo Pastel Lila) -->
            <div class="bg-white border border-purple-100 rounded-xl shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-purple-50 text-purple-900">
                        <tr>
                            <th class="p-3 font-semibold">Nombre Evento</th>
                            <th class="p-3 font-semibold">Fecha</th>
                            <th class="p-3 font-semibold">Localidad</th>
                            <th class="p-3 font-semibold text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-purple-50">
                        <tr v-for="ev in eventos" :key="ev.id" class="hover:bg-purple-50/50 transition">
                            <td class="p-3">{{ ev.nombre_evento }}</td>
                            <td class="p-3">{{ ev.fecha }}</td>
                            <!-- Muestra el nombre de la localidad directamente (Indicador 5) -->
                            <td class="p-3"><span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-full font-medium">{{ ev.localidad?.nombre_localidad }}</span></td>
                            <td class="p-3 flex justify-center gap-2">
                                <button @click="editar(ev)" class="bg-amber-100 text-amber-800 px-3 py-1 rounded text-xs font-semibold hover:bg-amber-200">Editar</button>
                                <button @click="eliminar(ev.id)" class="bg-rose-100 text-rose-800 px-3 py-1 rounded text-xs font-semibold hover:bg-rose-200">Eliminar</button>
                            </td>
                        </tr>
                        <tr v-if="eventos.length === 0">
                            <td colspan="4" class="p-4 text-center text-gray-400">Sin registros que coincidan.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>