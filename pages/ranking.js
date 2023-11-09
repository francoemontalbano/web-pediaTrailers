const showTopMoviesButton = document.getElementById('show-top-movies-button');
const showTopSeriesButton = document.getElementById('show-top-series-button');

showTopMoviesButton.addEventListener('click', function () {
  document.getElementById('movie-list').innerHTML = '';
  document.getElementById('series-list').innerHTML = '';
  loadTopMedia('movie', 'movies'); // Cambia 'tv' a 'movie' aquí
});

showTopSeriesButton.addEventListener('click', function () {
  document.getElementById('movie-list').innerHTML = '';
  document.getElementById('series-list').innerHTML = '';
  loadTopMedia('tv', 'tv');
});

function loadTopMedia(endpoint, mediaType) {
  const apiKey = '5f4e3a08b9be2e852b443b4cb14f45f7';
  const apiUrl = `https://api.themoviedb.org/3/${endpoint}/top_rated?api_key=${apiKey}&language=es-ES&page=1`; // Cambia 'mediaType' a 'endpoint' aquí
  const mediaList = mediaType === 'movies' ? document.getElementById('movie-list') : document.getElementById('series-list');

  fetch(apiUrl)
    .then(response => response.json())
    .then(data => {
      mediaList.innerHTML = '';

      data.results.slice(0, 12).forEach(media => {
        const imageUrl = `https://image.tmdb.org/t/p/w500${media.poster_path}`;
        const title = media.title || media.name;
        const mediaId = media.id;

        const mediaLink = document.createElement('a');
        mediaLink.href = mediaType === 'movies' ? `detalle-pelicula.php?id=${mediaId}` : `detalle-serie.php?id=${mediaId}`;
        mediaLink.classList.add('col-md-3', 'mb-3');

        const mediaElement = document.createElement('div');
        mediaElement.classList.add('card');

        const mediaImage = document.createElement('img');
        mediaImage.src = imageUrl;
        mediaImage.alt = title;
        mediaImage.classList.add('card-img-top');

        const mediaCardBody = document.createElement('div');
        mediaCardBody.classList.add('card-body');

        const mediaTitle = document.createElement('h5');
        mediaTitle.classList.add('card-title');
        mediaTitle.textContent = title;

        mediaCardBody.appendChild(mediaTitle);
        mediaElement.appendChild(mediaImage);
        mediaElement.appendChild(mediaCardBody);
        mediaLink.appendChild(mediaElement);

        mediaList.appendChild(mediaLink);
      });
    })
    .catch(error => console.error(`Error al cargar las 10 mejores ${mediaType}:`, error));
}
