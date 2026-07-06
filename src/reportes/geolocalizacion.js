function obtenerUbicacion() {

    if (navigator.geolocation) {

        navigator.geolocation.getCurrentPosition(function(posicion){

            document.getElementById("latitud").value =
                posicion.coords.latitude;

            document.getElementById("longitud").value =
                posicion.coords.longitude;

        });

    } else {
        alert("No se pudo obtener la localizacion");
    }
}