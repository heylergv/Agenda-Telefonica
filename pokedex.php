
<?php
require_once "Proteger.php";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Pokédex</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background-color: #eae9e5;">

    <div class="container mt-5">

        <div class="text-center mb-4">
            <h1 style="color: #3c3b38;">¡Bienvenido a tu Pokédex!</h1>
            <h4 style="color: #3c3b38;">Entrenador: <?php echo htmlspecialchars($_SESSION["nombre"]); ?></h4>
            <p style="color: #3c3b38;">Busca un Pokémon por su nombre o número de la Pokédex Nacional.</p>
        </div>

        <!-- Buscar -->
        <div class="card shadow-sm p-4 mb-4">

            <div class="input-group">
                <input type="text" id="buscarPokemon" class="form-control" placeholder="Ejemplo: Pikachu o 25">
                <button type="button" class="btn btn-danger" onclick="BuscarPokemon()">🔍 Buscar Pokémon</button>
            </div>

        </div>

        <!-- Mostrar el resultado -->
        <div id="resultadoPokemon" class="text-center"></div>

        <div class="text-center mt-4 mb-5">
            <a href="Inicio.php" class="btn btn-secondary">Volver a la Agenda</a>
        </div>

    </div>

<script>

// Guardar al Pokemon que se busco
let pokemonSeleccionado = null;
let nombrePokemonSeleccionado = null;

// Busqueda de la API PokeAPI
async function BuscarPokemon() {

    let busqueda = document.getElementById("buscarPokemon").value.trim().toLowerCase();
    let resultado = document.getElementById("resultadoPokemon");

    // Limpia lo que se haya buscando anteriormente
        pokemonSeleccionado = null;
        nombrePokemonSeleccionado = null;

    if (busqueda === "") {
        resultado.innerHTML = '<div class="alert alert-warning">Escribe el nombre o número de un Pokémon.</div>';
        return;
    }

    resultado.innerHTML = '<div class="alert alert-info">Buscando Pokémon...</div>';

    try {

        let respuesta = await fetch("https://pokeapi.co/api/v2/pokemon/" + encodeURIComponent(busqueda));

        if (!respuesta.ok) {
            throw new Error("Pokémon no encontrado");
        }

        let pokemon = await respuesta.json();

        // Datos relevantes del Pokemon y no trae toda la lista completa. Arte Oficial por defecto
        let numero = pokemon.id;
        let nombre = pokemon.name.charAt(0).toUpperCase() + pokemon.name.slice(1);
        let imagen = pokemon.sprites.other["official-artwork"].front_default || pokemon.sprites.front_default;

        // Region por Id (Numero)
        let region = ObtenerRegion(numero);

        // Tipos de pokemon que no se usara
        let tipos = pokemon.types.map(tipo => tipo.type.name);

        // Guardar el Id (numero de pokemon)
        pokemonSeleccionado = numero;
        nombrePokemonSeleccionado = pokemon.name;
        // Mostrar resultado con HTLM
        resultado.innerHTML = `
            <div class="card shadow-sm mx-auto p-4" style="max-width: 450px;">

                <h3 style="color: #3c3b38;">#${numero} - ${nombre}</h3>

                <img src="${imagen}" alt="${nombre}" style="width: 200px; height: 200px; object-fit: contain; margin: auto;">

                <p class="mt-3" style="color: #3c3b38;"><strong>Región:</strong> ${region}</p>

                <p style="color: #3c3b38;"><strong>Tipo:</strong> ${tipos.join(", ")}</p>

                <button type="button" id="btnRegistrarPokemon" class="btn btn-success mt-3" onclick="RegistrarPokemon()">
                    📖 Registrar en Pokédex
                </button>

            </div>
        `;

    } catch (error) {

    pokemonSeleccionado = null;
    nombrePokemonSeleccionado = null;

    resultado.innerHTML = '<div class="alert alert-danger">No se encontró el Pokémon. Verifica el nombre o número.</div>';

}
}

// Región segun el numero de ID
function ObtenerRegion(numero) {

    if (numero >= 1 && numero <= 151) return "Kanto";
    if (numero >= 152 && numero <= 251) return "Johto";
    if (numero >= 252 && numero <= 386) return "Hoenn";
    if (numero >= 387 && numero <= 493) return "Sinnoh";
    if (numero >= 494 && numero <= 649) return "Unova";
    if (numero >= 650 && numero <= 721) return "Kalos";
    if (numero >= 722 && numero <= 809) return "Alola";
    if (numero >= 810 && numero <= 905) return "Galar";
    if (numero >= 906 && numero <= 1025) return "Paldea";

    return "Región no disponible";
}

// Registrar Pokemon en MYSQL
async function RegistrarPokemon() {

    if (!pokemonSeleccionado) {
        alert("Primero debes buscar un Pokémon.");
        return;
    }

    let boton = document.getElementById("btnRegistrarPokemon");

    // Evita el doble clic
    boton.disabled = true;
    boton.textContent = "Registrando Pokémon...";

    try {

        let respuesta = await fetch("RegistrarPokemon.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
    numero: pokemonSeleccionado,
    nombre: nombrePokemonSeleccionado
})
        });

        let resultado = await respuesta.json();

        alert(resultado.mensaje);

        if (respuesta.ok && resultado.estado) {

            boton.textContent = "✅ Registrado en tu Pokédex";
            boton.classList.remove("btn-success");
            boton.classList.add("btn-secondary");

        } else {

            boton.disabled = false;
            boton.textContent = "📖 Registrar en Pokédex";

        }

    } catch (error) {

        alert("Ocurrió un error al registrar el Pokémon.");

        boton.disabled = false;
        boton.textContent = "📖 Registrar en Pokédex";

    }
}


document.getElementById("buscarPokemon").addEventListener("keydown", function(event) {

    if (event.key === "Enter") {
        BuscarPokemon();
    }

});

</script>

</body>
</html>
