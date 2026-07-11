function obtenerUbicacion() {

    if (navigator.geolocation) {

        navigator.geolocation.getCurrentPosition(function(posicion){

            document.getElementById("latitud").value =
                posicion.coords.latitude;

            document.getElementById("longitud").value =
                posicion.coords.longitude;

            document.getElementById('coordenadas-texto-visible').value = "Latitud: " + posicion.coords.latitude.toFixed(6) + " | Longitud: " + posicion.coords.longitude.toFixed(6);



        });

    } else {
        alert("No se pudo obtener la localizacion");
    }
}