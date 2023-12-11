document.addEventListener('DOMContentLoaded', function () {
  // Botón para mostrar solo películas
  const showMoviesButton = document.getElementById('show-movies-button');
  // Botón para mostrar solo series
  const showSeriesButton = document.getElementById('show-series-button');

  // Contenedor de botones de género para películas
  const movieGenreButtons = document.getElementById('genre-buttons-movies');
  // Contenedor de botones de género para series
  const seriesGenreButtons = document.getElementById('genre-buttons-series');

  // Evento al hacer clic en "Mostrar solo películas"
  showMoviesButton.addEventListener('click', function () {
    // Muestro solo botones de género de películas
    movieGenreButtons.style.display = 'block';
    seriesGenreButtons.style.display = 'none';

    // Limpio la cartelera de películas y series
    document.getElementById('movie-list').innerHTML = '';
    document.getElementById('series-list').innerHTML = '';
  });

  // Evento al hacer clic en "Mostrar solo series"
  showSeriesButton.addEventListener('click', function () {
    // Muestro solo botones de género de series
    seriesGenreButtons.style.display = 'block';
    movieGenreButtons.style.display = 'none';

    // Limpio la cartelera de películas y series
    document.getElementById('movie-list').innerHTML = '';
    document.getElementById('series-list').innerHTML = '';
  });

  // Cargo todos los botones de género de películas y series
  createGenreButtons('movie', 'movies', movieGenreButtons);
  createGenreButtons('tv', 'series', seriesGenreButtons);
});

// Función para cargar géneros de películas o series
function createGenreButtons(endpoint, mediaType, genreButtons) {
  const apiKey = '5f4e3a08b9be2e852b443b4cb14f45f7'; 

  // Hago una solicitud a la API para obtener la lista de géneros
  fetch(`https://api.themoviedb.org/3/genre/${endpoint}/list?api_key=${apiKey}&language=es-ES`)
    .then(response => response.json())
    .then(data => {
      data.genres.forEach(genre => {
        const genreButton = document.createElement('button');
        genreButton.classList.add('btn', 'btn-primary', 'mr-2', 'mb-2');
        genreButton.textContent = genre.name;

        genreButton.addEventListener('click', function () {
          loadMediaByGenre(genre.id, mediaType);
        });

        genreButtons.appendChild(genreButton);
      });
    })
    .catch(error => console.error(`Error al cargar los géneros de ${endpoint}:`, error));
}

// Función para cargar películas o series por género
function loadMediaByGenre(genreId, mediaType) {
  const endpoint = mediaType === 'movies' ? 'movie' : 'tv';
  const apiKey = '5f4e3a08b9be2e852b443b4cb14f45f7'; 
  const apiUrl = `https://api.themoviedb.org/3/discover/${endpoint}?api_key=${apiKey}&with_genres=${genreId}&language=es-ES&page=1`;
  const mediaList = mediaType === 'movies' ? document.getElementById('movie-list') : document.getElementById('series-list');

  fetch(apiUrl)
    .then(response => response.json())
    .then(data => {
      mediaList.innerHTML = ''; 

      data.results.forEach(media => {

        if (media.poster_path == null) {
          imageUrl = `../img/problemastecnicos.jpg`
        } else{
          imageUrl = `https://image.tmdb.org/t/p/w500${media.poster_path}`;
        }

        const title = media.title || media.name;
        const mediaId = media.id; // ID de la película o serie

        // Creo un elemento para mostrar la película o serie envuelto en un enlace
        const mediaLink = document.createElement('a');
        mediaLink.href = mediaType === 'movies' ? `detalle-pelicula.php?id=${mediaId}` : `detalle-serie.php?id=${mediaId}`;
        mediaLink.classList.add('col-md-3', 'mb-3');

        // Creo el elemento de la tarjeta de película o serie
        const mediaElement = document.createElement('div');
        mediaElement.classList.add('card');

        // Creo la imagen de la tarjeta
        const mediaImage = document.createElement('img');
        mediaImage.src = imageUrl;
        mediaImage.alt = title;
        mediaImage.classList.add('card-img-top');

        // Creo el cuerpo de la tarjeta
        const mediaCardBody = document.createElement('div');
        mediaCardBody.classList.add('card-body');

        // Creo el título de la película o serie
        const mediaTitle = document.createElement('h5');
        mediaTitle.classList.add('card-title');
        mediaTitle.textContent = title;

        // Anido los elementos
        mediaCardBody.appendChild(mediaTitle);
        mediaElement.appendChild(mediaImage);
        mediaElement.appendChild(mediaCardBody);
        mediaLink.appendChild(mediaElement);

        // Agrego el enlace con la película o serie a la lista correspondiente
        mediaList.appendChild(mediaLink);
      });
    })
    .catch(error => console.error(`Error al cargar ${mediaType} por género:`, error));
}
