// API Key proporcionada por themoviedb
const apiKey = '5f4e3a08b9be2e852b443b4cb14f45f7';

// URL de la API de themoviedb para obtener las películas próximas a estrenar
const url = `https://api.themoviedb.org/3/movie/upcoming?api_key=${apiKey}&language=es-ES&page=1`;

// Realiza la solicitud a la API de themoviedb para obtener las películas próximas a estrenar en español
fetch(url)
  .then(response => response.json())
  .then(data => {
    // Obtén el elemento del DOM donde mostrar las películas próximas a estrenar
    const upcomingMoviesElement = document.getElementById('upcoming-movies');

   // Recorre los resultados y muestra cada película en el elemento correspondiente del DOM
data.results.forEach(movie => {
  // Crea elementos HTML para mostrar el título y fecha de estreno de la película
  const movieElement = document.createElement('div');
  movieElement.classList.add('movie');

  const titleElement = document.createElement('h2');
  titleElement.textContent = movie.title;

  const releaseDateElement = document.createElement('p');
  releaseDateElement.textContent = `Fecha de estreno: ${movie.release_date}`;

  const posterElement = document.createElement('img');
  posterElement.src = `https://image.tmdb.org/t/p/w500${movie.poster_path}`;
  posterElement.alt = movie.title;

  // Agrega un evento de clic al poster para redirigir a la página de detalles
  posterElement.addEventListener('click', function () {
    // Obtiene el ID de la película
    const movieId = movie.id;

    // Redirecciona a la página de detalles de la película con el ID en la URL
    window.location.href = `detalle-pelicula.php?id=${movieId}`;
  });

  // Cambia el cursor a una mano cuando se pasa el cursor sobre el poster
posterElement.addEventListener('mouseover', function () {
  posterElement.style.cursor = 'pointer';
});

// Restaura el cursor predeterminado cuando el cursor se retira del poster
posterElement.addEventListener('mouseout', function () {
  posterElement.style.cursor = 'default';
});

  // Agrega los elementos al contenedor de películas próximas a estrenar
  movieElement.appendChild(titleElement);
  movieElement.appendChild(releaseDateElement);
  movieElement.appendChild(posterElement);
  upcomingMoviesElement.appendChild(movieElement);
});
  })
  .catch(error => {
    // Manejo de errores en caso de que la solicitud falle
    console.error('Error:', error);
  });
