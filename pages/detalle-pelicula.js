// Obtener el ID del medio (película o serie) de la URL
const urlParams = new URLSearchParams(window.location.search);
const mediaId = urlParams.get('id');
const mediaType = urlParams.get('type'); // Agregar un parámetro "type" a la URL

// Obtener los elementos del botón "Agregar a Favoritos" y "Eliminar de Favoritos" en tu HTML
const addToFavoritesButton = document.getElementById('addToFavorites');
const removeFromFavoritesButton = document.getElementById('removeFromFavorites');

// Agregar una función asincrónica para verificar si el medio está en favoritos
async function checkIfMediaIsInFavorites() {
  try {
    const response = await fetch('verificar-favoritos.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: 'mediaId=' + mediaId,
    });

    if (response.status === 200) {
      const data = await response.json();
      if (data.isInFavorites) {
        // El medio está en favoritos, mostrar el botón "Eliminar de Favoritos"
        addToFavoritesButton.style.display = 'none';
        removeFromFavoritesButton.style.display = 'block';
      } else {
        // El medio no está en favoritos, mostrar el botón "Agregar a Favoritos"
        addToFavoritesButton.style.display = 'block';
        removeFromFavoritesButton.style.display = 'none';
      }
    } else {
      console.error('Error en la solicitud al servidor');
    }
  } catch (error) {
    console.error('Error:', error);
  }
}

// Llamar a la función para verificar si el medio está en favoritos al cargar la página
window.addEventListener('load', checkIfMediaIsInFavorites);

// Agregar un evento click al botón "Agregar a Favoritos"
addToFavoritesButton.addEventListener('click', async function () {
  try {
    const response = await fetch('favoritos.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: 'mediaId=' + mediaId,
    });

    if (response.status === 200) {
      const data = await response.json();
      if (data.success) {
        // Película agregada a favoritos
        Swal.fire({
          icon: 'success',
          title: 'Éxito',
          text: data.message,
        }).then((result) => {
          if (result.isConfirmed) {
            addToFavoritesButton.style.display = 'none';
            removeFromFavoritesButton.style.display = 'block';
          }
        });
      } else {
        // Error al agregar la película a favoritos
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: data.message,
        });
      }
    } else {
      console.error('Error en la solicitud al servidor');
    }
  } catch (error) {
    console.error('Error:', error);
  }
});

// Agregar un evento click al botón "Eliminar de Favoritos"
removeFromFavoritesButton.addEventListener('click', async function () {
  try {
    const response = await fetch('eliminar-favoritos.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: 'mediaId=' + mediaId,
    });

    if (response.status === 200) {
      const data = await response.json();
      if (data.success) {
        // Película eliminada de favoritos
        Swal.fire({
          icon: 'success',
          title: 'Éxito',
          text: data.message,
        }).then((result) => {
          if (result.isConfirmed) {
            addToFavoritesButton.style.display = 'block';
            removeFromFavoritesButton.style.display = 'none';
          }
        });
      } else {
        // Error al eliminar la película de favoritos
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: data.message,
        });
      }
    } else {
      console.error('Error en la solicitud al servidor');
    }
  } catch (error) {
    console.error('Error:', error);
  }
});



// API Key proporcionada por themoviedb
const apiKey = '5f4e3a08b9be2e852b443b4cb14f45f7';

// URL de la API de themoviedb para obtener los detalles de la película
const movieUrl = `https://api.themoviedb.org/3/movie/${mediaId}?api_key=${apiKey}&language=es-ES`;

// Función para obtener las plataformas de transmisión para la película en Argentina
function getStreamingPlatformsInArgentina(mediaType, mediaId, apiKey) {
  const streamingUrl = `https://api.themoviedb.org/3/${mediaType}/${mediaId}/watch/providers?api_key=${apiKey}&region=AR&language=es-ES`;
  return fetch(streamingUrl)
    .then(response => response.json())
    .then(data => {
      return data.results;
    });
}

