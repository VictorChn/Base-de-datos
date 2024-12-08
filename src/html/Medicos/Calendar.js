document.addEventListener("DOMContentLoaded", function() {
    const calendarDate = document.getElementById("calendar-date");
    const calendarScrollable = document.getElementById("calendar-scrollable");
    const currentTimeText = document.getElementById("current-time-text");

    let currentTime = new Date();

    function updateCalendar() {
        // Actualizar la fecha
        const formattedDate = currentTime.toLocaleDateString("es-ES", {
            weekday: "long",
            day: "numeric",
            month: "long",
            year: "numeric"
        });
        calendarDate.textContent = formattedDate;

        // Crear las horas
        const hours = [];
        for (let i = 0; i < 24; i++) {
            hours.push(`${i.toString().padStart(2, "0")}:00`);
        }

        // Limpiar el calendario y agregar las horas
        calendarScrollable.innerHTML = "";
        hours.forEach((hour, index) => {
            const hourRow = document.createElement("div");
            hourRow.classList.add("calendar-hour-row");
            const hourLabel = document.createElement("span");
            hourLabel.textContent = hour;
            hourRow.appendChild(hourLabel);

            // Resaltar la hora actual
            if (index === currentTime.getHours()) {
                hourRow.classList.add("current-time");
            }

            // Agregar evento de clic en la hora
            hourRow.addEventListener("click", () => {
                alert(`Cita programada para las ${hour}`);
            });

            calendarScrollable.appendChild(hourRow);
        });

        // Actualizar el texto de la hora actual
        const currentFormattedTime = `${currentTime.getHours().toString().padStart(2, "0")}:${currentTime.getMinutes().toString().padStart(2, "0")}`;
        currentTimeText.textContent = currentFormattedTime;

        // Centrar el scroll en la hora actual
        scrollToCurrentTime();
    }

    function scrollToCurrentTime() {
        const currentHour = currentTime.getHours();
        const hourRowHeight = 60; // Altura de cada fila de hora (en px)
        const scrollPosition = currentHour * hourRowHeight;

        // Desplazar el calendario hacia la hora actual
        calendarScrollable.scrollTop = scrollPosition - calendarScrollable.clientHeight / 2;
    }

    // Actualizar el calendario cada minuto
    setInterval(() => {
        currentTime = new Date();
        updateCalendar();
    }, 60000);

    // Llamada inicial para mostrar el calendario con la hora actual
    updateCalendar();
});
