const API_URL = 'http://127.0.0.1/tareas';

let tareas = []; // Guardamos las tareas del fetch inicial

const tareasContainer = document.getElementById("tareasContainer");
const modalCrear = document.getElementById("modalCrear");
const modalTitulo = document.getElementById("modalTitulo");
const btnAbrirModal = document.getElementById("btnAbrirModal");
const btnCerrarModal = document.getElementById("btnCerrarModal");
const formCrearTarea = document.getElementById("formCrearTarea");

let tareaEditandoId = null;

// Cargar tareas desde API y renderizar
function cargarTareas() {
  fetch(API_URL)
    .then(response => {
      if (!response.ok) throw new Error('Error al cargar tareas');
      return response.json();
    })
    .then(data => {
      tareas = data.tareas; // Asumiendo estructura { tareas: [...] }
      renderTareas(tareas);
    })
    .catch(error => {
      alert('Error al cargar tareas: ' + error.message);
    });
}

function renderTareas(tareas) {
  tareasContainer.innerHTML = "";

  tareas.forEach((tarea) => {
    const card = document.createElement("article");
    card.classList.add("tarea-card");

    const estadoText = tarea.estado ? "Completado" : "Pendiente";
    const estadoClass = tarea.estado ? "completado" : "pendiente";

    card.innerHTML = `
      <h2 class="tarea-title">${tarea.titulo}</h2>
      <p class="tarea-desc">${tarea.descripcion}</p>
      <div class="tarea-meta">Creado: ${new Date(tarea.fecha_creacion).toLocaleString()}</div>
      <span class="estado ${estadoClass}">${estadoText}</span>
      <div class="botones-accion">
        <button class="toggle-estado" style="${tarea.estado == 1 ?'display:none;':""}" data-id="${tarea.id}" title="Cambiar estado">Estado</button>
        <button class="btn-actualizar" style="${tarea.estado == 1 ?'display:none;':""}" data-id="${tarea.id}" title="Actualizar tarea">Actualizar</button>
        <button class="btn-eliminar" data-id="${tarea.id}" title="Eliminar tarea" style="background:#dc3545;">Eliminar</button>
      </div>
    `;

    tareasContainer.appendChild(card);
  });

  // Listeners para actualizar
  const botonesActualizar = document.querySelectorAll(".btn-actualizar");
  botonesActualizar.forEach((btn) => {
    btn.addEventListener("click", () => {
      const id = parseInt(btn.getAttribute("data-id"));
      abrirModalConDatos(id);
    });
  });

  // Listeners para cambiar estado
  const botonesToggle = document.querySelectorAll(".toggle-estado");
  botonesToggle.forEach(btn => {
    btn.addEventListener("click", () => {
      const id = parseInt(btn.getAttribute("data-id"));
      toggleEstado(id);
    });
  });

  // Listeners para eliminar
  const botonesEliminar = document.querySelectorAll(".btn-eliminar");
  botonesEliminar.forEach(btn => {
    btn.addEventListener("click", () => {
      const id = parseInt(btn.getAttribute("data-id"));
      eliminarTarea(id);
    });
  });
}

function abrirModalConDatos(id) {
  modalTitulo.textContent = "Actualizar Tarea";
  const tarea = tareas.find((t) => t.id === id);
  if (!tarea) return;

  tareaEditandoId = id;

  formCrearTarea.titulo.value = tarea.titulo;
  formCrearTarea.descripcion.value = tarea.descripcion;
  formCrearTarea.usuario_id.value = tarea.usuario_id;

  modalCrear.classList.add("active");
}

btnAbrirModal.addEventListener("click", () => {
  tareaEditandoId = null;
  formCrearTarea.reset();
  modalTitulo.textContent = "Crear Nueva Tarea";
  modalCrear.classList.add("active");
});

btnCerrarModal.addEventListener("click", () => {
  modalCrear.classList.remove("active");
});

// Función para crear tarea en backend
function crearTarea(titulo, descripcion, usuario_id) {
  return fetch(API_URL, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ titulo, descripcion, estado: 0, usuario_id })
  })
  .then(response => {
    if (!response.ok) throw new Error('Error al crear tareadsad');
    return response.json();
  });
}

// Función para actualizar tarea en backend
function actualizarTarea(id, titulo, descripcion, usuario_id, estado) {
  estado = estado!= 0 ? 1 : 0
  return fetch(`${API_URL}/${id}`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ titulo, descripcion, usuario_id, estado })
  })
  .then(response => {
    if (!response.ok) throw new Error('Error al actualizar tarea');
    return response.json();
  });
}

// Función para eliminar tarea en backend
function eliminarTarea(id) {
  if (!confirm('¿Seguro que querés eliminar esta tarea?')) return;

  fetch(`${API_URL}/${id}`, {
    method: 'DELETE'
  })
  .then(response => {
    if (!response.ok) throw new Error('Error al eliminar tarea');
    // Refrescar tareas luego de eliminar
    cargarTareas();
  })
  .catch(error => {
    alert('Error: ' + error.message);
  });
}

// Cambiar estado: toggle true/false
function toggleEstado(id) {
  const tarea = tareas.find(t => t.id === id);
  if (!tarea) return;

  // Cambiar estado localmente
  const nuevoEstado = !tarea.estado;

  actualizarTarea(id, tarea.titulo, tarea.descripcion, tarea.usuario_id, nuevoEstado)
    .then(() => {
      // Actualizo el estado local y re-renderizo
      tarea.estado = nuevoEstado;
      renderTareas(tareas);
    })
    .catch(error => {
      alert('Error al cambiar estado: ' + error.message);
    });
}

formCrearTarea.addEventListener("submit", (e) => {
  e.preventDefault();

  const titulo = formCrearTarea.titulo.value.trim();
  const descripcion = formCrearTarea.descripcion.value.trim();
  const usuario_id = parseInt(formCrearTarea.usuario_id.value);

  if (tareaEditandoId) {
    // Actualizar
    const tarea = tareas.find((t) => t.id === tareaEditandoId);
    if (!tarea) return;

    actualizarTarea(tareaEditandoId, titulo, descripcion, usuario_id, tarea.estado)
      .then(() => {
        alert("Tarea actualizada correctamente.");
        tareaEditandoId = null;
        modalCrear.classList.remove("active");
        cargarTareas();
      })
      .catch(error => {
        alert("Error al actualizar: " + error.message);
      });
  } else {
    // Crear
    crearTarea(titulo, descripcion, usuario_id)
      .then(() => {
        alert("Tarea creada correctamente.");
        modalCrear.classList.remove("active");
        formCrearTarea.reset();
        cargarTareas();
      })
      .catch(error => {
        alert("Error al crear tarea: " + error.message);
      });
  }
});

// Cargar tareas al iniciar la página
cargarTareas();
