// API Key proporcionada por themoviedb
const apiKey = '5f4e3a08b9be2e852b443b4cb14f45f7';

// URL de la API de themoviedb para obtener tendencias de películas
const trendingMoviesUrl = `https://api.themoviedb.org/3/trending/movie/day?api_key=${apiKey}&language=es-ES&page=1`;

// URL de la API de themoviedb para obtener tendencias de series
const trendingSeriesUrl = `https://api.themoviedb.org/3/trending/tv/day?api_key=${apiKey}&language=es-ES&page=1`;

// Realiza solicitudes a la API de themoviedb para tendencias de películas y series
const fetchTrendingMovies = fetch(trendingMoviesUrl).then(response => response.json());
const fetchTrendingSeries = fetch(trendingSeriesUrl).then(response => response.json());

// Espera a que ambas solicitudes se completen
Promise.all([fetchTrendingMovies, fetchTrendingSeries])
  .then(([trendingMoviesData, trendingSeriesData]) => {
    const postersContainer = document.getElementById('movie-posters');

    // Agrupa las tendencias de películas y series en grupos de 4
    const groupedContent = [];
    while (trendingMoviesData.results.length > 0 || trendingSeriesData.results.length > 0) {
      const contentGroup = [];
      for (let i = 0; i < 4; i++) {
        if (trendingMoviesData.results.length > 0) {
          contentGroup.push(trendingMoviesData.results.shift());
        }
        if (trendingSeriesData.results.length > 0) {
          contentGroup.push(trendingSeriesData.results.shift());
        }
      }
      groupedContent.push(contentGroup);
    }

    // Recorre los grupos de tendencias de películas y series
    groupedContent.forEach((group, groupIndex) => {
      // Crea una fila para el grupo de tendencias de películas y series
      const row = document.createElement('div');
      row.className = groupIndex === 0 ? 'carousel-item active' : 'carousel-item';
      const rowInner = document.createElement('div');
      rowInner.className = 'row align-items-center'; // Alinea verticalmente los elementos de la fila
      row.appendChild(rowInner);

      // Recorre las tendencias de películas y series del grupo
      group.forEach(content => {
        // Obtiene la URL de la imagen del contenido (película o serie)
        const imageUrl = `https://image.tmdb.org/t/p/w500${content.poster_path}`;

        // Crea un elemento de imagen para mostrar la carátula
        const image = document.createElement('img');
        image.src = imageUrl;
        image.alt = content.title || content.name;

        // Crea un elemento de enlace para el contenido
        const link = document.createElement('a');
        link.href = content.title
          ? `detalle-pelicula.php?id=${content.id}`
          : `detalle-serie.php?id=${content.id}`; // Enlace a la página de detalles de la película o serie
        link.className = 'col-md-3'; // Ajusta el tamaño de los posters según tus necesidades
        link.appendChild(image);

        // Agrega el enlace a la fila
        rowInner.appendChild(link);
      });

      // Agrega la fila al contenedor del carrusel
      postersContainer.appendChild(row);
    });

    // Inicializa el carrusel de Bootstrap con opciones personalizadas
    $('.carousel').carousel({
      interval: false, // Desactiva el movimiento automático
    });

    // Agrega eventos a los botones de navegación del carrusel
    $('.carousel-control-prev').click(function () {
      $('.carousel').carousel('prev');
    });

    $('.carousel-control-next').click(function () {
      $('.carousel').carousel('next');
    });
  });