// Realizar la solicitud a la API de themoviedb para obtener los detalles de la película
fetch(movieUrl)
  .then(response => response.json())
  .then(mediaData => {
    // Obtener los elementos del DOM donde mostrar la información
    const titleElement = document.getElementById('title');
    const overviewElement = document.getElementById('overview');
    const castElement = document.getElementById('cast');
    const imagesElement = document.getElementById('images');
    const genresElement = document.getElementById('genres');
    const trailerContainer = document.getElementById('trailer-container');
    const platformsElement = document.getElementById('platforms'); // Elemento para mostrar las plataformas

    // Se encontró una película válida, mostrar la información de la película
// Mostrar el título y la sinopsis de la película
titleElement.textContent = mediaData.title;
if (mediaData.overview) {
  overviewElement.textContent = mediaData.overview;
} else {
  // Mostrar un mensaje indicando que no hay sinopsis disponible
  overviewElement.textContent = 'No hay sinopsis disponible.';
}


    // Mostrar el póster de la película con su año de lanzamiento
    const posterElement = document.createElement('img');
    const posterPath = mediaData.poster_path;

    if (posterPath) {
      posterElement.src = `https://image.tmdb.org/t/p/w500${posterPath}`;
      posterElement.alt = mediaData.title;
      titleElement.appendChild(posterElement);
    } else {
      // Mostrar la imagen predeterminada si no se encuentra el póster
      const defaultPosterElement = document.createElement('img');
      defaultPosterElement.src = '../img/problemastecnicos.jpg';
      defaultPosterElement.alt = 'Imagen de problemas técnicos';
      titleElement.appendChild(defaultPosterElement);
    }

    const yearElement = document.createElement('p');
    yearElement.textContent = `Año de lanzamiento: ${mediaData.release_date?.substring(0, 4)}`;
    titleElement.appendChild(yearElement);

    // Mostrar la duración de la película
    const durationElement = document.createElement('p');
    durationElement.textContent = `Duración: ${mediaData.runtime || 'No se encontraron'} minutos`;
    titleElement.appendChild(durationElement);

    // Mostrar los géneros de la película
    const genres = mediaData.genres;

    if (genres.length > 0) {
      const genreNames = genres.map(genre => genre.name);
      genresElement.textContent = `${genreNames.join(', ')}`;
    } else {
      // Mostrar un mensaje indicando que no se encontró información sobre los géneros
      const noGenresMessage = document.createElement('p');
      noGenresMessage.textContent = 'No se encontró información sobre los géneros.';
      noGenresMessage.classList.add('no-genres-message'); // Agrega la clase al elemento
      genresElement.appendChild(noGenresMessage);
    }


    // Obtener el reparto de la película
    const castUrl = `https://api.themoviedb.org/3/movie/${mediaId}/credits?api_key=${apiKey}`;
    fetch(castUrl)
      .then(response => response.json())
      .then(castData => {
        // Recorrer los primeros 8 miembros del reparto y mostrar sus fotos y nombres
        const castMembers = castData.cast.slice(0, 8);

        if (castMembers.length > 0) {
          // Crear un contenedor para mostrar las fotos y nombres de los actores
          const castContainer = document.createElement('div');
          castContainer.classList.add('cast-container');

          castMembers.forEach(member => {
            if (member.profile_path) { // Verificar si `profile_path` no es nulo
              // Crear un contenedor para cada actor
              const actorContainer = document.createElement('div');
              actorContainer.classList.add('actor-container');

              // Crear un elemento de imagen para mostrar la foto del actor
              const actorImage = document.createElement('img');
              actorImage.src = `https://image.tmdb.org/t/p/w200${member.profile_path}`; // Ajusta el tamaño de la imagen según tus necesidades
              actorImage.alt = member.name;
              actorContainer.appendChild(actorImage);

              // Crear un elemento de texto para mostrar el nombre del actor
              const actorName = document.createElement('p');
              actorName.textContent = member.name;
              actorContainer.appendChild(actorName);

              // Agregar el contenedor del actor al contenedor principal
              castContainer.appendChild(actorContainer);
            }
          });

          // Agregar el contenedor del reparto al elemento correspondiente en el DOM
          castElement.appendChild(castContainer);
        } else {
          // Mostrar un mensaje indicando que no se encontró información sobre el reparto
          const noCastMessage = document.createElement('p');
          noCastMessage.textContent = 'No se encontró información sobre el reparto.';
          noCastMessage.classList.add('no-cast-message'); // Agrega la clase al elemento
          castElement.appendChild(noCastMessage);
        }
      });



    // Obtener las imágenes de la película
    const imagesUrl = `https://api.themoviedb.org/3/movie/${mediaId}/images?api_key=${apiKey}`;
    fetch(imagesUrl)
      .then(response => response.json())
      .then(imagesData => {
        // Recorrer las primeras 3 imágenes y mostrar su URL
        const images = imagesData.backdrops.slice(0, 3);

        if (images.length > 0) {
          const imageUrls = images.map(image => `https://image.tmdb.org/t/p/w500${image.file_path}`);
          imageUrls.forEach(url => {
            const imageElement = document.createElement('img');
            imageElement.src = url;
            imagesElement.appendChild(imageElement);
          });
        } else {
          // Mostrar un mensaje indicando que no se encontraron imágenes
          const noImagesMessage = document.createElement('p');
          noImagesMessage.textContent = 'No se encontraron imágenes.';
          noImagesMessage.classList.add('no-images-message'); // 
          imagesElement.appendChild(noImagesMessage);
        }
      });


    // Obtener el tráiler de la película en inglés con subtítulos en español
    const videosUrl = `https://api.themoviedb.org/3/movie/${mediaId}/videos?api_key=${apiKey}&language=en-US`; // Trailer en inglés
    fetch(videosUrl)
      .then(response => response.json())
      .then(videosData => {
        const trailer = videosData.results.find(video => video.type === 'Trailer' && video.site === 'YouTube');
        if (trailer) {
          const trailerElement = document.createElement('iframe');
          trailerElement.src = `https://www.youtube.com/embed/${trailer.key}?cc_load_policy=1&cc_lang_pref=es`; // Subtítulos en español
          trailerElement.title = 'Trailer';
          trailerElement.allowFullscreen = true;
          trailerContainer.appendChild(trailerElement);
        } else {
          const noTrailerMessage = document.createElement('p');
          noTrailerMessage.textContent = 'No se encontró el tráiler de la película.';
          noTrailerMessage.classList.add('no-trailer-message'); // Agrega la clase al elemento
          trailerContainer.appendChild(noTrailerMessage);
        }
      });

    // Obtener las plataformas de transmisión para la película en Argentina y mostrarlas
    getStreamingPlatformsInArgentina('movie', mediaId, apiKey)
      .then(platforms => {
        if (platforms && platforms.AR) {
          const streamingPlatforms = platforms.AR.flatrate;
          if (streamingPlatforms && streamingPlatforms.length > 0) {
            const platformNames = streamingPlatforms.map(platform => platform.provider_name);
            platformsElement.textContent = `Plataformas de transmisión: ${platformNames.join(', ')}`;
          } else {
            // Mostrar un mensaje indicando que no hay datos disponibles
            platformsElement.textContent = 'Información de plataformas no disponible en Argentina en este momento';
          }
        } else {
          // Mostrar un mensaje indicando que no hay datos disponibles
          platformsElement.textContent = 'Información de plataformas no disponible en Argentina en este momento';
        }
      })
      .catch(error => {
        // Manejo de errores en caso de que la solicitud falle
        console.error('Error:', error);
        // Mostrar un mensaje indicando que no hay datos disponibles
        platformsElement.textContent = 'Información de plataformas no disponible en Argentina en este momento';
      });
  })
  .catch(error => {
    // Manejo de errores en caso de que la solicitud falle
    console.error('Error:', error);
  });


