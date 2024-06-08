document.addEventListener("DOMContentLoaded", async () => {
    const cssLink = document.createElement("link");
    cssLink.href = "botones.css";
    cssLink.rel = "stylesheet";
    cssLink.type = "text/css";
    document.head.appendChild(cssLink);
  
    try {
      await loadClothes();
    } catch (error) {
      console.error("Error al obtener la lista de prendas:", error);
      showMessage("Error al obtener la lista de prendas");
    }
  });
  
  async function loadClothes() {
    const response = await fetch("ropa-api.php");
    if (!response.ok) {
      throw new Error("Error al obtener la lista de prendas");
    }
  
    const clothes = await response.json();
    updateClothesTable(clothes);
  }
  
  function updateClothesTable(clothes) {
    const tbody = document.querySelector("#prendasTable tbody");
    tbody.innerHTML = clothes
      .map(
        (cloth) => `
          <tr>
            <td>${cloth.nombre}</td>
            <td>${cloth.precio}</td>
            <td><img src="${cloth.imagen_url}" alt="${cloth.nombre}" width="100"></td>
            <td>
              <button class="Btn" onclick="editCloth(${cloth.id}, '${cloth.nombre}', ${cloth.precio}, '${cloth.imagen_url}')">
                Editar
                <svg class="svg" viewBox="0 0 512 512">
                  <path d="..."></path>
                </svg>
              </button>
              <button type="button" class="button" onclick="deleteCloth(${cloth.id})">
                <span class="button__text">Eliminar</span>
                <svg>...</svg>
              </button>
            </td>
          </tr>
        `
      )
      .join("");
  }
  
  function showMessage(message) {
    document.getElementById("mensaje").textContent = message;
  }
  
  const addForm = document.getElementById("agregar-formulario");
  addForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(addForm);
  
    try {
      const response = await fetch("agregar-ropa.php", {
        method: "POST",
        body: formData,
      });
  
      if (response.ok) {
        const data = await response.json();
        showMessage(data.mensaje);
        addForm.reset();
        await loadClothes();
      } else {
        showMessage("Error al agregar la prenda");
      }
    } catch (error) {
      console.error("Error al realizar la solicitud:", error);
      showMessage("Error al realizar la solicitud");
    }
  });
  
  async function deleteCloth(id) {
    if (confirm("¿Estás seguro de que deseas eliminar esta prenda?")) {
      try {
        const response = await fetch("eliminar-ropa.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ id }),
        });
  
        if (response.ok) {
          const data = await response.json();
          console.log(data);
          await loadClothes();
        } else {
          showMessage("Error al eliminar la prenda");
        }
      } catch (error) {
        console.error("Error al eliminar la prenda:", error);
        showMessage("Error al eliminar la prenda");
      }
    }
  }
  
  function editCloth(id, nombre, precio, imagen) {
    document.getElementById("id").value = id;
    document.getElementById("nombre2").value = nombre;
    document.getElementById("precio2").value = precio;
  
    const modal = document.getElementById("modal-edicion");
    modal.style.display = "block";
  }
  
  const updateForm = document.getElementById("actualizar-formulario");
  updateForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(updateForm);
  
    try {
      const response = await fetch("editar-ropa.php", {
        method: "POST",
        body: formData,
      });
  
      if (response.ok) {
        const data = await response.json();
        document.getElementById("mensaje-actualizacion").textContent = data.mensaje;
        updateForm.reset();
        document.getElementById("modal-edicion").style.display = "none";
        await loadClothes();
      } else {
        document.getElementById("mensaje-actualizacion").textContent =   "Error al actualizar la prenda";
      }
    } catch (error) {
      console.error("Error al realizar la solicitud:", error);
      document.getElementById("mensaje-actualizacion").textContent =
        "Error al realizar la solicitud de actualización";
    }
  });
  
  // Funciones para cerrar el modal
  const closeModal = document.getElementsByClassName("close")[0];
  closeModal.onclick = function () {
    const modal = document.getElementById("modal-edicion");
    modal.style.display = "none";
  };
  
  window.onclick = function (event) {
    const modal = document.getElementById("modal-edicion");
    if (event.target == modal) {
      modal.style.display = "none";
    }
  };