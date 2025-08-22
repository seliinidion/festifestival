<!DOCTYPE html>
<html lang="fi">
<head>
  <meta charset="UTF-8" />
  <title>Festarit ohjelma</title>
  <link rel="stylesheet" href="style.css" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

    <style>
@import url('https://fonts.googleapis.com/css2?family=Michroma&display=swap');
</style>

</head>
<body>

<!-- mouse light -->
  <div class="light"></div> 

  <h1>OHJELMISTO</h1>
  
<main class="content">

<nav class="navMenu">
  <a href="index.php">Etusivu</a>
  <a href="schedule.php">Ohjelmisto</a>
  <a href="info.html">Info</a>
  <a href="watchlist.php">Oma lista</a>
  <div class="dot"></div>
</nav>
  <br>

<nav class="navMenu">
  <a href="watchlist.php">OMA TO-WATCH LISTA</a>
  <a href="watched.php">KATSOTUT KEIKAT</a>
  <div class="dot">
</div>
</nav>

  <p>Lisää artisti listallesi painamalla + ja tee oma to-watch lista festareille!</p>


  <div id="artists-list"></div>

  <?php
require 'db.php';

if(!isset($_COOKIE['user_id'])){
    $user_id = bin2hex(random_bytes(8));
    setcookie('user_id', $user_id, time()+60*60*24*365);
} else {
    $user_id = $_COOKIE['user_id'];
}

// USERS WATCHLIST
$watchlist = [];
$sql = "SELECT artist_id FROM watchlist WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $watchlist[] = $row['artist_id'];
}

$stmt->close();

// GET WATCHED
$watched = [];
$sql2 = "SELECT artist_id FROM watched WHERE user_id = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("s", $user_id);
$stmt2->execute();
$result2 = $stmt2->get_result();
while ($row = $result2->fetch_assoc()) {
    $watched[] = $row['artist_id'];
}
$stmt2->close();


$conn->close();
?>

<script>
const watchlist = <?php echo json_encode($watchlist); ?>;
const watched = <?php echo json_encode($watched); ?>;
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const artists = [
    { id: 1, name: 'Esiintyjä1', stage: 'PÄÄLAVA', time: '20:00' },
    { id: 2, name: 'Esiintyjä2', stage: 'RANTALAVA', time: '18:30' },
    { id: 3, name: 'Esiintyjä3', stage: 'TELTTALAVA', time: '17:15' },
    { id: 4, name: 'Esiintyjä4', stage: 'LAVALAVA', time: '20:15' },
    { id: 5, name: 'Esiintyjä5', stage: 'PÄÄLAVA', time: '21:00' },
    { id: 6, name: 'Esiintyjä6', stage: 'LAVALAVA', time: '21:00' },
  ];

  const stages = ['PÄÄLAVA', 'RANTALAVA', 'TELTTALAVA', 'LAVALAVA'];

  function renderSchedule() {
    const container = document.getElementById('artists-list');
    container.innerHTML = '';

    // ARTISTS AND STAGES
    const grouped = {};
    stages.forEach(stage => grouped[stage] = []);
    artists.forEach(artist => {
      const stageUpper = artist.stage.toUpperCase();
      if (grouped[stageUpper]) {
        grouped[stageUpper].push(artist);
      }
    });

    const screenWidth = window.innerWidth;

    if (screenWidth > 768) {
      // FULL TABLE
      const table = document.createElement('table');
      table.classList.add('schedule-table');

      // STAGE TITLES
      const thead = document.createElement('thead');
      const trHead = document.createElement('tr');
      stages.forEach(stage => {
        const th = document.createElement('th');
        th.textContent = stage;
        trHead.appendChild(th);
      });
      thead.appendChild(trHead);
      table.appendChild(thead);

      // BIGGEST ARTIST NUMBER
      const maxRows = Math.max(...stages.map(stage => grouped[stage].length));

      const tbody = document.createElement('tbody');
      for(let i = 0; i < maxRows; i++) {
        const tr = document.createElement('tr');
        stages.forEach(stage => {
          const td = document.createElement('td');
          td.setAttribute('data-label', stage);
          const artist = grouped[stage][i];
          if (artist) {
            const artistDiv = document.createElement('div');
            artistDiv.innerHTML = `<strong>${artist.name}</strong> (${artist.time})`;

            if (!watchlist.includes(artist.id) && !watched.includes(artist.id)) {
              const btn = document.createElement('button');
              btn.className = 'add-button';
              btn.textContent = '+';
              btn.addEventListener('click', e => {
                e.preventDefault();
                fetch(`add.php?id=${artist.id}`)
                  .then(response => {
                    if (response.ok) {
                      btn.style.visibility = 'hidden';
                    } else {
                      alert('Virhe lisättäessä listalle.');
                    }
                  })
                  .catch(() => alert('Yhteysvirhe!'));
              });
              artistDiv.appendChild(btn);
            }
            td.appendChild(artistDiv);
          }
          tr.appendChild(td);
        });
        tbody.appendChild(tr);
      }
      table.appendChild(tbody);
      container.appendChild(table);

    } else {
      // THINNER SCREEN
      stages.forEach(stage => {
        const stageDiv = document.createElement('div');
        stageDiv.className = 'stage-block';

        const heading = document.createElement('h2');
        heading.textContent = stage;
        stageDiv.appendChild(heading);

        grouped[stage].forEach(artist => {
          const artistDiv = document.createElement('div');
          artistDiv.className = 'artist-block';
          artistDiv.innerHTML = `<strong>${artist.name}</strong> (${artist.time})`;

          if (!watchlist.includes(artist.id) && !watched.includes(artist.id)) {
            const btn = document.createElement('button');
            btn.className = 'add-button';
            btn.textContent = '+';
            btn.addEventListener('click', e => {
              e.preventDefault();
              fetch(`add.php?id=${artist.id}`)
                .then(response => {
                  if (response.ok) {
                    btn.style.visibility = 'hidden';
                  } else {
                    alert('Virhe lisättäessä listalle.');
                  }
                })
                .catch(() => alert('Yhteysvirhe!'));
            });
            artistDiv.appendChild(btn);
          }

          stageDiv.appendChild(artistDiv);
        });

        container.appendChild(stageDiv);
      });
    }
  }

  renderSchedule();
  window.addEventListener('resize', renderSchedule);
});
</script>




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