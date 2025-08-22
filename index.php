<!DOCTYPE html>
<html>
<head>
  <title>Festarit 2025</title>

  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link rel="stylesheet" href="style.css">

  <style>
@import url('https://fonts.googleapis.com/css2?family=Michroma&display=swap');
</style>

</head>
<body>

<!-- mouse light -->
  <div class="light"></div> 

  <!--
  BANNER
  <header>
  <img src="./media/consert.jpg" alt="Festari-banneri" class="header-banner" />
</header>

!-->

<main class="content">

  <h1>FESTIFESTIVAL</h1>



<nav class="navMenu">
  <a href="index.php">Etusivu</a>
  <a href="schedule.php">Ohjelmisto</a>
  <a href="info.html">Info</a>
  <a href="watchlist.php">Oma lista</a>
  <div class="dot"></div>
</nav>

  <p>Tervetuloa festifestivaleille 2025! Lorem ipsum dolor sit amet consectetur, adipisicing elit. Molestias nam quod velit molestiae, adipisci necessitatibus odit culpa tempora ad illo asperiores quibusdam repudiandae, aliquid maiores delectus laudantium? A, totam in.</p>

  <footer class="site-footer">
    <p><strong>FESTIFESTIVAL</strong></p>
    <p>Ota yhteyttä: <a href="mailto:info@festifestival.com">info@festifestival.com</a></p>
    <p>Seuraa meitä: 
      <a href="#">Facebook</a> | 
      <a href="#">Instagram</a> | 
      <a href="#">Twitter</a>
    </p>
    <p class="copyright">&copy; 2025 Festifestival. Kaikki oikeudet pidätetään.</p>
  </footer>


<!-- mouse light -->
   <script>
    const light = document.querySelector('.light');

    window.addEventListener('mousemove', e => {
      light.style.top = e.clientY + 'px';
      light.style.left = e.clientX + 'px';
    });
  </script>

  <script src="script.js"></script>


  </main>
</body>
</html>