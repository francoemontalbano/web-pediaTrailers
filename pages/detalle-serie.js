// Obtengo el ID de la serie de la URL
const urlParams = new URLSearchParams(window.location.search);
const mediaId = urlParams.get('id');
const mediaType = urlParams.get('type'); // Agregar un parámetro "type" a la URL

// Obtengo los elementos del botón "Agregar a Favoritos" y "Eliminar de Favoritos" en tu HTML
const addToFavoritesButton = document.getElementById('addToFavorites');
const removeFromFavoritesButton = document.getElementById('removeFromFavorites');

// Obtengo el contenedor de comentarios y el formulario de comentarios en tu HTML
const commentsContainer = document.getElementById('comments-container');
const commentForm = document.getElementById('commentForm');
// Obtengo la fecha actual en formato ISO (ejemplo: "2023-11-13T12:00:00")
const fecha = new Date().toISOString();

// Agrego una función asincrónica para verificar si la serie está en favoritos
async function checkIfMediaIsInFavorites() {
  try {
    const response = await fetch('verificar-favoritos-series.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: 'mediaId=' + mediaId,
    });

    if (response.status === 200) {
      const data = await response.json();
      if (data.isInFavorites) {
        // La serie está en favoritos, mostrar el botón "Eliminar de Favoritos"
        addToFavoritesButton.style.display = 'none';
        removeFromFavoritesButton.style.display = 'block';
      } else {
        // La serie no está en favoritos, mostrar el botón "Agregar a Favoritos"
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

// Llamo a la función para verificar si la serie está en favoritos al cargar la página
window.addEventListener('load', checkIfMediaIsInFavorites);

// Función para mostrar los comentarios en el contenedor de comentarios
function mostrarComentarios(comentarios) {
  if (comentarios.length > 0) {
    const comentariosHTML = comentarios.map(comment => {
      const fecha = new Date(comment.fecha);
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      const fechaFormateada = fecha.toLocaleDateString(undefined, options);

      return `
        <div class="comment">
          <p>${comment.comentario}</p>
          <span>${fechaFormateada} - ${comment.nombre_usuario}</span>
        </div>
      `;
    }).join('');

    commentsContainer.innerHTML = comentariosHTML;
  } else {
    // Mostrar un mensaje indicando que no hay comentarios
    commentsContainer.innerHTML = '<p>No hay comentarios.</p>';
  }
}

// Función para cargar los comentarios al cargar la página
async function cargarComentarios() {
  try {
    const response = await fetch(`obtener-comentarios.php?mediaId=${mediaId}`);
    if (response.status === 200) {
      const comentarios = await response.json();
      mostrarComentarios(comentarios);
    } else {
      console.error('Error en la solicitud al servidor para obtener comentarios');
    }
  } catch (error) {
    console.error('Error:', error);
  }
}

// Llamo a la función para cargar comentarios al cargar la página
window.addEventListener('load', cargarComentarios);

// Agrego un evento click al botón "Agregar a Favoritos"
addToFavoritesButton.addEventListener('click', async function () {
  try {
    const response = await fetch('favoritos-serie.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: 'mediaId=' + mediaId,
    });

    if (response.status === 200) {
      const data = await response.json();
      if (data.success) {
        // Serie agregada a favoritos
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
        // Error al agregar la serie a favoritos
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

// Agrego un evento click al botón "Eliminar de Favoritos"
removeFromFavoritesButton.addEventListener('click', async function () {
  try {
    const response = await fetch('eliminar-favoritos-serie.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: 'mediaId=' + mediaId,
    });

    if (response.status === 200) {
      const data = await response.json();
      if (data.success) {
        // Serie eliminada de favoritos
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
        // Error al eliminar la serie de favoritos
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

// Agrego un evento submit al formulario de comentarios
commentForm.addEventListener('submit', async function (event) {
  event.preventDefault();

  const commentText = document.getElementById('commentText').value;

  try {
    const response = await fetch('agregar-comentario-serie.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `mediaId=${mediaId}&commentText=${commentText}&fecha=${fecha}`,
    });

    if (response.status === 200) {
      const data = await response.json();
      if (data.success) {
        // Comentario agregado con éxito, mostrar el SweetAlert
        Swal.fire({
          icon: 'success',
          title: 'Comentario Enviado',
          text: 'Tu comentario se ha enviado con éxito.',
        }).then((result) => {
          if (result.isConfirmed) {
            cargarComentarios(); 
            document.getElementById('commentText').value = ''; 
          }
        });
      } else {
        // Error al agregar el comentario
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: data.message,
        });
        document.getElementById('commentText').value = ''; 
      }
    } else {
      console.error('Error en la solicitud al servidor');
    }
  } catch (error) {
    console.error('Error:', error);
  }
});


const apiKey = '5f4e3a08b9be2e852b443b4cb14f45f7';


const seriesUrl = `https://api.themoviedb.org/3/tv/${mediaId}?api_key=${apiKey}&language=es-ES`;

// Función para obtener las plataformas de transmisión 
function getStreamingPlatformsInArgentina(mediaType, mediaId, apiKey) {
  const streamingUrl = `https://api.themoviedb.org/3/${mediaType}/${mediaId}/watch/providers?api_key=${apiKey}&region=AR&language=es-ES`;
  return fetch(streamingUrl)
    .then(response => response.json())
    .then(data => {
      return data.results;
    });
}

function showNoPlatformsMessage() {
  const noPlatformsMessage = document.getElementById('no-series-platforms-message');
  noPlatformsMessage.style.display = 'block'; 
}


fetch(seriesUrl)
  .then(response => response.json())
  .then(mediaData => {
    // Obtén los elementos del DOM donde mostrar la información de la serie
    const seriesTitleElement = document.getElementById('series-title');
    const seriesOverviewElement = document.getElementById('series-overview');
    const seriesCastElement = document.getElementById('series-cast');
    const seriesImagesElement = document.getElementById('series-images');
    const seriesGenresElement = document.getElementById('series-genres');
    const seriesTrailerContainer = document.getElementById('series-trailer-container');
    const seriesPlatformsElement = document.getElementById('series-platforms');

    // Se encontró una serie válida, mostrar la información de la serie
    // Muestro el título y la sinopsis de la serie
    if (mediaData.name) {
      seriesTitleElement.textContent = mediaData.name;
    } else {
      seriesTitleElement.textContent = 'No se encontró el título de la serie.';
    }

    if (mediaData.overview) {
      seriesOverviewElement.textContent = mediaData.overview;
    } else {
      const noOverviewMessage = document.createElement('p');
      noOverviewMessage.textContent = 'No se encontró la sinopsis de la serie.';
      noOverviewMessage.classList.add('no-overview-message'); 
      seriesOverviewElement.appendChild(noOverviewMessage);
    }

    // Muestro el póster de la serie con su año de lanzamiento
    const seriesPosterElement = document.createElement('img');
    seriesPosterElement.alt = mediaData.name;

    if (mediaData.poster_path) {
      seriesPosterElement.src = `https://image.tmdb.org/t/p/w500${mediaData.poster_path}`;
    } else {
      seriesPosterElement.src = '../img/problemastecnicos.jpg';
    }

    seriesTitleElement.appendChild(seriesPosterElement);


    const yearElement = document.createElement('p');
    yearElement.textContent = `Año de lanzamiento: ${mediaData.first_air_date?.substring(0, 4)}`;
    seriesTitleElement.appendChild(yearElement);


    // Muestro los géneros de la serie
    if (mediaData.genres && mediaData.genres.length > 0) {
      const genres = mediaData.genres.map(genre => genre.name);
      seriesGenresElement.textContent = `${genres.join(', ')}`;
    } else {
      const noGenresMessage = document.createElement('p');
      noGenresMessage.textContent = 'No se encontraron géneros para la serie.';
      noGenresMessage.classList.add('no-genres-message'); 
      seriesGenresElement.appendChild(noGenresMessage);
    }

    // Obtengo el reparto de la serie
    const castUrl = `https://api.themoviedb.org/3/tv/${mediaId}/credits?api_key=${apiKey}`;
    fetch(castUrl)
      .then(response => response.json())
      .then(castData => {
        if (castData.cast && castData.cast.length > 0) {
          // Recorro los primeros 8 miembros del reparto y mostrar sus fotos y nombres
          const castMembers = castData.cast.slice(0, 8);

          // Creo un contenedor para mostrar las fotos y nombres de los actores
          const castContainer = document.createElement('div');
          castContainer.classList.add('cast-container');

          castMembers.forEach(member => {
            // Creo un contenedor para cada actor
            const actorContainer = document.createElement('div');
            actorContainer.classList.add('actor-container');

            // Creo un elemento de imagen para mostrar la foto del actor
            const actorImage = document.createElement('img');
            actorImage.src = `https://image.tmdb.org/t/p/w200${member.profile_path}`; // Ajusta el tamaño de la imagen según tus necesidades
            actorImage.alt = member.name;
            actorContainer.appendChild(actorImage);

            // Creo un elemento de texto para mostrar el nombre del actor
            const actorName = document.createElement('p');
            actorName.textContent = member.name;
            actorContainer.appendChild(actorName);

            // Agrego el contenedor del actor al contenedor principal
            castContainer.appendChild(actorContainer);
          });

          // Agrego el contenedor del reparto al elemento correspondiente en el DOM
          seriesCastElement.appendChild(castContainer);
        } else {
          const noCastMessage = document.createElement('p');
          noCastMessage.textContent = 'No se encontró información de reparto para la serie.';
          noCastMessage.classList.add('no-cast-message'); // Agrega la clase al elemento
          seriesCastElement.appendChild(noCastMessage);
        }
      });

    // Obtengo las imágenes de la serie
    const imagesUrl = `https://api.themoviedb.org/3/tv/${mediaId}/images?api_key=${apiKey}`;
    fetch(imagesUrl)
      .then(response => response.json())
      .then(imagesData => {
        if (imagesData.backdrops && imagesData.backdrops.length > 0) {
          // Recorro las primeras 3 imágenes y muestra su URL
          const images = imagesData.backdrops.slice(0, 3);
          const imageUrls = images.map(image => `https://image.tmdb.org/t/p/w500${image.file_path}`);
          imageUrls.forEach(url => {
            const imageElement = document.createElement('img');
            imageElement.src = url;
            seriesImagesElement.appendChild(imageElement);
          });
        } else {
          const noImagesMessage = document.createElement('p');
          noImagesMessage.textContent = 'No se encontraron imágenes de la serie.';
          noImagesMessage.classList.add('no-images-message'); // Agrega la clase al elemento
          seriesImagesElement.appendChild(noImagesMessage);
        }
      });

    const videosUrl = `https://api.themoviedb.org/3/tv/${mediaId}/videos?api_key=${apiKey}&language=en-US`; // Trailer en inglés
    fetch(videosUrl)
      .then(response => response.json())
      .then(videosData => {
        // Encuentro el tráiler en inglés
        const trailer = videosData.results.find(video => video.type === 'Trailer' && video.site === 'YouTube' && video.iso_639_1 === 'en');
        if (trailer) {
          // Obtengo el identificador del video del tráiler
          const videoId = trailer.key;

          // Creo el elemento del iframe para mostrar el tráiler en inglés con la opción de subtítulos en español
          const trailerElement = document.createElement('iframe');
          trailerElement.src = `https://www.youtube.com/embed/${videoId}?rel=0&cc_load_policy=1&cc_lang_pref=es`; // Subtítulos en español
          trailerElement.title = 'Trailer';
          trailerElement.allowFullscreen = true;
          seriesTrailerContainer.appendChild(trailerElement);
        } else {
          const noTrailerMessage = document.createElement('p');
          noTrailerMessage.textContent = 'No se encontró el tráiler de la serie.';
          noTrailerMessage.classList.add('no-trailer-message'); // Agrega la clase al elemento
          seriesTrailerContainer.appendChild(noTrailerMessage);
        }
      });


    // Obtengo las plataformas de transmisión para la serie en Argentina y muéstralas
    getStreamingPlatformsInArgentina('tv', mediaId, apiKey)
      .then(platforms => {
        if (platforms && platforms.AR) {
          const streamingPlatforms = platforms.AR.flatrate;
          if (streamingPlatforms && streamingPlatforms.length > 0) {
            const platformNames = streamingPlatforms.map(platform => platform.provider_name);
            seriesPlatformsElement.textContent = `Plataformas de transmisión: ${platformNames.join(', ')}`;
          } else {
            // Muestro un mensaje indicando que no hay datos disponibles
            seriesPlatformsElement.textContent = 'Información de plataformas no disponible en Argentina en este momento';
          }
        } else {
          // Muestro un mensaje indicando que no hay datos disponibles
          seriesPlatformsElement.textContent = 'Información de plataformas no disponible en Argentina en este momento';
        }
      })
      .catch(error => {
        // Manejo de errores en caso de que la solicitud falle
        console.error('Error:', error);
        // Muestro un mensaje indicando que no hay datos disponibles
        seriesPlatformsElement.textContent = 'Información de plataformas no disponible en Argentina en este momento';
      });
  })
  .catch(error => {
    // Manejo de errores en caso de que la solicitud falle
    console.error('Error:', error);
  });
