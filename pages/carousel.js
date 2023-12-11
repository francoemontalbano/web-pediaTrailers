const apiKey = '5f4e3a08b9be2e852b443b4cb14f45f7';

const trendingMoviesUrl = `https://api.themoviedb.org/3/trending/movie/day?api_key=${apiKey}&language=es-ES&page=1`;

const trendingSeriesUrl = `https://api.themoviedb.org/3/trending/tv/day?api_key=${apiKey}&language=es-ES&page=1`;

// Realizo solicitudes a la API de themoviedb para tendencias de películas y series
const fetchTrendingMovies = fetch(trendingMoviesUrl).then(response => response.json());
const fetchTrendingSeries = fetch(trendingSeriesUrl).then(response => response.json());

// Espero a que ambas solicitudes se completen
Promise.all([fetchTrendingMovies, fetchTrendingSeries])
  .then(([trendingMoviesData, trendingSeriesData]) => {
    const postersContainer = document.getElementById('movie-posters');

    // Agrupo las tendencias de películas y series en grupos de 4
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

    // Recorro los grupos de tendencias de películas y series
    groupedContent.forEach((group, groupIndex) => {
      // Creo una fila para el grupo de tendencias de películas y series
      const row = document.createElement('div');
      row.className = groupIndex === 0 ? 'carousel-item active' : 'carousel-item';
      const rowInner = document.createElement('div');
      rowInner.className = 'row align-items-center'; // Alinea verticalmente los elementos de la fila
      row.appendChild(rowInner);

      // Recorro las tendencias de películas y series del grupo
      group.forEach(content => {
        // Obtengo la URL de la imagen del contenido (película o serie)
        const imageUrl = `https://image.tmdb.org/t/p/w500${content.poster_path}`;

        // Creo un elemento de imagen para mostrar la carátula
        const image = document.createElement('img');
        image.src = imageUrl;
        image.alt = content.title || content.name;

        // Creo un elemento de enlace para el contenido
        const link = document.createElement('a');
        link.href = content.title
          ? `detalle-pelicula.php?id=${content.id}`
          : `detalle-serie.php?id=${content.id}`; 
        link.className = 'col-md-3'; 
        link.appendChild(image);

        // Agrego el enlace a la fila
        rowInner.appendChild(link);
      });

      // Agrego la fila al contenedor del carrusel
      postersContainer.appendChild(row);
    });

    // Inicializo el carrusel de Bootstrap
    $('.carousel').carousel({
      interval: false, 
    });

    $('.carousel-control-prev').click(function () {
      $('.carousel').carousel('prev');
    });

    $('.carousel-control-next').click(function () {
      $('.carousel').carousel('next');
    });
  });
