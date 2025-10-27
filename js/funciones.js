const slidesContainer = document.querySelector('.slides');
const slides = document.querySelectorAll('.slide');
const totalSlides = slides.length;
const visible = 3;
let grupo = 0;
let autoSlideInterval;

// Mostrar grupo actual (3 imágenes)
function mostrarGrupo() {
  slidesContainer.style.transform = `translateX(-${grupo * (100)}%)`;
}

// Animar imágenes visibles (zoom in/out)
function animarGrupo() {
  const inicio = grupo * visible;
  const fin = inicio + visible;
  const visibles = Array.from(slides).slice(inicio, fin);

  visibles.forEach(s => s.querySelector('img').classList.add('zoom'));

  setTimeout(() => {
    visibles.forEach(s => s.querySelector('img').classList.remove('zoom'));
    siguienteGrupo();
  }, 1500);
}

// Pasar al siguiente grupo
function siguienteGrupo() {
  grupo++;
  const maxGrupos = Math.ceil(totalSlides / visible);
  if (grupo >= maxGrupos) grupo = 0;
  mostrarGrupo();
}

// Pasar al grupo anterior
function anteriorGrupo() {
  grupo--;
  const maxGrupos = Math.ceil(totalSlides / visible);
  if (grupo < 0) grupo = maxGrupos - 1;
  mostrarGrupo();
}

// Iniciar autoplay
function iniciarAutoPlay() {
  clearInterval(autoSlideInterval);
  autoSlideInterval = setInterval(animarGrupo, 3000);
}

// Eventos para botones
document.querySelector('.next').addEventListener('click', siguienteGrupo);
document.querySelector('.prev').addEventListener('click', anteriorGrupo);

// Iniciar
mostrarGrupo();
iniciarAutoPlay();
